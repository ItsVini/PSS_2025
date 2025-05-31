{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="container user-management">
    <h2 class="section-title">Zarządzaj użytkownikami</h2>

    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>Imię i nazwisko</th>
            <th>E-mail</th>
            <th>Rola</th>
            <th>Akcje</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$users item=u}
            <tr>
              <td>{$u.first_name|escape} {$u.last_name|escape}</td>
              <td>{$u.email|escape}</td>
              <td>
                {if isset($roleMap[$u.role_id])}
                  {$roleMap[$u.role_id]|capitalize|escape}
                {else}
                  –  
                {/if}
              </td>
              <td>
                {*
                  pokazujemy przycisk tylko jeśli rola _przeglądanej_ osoby
                  nie jest adminem (role_id == 4)
                *}
                {if $u.role_id < 4}
                  <a href="{$conf->action_root}userEdit&id={$u.id}"
                     class="btn btn--small btn--primary">
                    Zmień rolę
                  </a>
                {else}
                  <span style="color:#999;font-style:italic;">—</span>
                {/if}
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </div>
 {/block}
