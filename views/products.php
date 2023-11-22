<?php
session_start();
include(__DIR__ . '/../config/config.php');

try {

  require_once(__DIR__ . '/../library/Database.php');
  require_once(__DIR__ . '/../controllers/Product_Controller.php');
  require_once(__DIR__ . '/../model/Product_Model.php');

  $db = new Database($db_config);
  $product_model = new Product_Model($db);
  $product_controller = new Product_Controller($product_model);

  if (isset($_POST['user_id'])) {
    echo json_encode($product_controller->get_products($_POST['user_id']));
  }

  if (isset($_POST['groups'])) {
    $response['groups'] = $product_controller->get_all_groups();
    $response['products'] = $product_controller->get_products(1);
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  if (isset($_POST['group_id'])) {

    if (isset($_FILES['file'])) {

      $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'; // Ваш каталог для зберігання завантажених файлів

      $uploadedFile = $_FILES['file'];
      $filename = $uploadDirectory . basename($uploadedFile['name']);
      $src = '/uploads/' . basename($uploadedFile['name']);

      // Переміщення файлу з тимчасового каталогу
      if (move_uploaded_file($uploadedFile['tmp_name'], $filename)) {
        $result = $product_controller->add_cat($_POST['name'], $_POST['description'], $src, $_POST['group_id']);

        echo $result;
      }
    } else {
      $product_controller->add_cat($_POST['name'], $_POST['description'], $_POST['img_src'], $_POST['group_id']);
    }
  }

  if (isset($_POST['update'])) {
    $response['status'] =  $product_controller->update_cat($_POST['name'], $_POST['description'], $_POST['img_src'], $_POST['id']);
    $response['products'] = $product_controller->get_products(1);
    header('Content-Type: application/json');
    echo json_encode($response);
  }

  if (isset($_POST['delete'])) {
    $response['status'] =  $product_controller->delete_cat($_POST['delete']);
    $response['products'] = $product_controller->get_products(1);
    echo json_encode($response);
  }
} catch (\Throwable $th) {
  echo $th->getMessage();
}
