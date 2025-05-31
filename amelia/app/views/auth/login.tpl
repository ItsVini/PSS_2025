{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="form-container container">
    <h2>Logowanie</h2>

    {if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=msg}
          <p class="error">{$msg->text nofilter}</p>
        {/foreach}
      </div>
    {/if}

    <form method="post" action="{$conf->action_root}login" class="auth-form">
      <label>
        Login:
        <input type="text" name="username" value="{$form.username|default:''}">
      </label>
      <label>
        Hasło:
        <input type="password" name="password">
      </label>
      <button type="submit" class="btn btn--primary">Zaloguj</button>
    </form>
  </div>
{/block}
