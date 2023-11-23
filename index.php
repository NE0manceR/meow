<?php session_start();
if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = -1;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/style/style.min.css?1">
  <script src="/scripts/jquery-3.7.1.min.js"></script>
  <script src="/scripts/sweetalert2@11.js"></script>
  <link rel="icon" href="./style/images/paw-icon.png" type="image/x-icon">
  <title>Meow</title>
</head>

<body class="bg-light">
  <?php require_once(__DIR__ . '/components/__header.php') ?>
  <?php require_once(__DIR__ . '/components/modals/__logination-window.php') ?>
  <?php require_once(__DIR__ . '/components/modals/__regestration-window.php') ?>
  <?php require_once(__DIR__ . '/router.php') ?>

  <div class="bcg toggle-login-modal"></div>
  <script src="scripts/script.js"></script>
</body>

</html>