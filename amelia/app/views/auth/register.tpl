{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="form-container container">
    <h2>Rejestracja</h2>

    {if $msgs->isError()}
      <div class="errors">
        {foreach from=$msgs->getMessages() item=msg}
          <p class="error">{$msg->text nofilter}</p>
        {/foreach}
      </div>
    {/if}

    <form method="post" action="{$conf->action_root}register" class="auth-form">
      <label>Login:
        <input type="text" name="username" value="{$form.username|default:''}">
      </label>
      <label>Imię:
        <input type="text" name="first_name" value="{$form.first_name|default:''}">
      </label>
      <label>Nazwisko:
        <input type="text" name="last_name" value="{$form.last_name|default:''}">
      </label>
      <label>E-mail:
        <input type="email" name="email" value="{$form.email|default:''}">
      </label>
      <label>Hasło:
        <input type="password" name="password">
      </label>
      <label>Potwierdź hasło:
        <input type="password" name="password_confirm">
      </label>
      <button type="submit" class="btn btn--primary">Załóż konto</button>
    </form>
  </div>
{/block}
