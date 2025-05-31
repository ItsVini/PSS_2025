<?php
namespace app\controllers;

use core\App;
use core\SessionUtils;
use core\Message;
use core\ParamUtils;
use app\models\TaskModel;
use app\models\StatusModel;
use app\models\UserModel;
use app\models\CommentModel;

class TaskCtrl {
    public function action_list(): void {
    $smarty  = App::getSmarty();
    $msgs    = App::getMessages();
    $current = SessionUtils::loadObject('user', true);

    if (!$current) {
        $msgs->addMessage(new Message('Musisz się zalogować, aby zobaczyć zadania.', Message::ERROR));
        App::getRouter()->redirectTo('login');
        return;
    }

    // Filtry
    $search         = ParamUtils::getFromGet('search', true)   ?? '';
    $statusFilter   = ParamUtils::getFromGet('status', true)   !== null ? (int)ParamUtils::getFromGet('status', true)   : null;
    $assignedFilter = ParamUtils::getFromGet('assigned', true) !== null ? (int)ParamUtils::getFromGet('assigned', true) : null;

    // Pobierz statusy
    $statuses  = StatusModel::getAll();
    $employees = [];
    if ($current['role_id'] >= 3) {
        $employees = array_filter(
            UserModel::getAll(),
            fn($u) => $u['role_id'] === 2
        );
    }       

    // $findAll = TaskModel::findAll($current['id']);


    // Pobierz zadania wg roli i filtrów
    if ($current['role_id'] >= 3) {
        $tasks = TaskModel::getFilteredAll($search, $statusFilter, $assignedFilter);
    } else {
        $tasks = TaskModel::getFilteredForUser($current['id'], $search, $statusFilter);
    }

    $empMap = [];
    foreach ($employees as $e) {
        $empMap[$e['id']] = $e['first_name'].' '.$e['last_name'];
    }
    foreach ($tasks as &$t) {
        $t['assigned_name'] = $empMap[$t['assigned_to']] ?? '';
    }
    unset($t);

    $board = [];
    foreach ($statuses as $s) {
        $board[$s['id']] = array_filter(
            $tasks,
            fn($t) => $t['status_id'] === $s['id']
        );
    }

    $smarty->assign([
        'search'         => $search,
        'statusFilter'   => $statusFilter,
        'assignedFilter' => $assignedFilter,
        'statuses'       => $statuses,
        'employees'      => $employees,
        'board'          => $board,
        'user'           => $current,
        'isMgr'          => $current['role_id'] >= 3,
    ]);

    $smarty->display('task/list.tpl');
}

public function action_create(): void {
    $smarty    = App::getSmarty();
    $current   = SessionUtils::loadObject('user', true);
    $msgs      = App::getMessages();
    $isManager = ($current['role_id'] >= 3);

    // GET
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        if ($isManager) {
            $emps = UserModel::getAll();
            $smarty->assign('employees', array_filter($emps, fn($u) => $u['role_id'] === 2));
        }
        $smarty->assign('isMgr', $isManager);
        $smarty->display('task/create.tpl');
        return;
    }

    // POST
    $title      = trim(ParamUtils::getFromPost('title'));
    $start_date = ParamUtils::getFromPost('start_date');
    $end_date   = ParamUtils::getFromPost('end_date');
    $assigned   = $isManager
                ? (int) ParamUtils::getFromPost('assigned_to')
                : $current['id'];
    $comment    = trim(ParamUtils::getFromPost('comment'));

    // WALIDACJA
    $today = date('Y-m-d');
    if ($title === '' || !$start_date || !$end_date) {
        $msgs->addMessage(new Message('Wszystkie pola są wymagane.', Message::ERROR));
    } elseif ($start_date < $today) {
        $msgs->addMessage(new Message("Data rozpoczęcia nie może być wcześniejsza niż {$today}.", Message::ERROR));
    } elseif ($end_date < $start_date) {
        $msgs->addMessage(new Message('Data zakończenia nie może być wcześniejsza niż data rozpoczęcia.', Message::ERROR));
    }
    if ($isManager) {
        $emp = UserModel::findById($assigned);
        if (!$emp) {
            $msgs->addMessage(new Message('Wybrany pracownik nie istnieje.', Message::ERROR));
        }
    }

