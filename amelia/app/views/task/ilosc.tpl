{extends file="../layouts/main.tpl"}

{block name="content"}
  <div class="container user-management">
    <h2 class="section-title">Ilosc zadan</h2>

    <div class="table-wrapper">
      <table class="user-table">
        <thead>
          <tr>
            <th>Imie i nazwisko</th>
            <th>Ilosc zadan</th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$users item=u}
            <tr>
              <td>{$u.first_name|escape} {$u.last_name|escape}</td>
              <td></td>
            </tr>
          {/foreach}
        </tbody>
      </table>
    </div>
  </div>
 {/block}
