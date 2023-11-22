<header class="header">
  <div class="container header__container">
    <a class="logo" href="/">Meow</a>
    <label class="header__search">
      <input type="text">
      <button type="button" class="btn main-btn">Пошук</button>
    </label>
    <div class="header__user-panel">
      <a href="/admin" class="btn main-btn admin-btn <?= $_SESSION['user_id'] == -1 ? 'hide-element' : '' ?>">Адмін панель</a>
      <button class="btn main-yellow header-regestration-btn <?= $_SESSION['user_id'] != -1 ? 'hide-element' : '' ?>">Реєстрація</button>

      <button class="btn main-green toggle-login-modal header-login-btn <?= $_SESSION['user_id'] != -1 ? 'hide-element' : '' ?>">Логінація</button>

      <button class="btn alert-btn logout-btn <?= $_SESSION['user_id'] == -1 ? 'hide-element' : '' ?>">Вихід</button>

    </div>
  </div>
</header>

<script>
  $(document).ready(function() {
    $('.login-btn').on('click', () => {
      login_func(<?php echo json_encode($_SESSION['user_id'] == -1); ?>);
    });


    $('.logout-btn').on('click', () => {
      logout(<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>);
    })

    $('.registration-btn').on('click', function() {
      registration(<?php echo json_encode($_SESSION['user_id'] == -1); ?>);
    })
  })
</script>