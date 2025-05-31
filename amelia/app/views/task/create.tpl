{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="form-container">
    <h2>Nowe zadanie</h2>

    {if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=m}
          <p class="error">{$m->text nofilter}</p>
        {/foreach}
      </div>
    {/if}

    <form method="post" action="{$conf->action_root}create" class="task-form">
      <label>
        Tytuł:
        <input type="text" name="title" value="{$form.title|default:''}">
      </label>

      <label>
        Od:
        <input type="date" name="start_date" value="{$form.start_date|default:''}">
      </label>

      <label>
        Do:
        <input type="date" name="end_date" value="{$form.end_date|default:''}">
      </label>

      {if $isMgr}
        <label>
          Przypisz do:
          <select name="assigned_to">
            {foreach from=$employees item=e}
              <option 
                value="{$e.id}"
                {if $form.assigned_to|default:'' == $e.id}selected{/if}
              >
                {$e.first_name|escape} {$e.last_name|escape}
              </option>
            {/foreach}
          </select>
        </label>
      {/if}

      <label>
        Komentarz (opcjonalnie):
        <textarea 
          name="comment" 
          class="form-textarea"
          placeholder="Pierwszy komentarz…"
        >{$form.comment|default:''}</textarea>
      </label>

      <div class="form-buttons">
        <button type="submit" class="btn btn--primary">Utwórz zadanie</button>
        <a href="{$conf->action_root}list" class="btn btn--secondary">Anuluj</a>
      </div>
    </form>
  </div>
{/block}
