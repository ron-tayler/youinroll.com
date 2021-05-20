<header class="sidebar-mobile">
  <div class="container">
    <nav class="bottom-nav">
      <div class="bottom-nav-item <?=(!isset($_SERVER['REQUEST_URI']) || ltrim($_SERVER['REQUEST_URI'],'/') === '') ? 'active'  : ''?>">
		<div onclick="location.href='<?=is_user()? profile_url(user_id(), user_name()).'?sk=about':'/login'?>>'" class="bottom-nav-link">
			<i class="material-icons">home</i>
			<span>Мой канал</span>
		</div>
      </div>
      <? /* TODO Хардкод решение по выключению трендов ?>
      <div onclick="location.href='/activity'" class="bottom-nav-item <?=($_SERVER['REQUEST_URI'] === '/activity') ? 'active'  : ''?>">
        <div class="bottom-nav-link">
          <i class="material-icons">favorite</i>
          <span>В тренде</span>
        </div>
      </div>
      <? //*/ ?>
      <div onclick="location.href='/videos/browse/'" class="bottom-nav-item <?=($_SERVER['REQUEST_URI'] === '/videos/browse/') ? 'active'  : ''?>">
        <div class="bottom-nav-link">
          <i class="material-icons">play_arrow</i>
          <span>Просмотры</span>
        </div>
      </div>
      <div onclick="location.href='<?=site_url().members?>'" class="bottom-nav-item <?=($_SERVER['REQUEST_URI'] === site_url().members) ? 'active'  : ''?>">
        <div class="bottom-nav-link">
          <i class="material-icons">account_circle</i>
          <span>Сообщества</span>
        </div>
      </div>
      <div onclick="location.href='/dashboard'" class="bottom-nav-item <?=($_SERVER['REQUEST_URI'] === '/dashboard') ? 'active'  : ''?>">
        <div class="bottom-nav-link">
          <i class="material-icons">settings</i>
          <span>Студия</span>
        </div>
      </div>
    </nav>
  </div>
</header>