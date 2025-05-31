{* File: app/views/layouts/main.tpl *}
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$conf->app_name|default:'Planer zadań'}</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="{$conf->action_root}home" class="logo">Planer zadań</a>
      <nav>
        <ul class="nav-list">
          <li><a href="{$conf->action_root}home">Strona główna</a></li>
          {if $user && $user.role_id == 4}
            <li><a href="{$conf->action_root}users">Zarządzaj użytkownikami</a></li>
          {/if}
          {if $user}
          {if $user && $user.role_id != 1}
            <li><a href="{$conf->action_root}list">Moje zadania</a></li>
            <li><a href="{$conf->action_root}lista">Ilość zadań</a></li>
            {/if}
            <li><a href="{$conf->action_root}logout">Wyloguj ({$user.username})</a></li>
          {else}
            <li><a href="{$conf->action_root}login">Zaloguj</a></li>
            <li><a href="{$conf->action_root}register">Rejestracja</a></li>
          {/if}
        </ul>
      </nav>
    </div>
  </header>

  <main class="site-main container">
    
    {block name="content"}{/block}
  </main>
</body>
</html>
