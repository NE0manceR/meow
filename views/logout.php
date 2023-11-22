<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {

  session_start();
  require_once(__DIR__ . '/../controllers/User_Controller.php');
  require_once(__DIR__ . '/../model/User_Model.php');

  $user_model = new User_Model();
  $user_controller = new User_Controller($user_model);

  $user_controller->logout();
}