    if ($msgs->isError()) {
        if ($isManager) {
            $emps = UserModel::getAll();
            $smarty->assign('employees', array_filter($emps, fn($u) => $u['role_id'] === 2));
        }
        $smarty->assign('isMgr', $isManager);
        $smarty->assign('form', array_merge($_GET, $_POST));
        $smarty->display('task/create.tpl');
        return;
    }

    // ZAPIS ZADANIA
    $newTaskId = TaskModel::create([
      'title'       => $title,
      'start_date'  => $start_date,
      'end_date'    => $end_date,
      'status_id'   => 1,                   
      'created_by'  => $current['id'],
      'assigned_to'=> $assigned,
      'created_at'  => date('Y-m-d H:i:s')
    ]);

    // DODANIE PIERWSZEGO KOMENTARZA
    if ($comment !== '') {
        CommentModel::create([
          'task_id'   => $newTaskId,
          'author_id' => $current['id'],
          'content'   => $comment,
        ]);
    }

    $msgs->addMessage(new Message('Zadanie zostało utworzone.', Message::INFO));
    App::getRouter()->redirectTo('list');
}


public function action_edit(): void {
    $smarty  = App::getSmarty();
    $msgs    = App::getMessages();
    $current = SessionUtils::loadObject('user', true);

    if (!$current) {
        $msgs->addMessage(new Message('Musisz się zalogować, aby edytować zadanie.', Message::ERROR));
        App::getRouter()->redirectTo('login');
        return;
    }
    $isMgr = ($current['role_id'] >= 3);

    //  GET
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $id   = (int) ParamUtils::getFromGet('id');
        $task = TaskModel::findById($id);

        if (!$task) {
            $msgs->addMessage(new Message('Zadanie nie istnieje.', Message::ERROR));
            App::getRouter()->redirectTo('list');
            return;
        }
        if (!$isMgr && $task['assigned_to'] !== $current['id']) {
            $msgs->addMessage(new Message('Brak dostępu do tego zadania.', Message::ERROR));
            App::getRouter()->redirectTo('list');
            return;
        }

        // Statusy
        $allStatuses = StatusModel::getAll();
        $statuses    = $isMgr
                     ? $allStatuses
                     : array_filter($allStatuses, fn($s) => strtolower($s['name']) !== 'closed');

        // Pracownicy
        if ($isMgr) {
            $employees = array_filter(
                UserModel::getAll(),
                fn($u) => $u['role_id'] === 2
            );
            $smarty->assign('employees', $employees);
        }

        // Komentarze
        $comments    = CommentModel::getForTask($task['id']);
        $authorMap   = [];
        foreach ($comments as $c) {
            $u = UserModel::findById($c['author_id']);
            $authorMap[$c['author_id']] = $u
                ? "{$u['first_name']} {$u['last_name']}"
                : '–';
        }

        $smarty->assign([
            'task'           => $task,
            'statuses'       => $statuses,
            'isMgr'          => $isMgr,
            'comments'       => $comments,
            'commentAuthors' => $authorMap,
        ]);
        $smarty->display('task/edit.tpl');
        return;
    }

    // 3) POST
    $id         = (int) ParamUtils::getFromPost('id');
    $task       = TaskModel::findById($id);
    if (!$task) {
        $msgs->addMessage(new Message('Zadanie nie istnieje.', Message::ERROR));
        App::getRouter()->redirectTo('list');
        return;
    }
    if (!$isMgr && $task['assigned_to'] !== $current['id']) {
        $msgs->addMessage(new Message('Brak dostępu do tego zadania.', Message::ERROR));
        App::getRouter()->redirectTo('list');
        return;
    }

    $title     = trim(ParamUtils::getFromPost('title'));
    $sd        = ParamUtils::getFromPost('start_date');
    $ed        = ParamUtils::getFromPost('end_date');
    $ns        = (int) ParamUtils::getFromPost('status_id');
    $na        = $isMgr 
               ? (int) ParamUtils::getFromPost('assigned_to')
               : $current['id'];

    $allStatuses = StatusModel::getAll();
    $validIds    = $isMgr
                 ? array_column($allStatuses, 'id')
                 : array_column(
                     array_filter($allStatuses, fn($s) => strtolower($s['name']) !== 'closed'),
                     'id'
                   );

    $hasError = false;
    $today    = date('Y-m-d');

    if ($title === '') {
        $msgs->addMessage(new Message('Tytuł jest wymagany.', Message::ERROR));
        $hasError = true;
    }
    if (!$sd || !$ed) {
        $msgs->addMessage(new Message('Obie daty są wymagane.', Message::ERROR));
        $hasError = true;
    } elseif ($sd < $today) {
        $msgs->addMessage(new Message("Data rozpoczęcia nie może być wcześniejsza niż dziś ($today).", Message::ERROR));
        $hasError = true;
    } elseif ($ed < $sd) {
        $msgs->addMessage(new Message('Data zakończenia nie może być przed datą rozpoczęcia.', Message::ERROR));
        $hasError = true;
    }
    if (!in_array($ns, $validIds, true)) {
        $msgs->addMessage(new Message('Wybrano niedozwolony status.', Message::ERROR));
        $hasError = true;
    }

    if ($isMgr) {
        $emp = UserModel::findById($na);
        if (!$emp || $emp['role_id'] !== 2) {
            $msgs->addMessage(new Message('Wybrany pracownik nie istnieje lub nie ma odpowiedniej roli.', Message::ERROR));
            $hasError = true;
        }
    }

    if ($hasError) {
        $statuses = $isMgr
                  ? $allStatuses
                  : array_filter($allStatuses, fn($s) => strtolower($s['name']) !== 'closed');
        $smarty->assign('task', [
            'id'           => $id,
            'title'        => $title,
            'start_date'   => $sd,
            'end_date'     => $ed,
            'status_id'    => $task['status_id'],
            'assigned_to'  => $task['assigned_to'],
        ]);
        $smarty->assign('statuses', $statuses);
        $smarty->assign('isMgr',    $isMgr);
        if ($isMgr) {
            $employees = array_filter(
                UserModel::getAll(),
                fn($u) => $u['role_id'] === 2
            );
            $smarty->assign('employees', $employees);
        }
        $comments  = CommentModel::getForTask($id);
        $authorMap = [];
        foreach ($comments as $c) {
            $u = UserModel::findById($c['author_id']);
            $authorMap[$c['author_id']] = $u
                ? "{$u['first_name']} {$u['last_name']}"
                : '–';
        }
        $smarty->assign('comments',       $comments);
        $smarty->assign('commentAuthors', $authorMap);

        $smarty->display('task/edit.tpl');
        return;
    }

    try {
        TaskModel::update($id, [
            'title'        => $title,
            'start_date'   => $sd,
            'end_date'     => $ed,
            'status_id'    => $ns,
            'assigned_to'  => $na,
        ]);
    } catch (\PDOException $e) {
        $msgs->addMessage(new Message('Błąd zapisu w bazie danych.', Message::ERROR));
        App::getRouter()->redirectTo("edit&id={$id}");
        return;
    }

    $msgs->addMessage(new Message('Zadanie zaktualizowane pomyślnie.', Message::INFO));
    App::getRouter()->redirectTo('list');
}

public function action_taskComment(): void {
    $current = SessionUtils::loadObject('user', true);
    $msgs    = App::getMessages();

    if (!$current) {
        $msgs->addMessage(new Message('Musisz się zalogować, aby dodać komentarz.', Message::ERROR));
        App::getRouter()->redirectTo('login');
        return;
    }

    $taskId  = (int) ParamUtils::getFromPost('task_id');
    $content = trim(ParamUtils::getFromPost('content'));

    if ($content === '') {
        $msgs->addMessage(new Message('Komentarz nie może być pusty.', Message::ERROR));
        App::getRouter()->redirectTo("edit&id={$taskId}");
        return;
    }

    try {
        CommentModel::create([
            'task_id'   => $taskId,
            'author_id' => $current['id'],
            'content'   => $content,
        ]);
        $msgs->addMessage(new Message('Komentarz dodany pomyślnie.', Message::INFO));
    } catch (\PDOException $e) {
        $msgs->addMessage(new Message('Błąd podczas zapisu komentarza.', Message::ERROR));
    }

    App::getRouter()->redirectTo("edit&id={$taskId}");
}

}
