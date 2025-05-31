{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="container">
    <h2>Zmień rolę użytkownika: {$userToEdit.username}</h2>

    {if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=msg}
          <p class="error">{$msg->text nofilter}</p>
        {/foreach}
      </div>
    {/if}

    <form method="post" action="{$conf->action_root}userEdit" class="role-form">
      <input type="hidden" name="id" value="{$userToEdit.id}">
      <label>Nowa rola:
        <select name="role_id">
          {foreach from=$roles item=r}
            <option value="{$r.id}" {if $r.id == $userToEdit.role_id}selected{/if}>
              {$r.name}
            </option>
          {/foreach}
        </select>
      </label>
      <div class="form-buttons">
  <button type="submit" class="btn btn--primary">Zapisz</button>
  <a href="{$conf->action_root}home" class="btn btn--secondary">Anuluj</a>
</div>
    </form>
  </div>
{/block}
