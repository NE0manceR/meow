<?php
include(__DIR__ . '/../config/config.php');

try {

  require_once(__DIR__ . '/../library/Database.php');
  require_once(__DIR__ . '/../controllers/User_Controller.php');
  require_once(__DIR__ . '/../model/User_Model.php');

  $db = new Database($db_config);
  $user_model = new User_Model($db);
  $user_controller = new User_Controller($user_model);

  if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {
    echo $user_controller->registration($_POST['name'], $_POST['email'], $_POST['password']);
  }
} catch (\Throwable $th) {
  echo $th->getMessage();
}
