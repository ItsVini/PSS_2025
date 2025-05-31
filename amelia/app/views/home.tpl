{extends file="layouts/main.tpl"}

{block name="content"}
  <section class="hero">
    <div class="hero-content container">
      <h1>Witaj w Planerze zadań</h1>
      <p>Zarządzaj zadaniami swojego zespołu szybko i wygodnie.</p>
      <div class="hero-buttons">
        {if !$user}
          <a href="{$conf->action_root}login"    class="btn btn--primary">Zaloguj się</a>
          <a href="{$conf->action_root}register" class="btn btn--secondary">Utwórz konto</a>
        {else}
          <a href="{$conf->action_root}list" class="btn btn--primary">Przejdź do zadań</a>
        {/if}
      </div>
    </div>
  </section>

  <section class="news container">
    <h2>Aktualności</h2>
    {if $news|@count}
      <div class="news-grid">
        {foreach from=$news item=item}
          <article class="news-card">
            <h3>{$item.title}</h3>
            <time datetime="{$item.date}">{$item.date}</time>
            <p>{$item.summary}</p>
            <a href="{$conf->action_root}news/{$item.id}" class="read-more">Czytaj dalej →</a>
          </article>
        {/foreach}
      </div>
    {else}
      <p class="no-news">Brak aktualności.</p>
    {/if}
  </section>
{/block}
