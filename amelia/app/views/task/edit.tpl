{extends file="../layouts/main.tpl"}

{block name="content"}
{if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=m}
          <p class="error">{$m->text nofilter}</p>
        {/foreach}
      </div>
    {/if}
  <div class="container edit-layout">

  

    <div class="edit-col">
      <h2>Edytuj zadanie</h2>

      <form method="post" action="{$conf->action_root}edit" id="editForm" class="auth-form">
        <input type="hidden" name="id" value="{$task.id}">

        <label>Tytuł:
          <input type="text" name="title" value="{$task.title|escape}">
        </label>

        <label>Od:
          <input type="date" name="start_date" value="{$task.start_date}">
        </label>

        <label>Do:
          <input type="date" name="end_date" value="{$task.end_date}">
        </label>

        <label>Status:
          <select name="status_id">
    {foreach from=$statuses item=s}
      <option value="{$s.id}" {if $s.id == $task.status_id}selected{/if}>
        {if $s.name == 'new'}Nowe
        {elseif $s.name == 'in_progress'}W realizacji
        {elseif $s.name == 'completed'}Zakończone
        {elseif $s.name == 'closed'}Zamknięte
        {else}{$s.name|capitalize}{/if}
      </option>
    {/foreach}
          </select>
        </label>

        {if $isMgr}
          <label>Przypisz do:
            <select name="assigned_to">
              {foreach from=$employees item=e}
                <option value="{$e.id}" {if $e.id == $task.assigned_to}selected{/if}>
                  {$e.first_name} {$e.last_name}
                </option>
              {/foreach}
            </select>
          </label>
        {/if}
      </form>

      <form method="post" action="{$conf->action_root}taskComment" id="commentForm" class="comment-form">
        <input type="hidden" name="task_id" value="{$task.id}">
        <label>Nowy komentarz:
          <textarea name="content" rows="3" required>{$form.content|default:''}</textarea>
        </label>
      </form>

      <div class="form-buttons">
        <button type="submit" form="editForm"    class="btn btn--primary">Zapisz</button>
        <a      href="{$conf->action_root}list" class="btn btn--secondary">Anuluj</a>
        <button type="submit" form="commentForm" class="btn btn--primary">Dodaj komentarz</button>
      </div>
    </div>

    <div class="comments-col">
      <h3>Komentarze</h3>

      {if $comments|@count}
        <div class="comments-list">
          {foreach from=$comments item=c}
            <div class="comment-card">
              <div class="meta">
                <strong>{$commentAuthors[$c.author_id]|escape}</strong>
                <span>&middot; {$c.created_at}</span>
              </div>
              <p>{$c.content|escape}</p>
            </div>
          {/foreach}
        </div>
      {else}
        <p class="no-comments">Brak komentarzy.</p>
      {/if}
    </div>

  </div>
{/block}
