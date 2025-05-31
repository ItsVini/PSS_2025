{extends file="../layouts/main.tpl"}

{block name="content"}
{if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=m}
          <p class="error">{$m->text nofilter}</p>
        {/foreach}
      </div>
    {/if}
  <div class="container">

      {* <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>Imię i nazwisko</th>
            <th>Ilość zadań</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$users item=u}
            <tr>
              <td></td>
              <td>
               
              </td> *}

    <form
      method="get"
      action="{$conf->action_root}list"
      class="filter-form"
    >
      <input type="hidden" name="action" value="list">

      <input
        type="text"
        name="search"
        placeholder="Szukaj po tytule..."
        value="{$search|escape}"
      >

      <select name="status">
        <option value="">Wszystkie statusy</option>
        {foreach from=$statuses item=s}
          <option value="{$s.id}"
                  {if $statusFilter === $s.id}selected{/if}>
            {if $s.name == 'new'}Nowe
            {elseif $s.name == 'in_progress'}W realizacji
            {elseif $s.name == 'completed'}Zakończone
            {elseif $s.name == 'closed'}Zamknięte{/if}
          </option>
        {/foreach}
      </select>

      {if $user.role_id >= 3}
        <select name="assigned">
          <option value="">Wszyscy pracownicy</option>
          {foreach from=$employees item=e}
            <option value="{$e.id}"
                    {if $assignedFilter === $e.id}selected{/if}>
              {$e.first_name} {$e.last_name}
            </option>
          {/foreach}
        </select>
      {/if}

      <button type="submit" class="btn btn--small btn--primary">Filtruj</button>
      <a href="{$conf->action_root}list" class="btn btn--small btn--secondary">Wyczyść</a>
      
    </form>

    <div class="board-columns">
      {foreach from=$statuses item=s}
        <div class="column column-{$s.name}">
          <h3>
            {if $s.name == 'new'}Nowe
            {elseif $s.name == 'in_progress'}W realizacji
            {elseif $s.name == 'completed'}Zakończone
            {elseif $s.name == 'closed'}Zamknięte{/if}
          </h3>
          <ul class="task-list">
            {foreach from=$board[$s.id] item=t}
              <li class="task-card">
                <h4>{$t.title|escape}</h4>
                <small>Przypisane: {$t.assigned_name|escape}</small>
                <small>Od: {$t.start_date}</small>
                <small>Do: {$t.end_date}</small>
                <div class="task-actions">
                  <a href="{$conf->action_root}edit&id={$t.id}" class="btn btn--small">Edytuj</a>
                </div>
              </li>
            {/foreach}
            {if !$board[$s.id]|@count}
              <li class="no-task">Brak zadań</li>
            {/if}
          </ul>
        </div>
      {/foreach}
    </div>
    {if $user}
      <div class="create-task" style="text-align: right; margin-bottom:1rem;">
        <a href="{$conf->action_root}create" class="btn btn--primary">+ Nowe zadanie</a>
      </div>
    {/if}

  </div>
{/block}
