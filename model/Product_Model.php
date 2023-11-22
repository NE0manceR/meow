<?php

class Product_Model
{
  private $conn;

  function __construct(?Database $db = null)
  {
    $this->conn = $db;
  }

  public function get_products($user_id)
  {

    $sql = "SELECT * FROM cats LIMIT 3";

    if ($user_id !== '-1') {
      $sql = "SELECT * FROM cats";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $all_cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    return $all_cats;
  }

  public function get_all_groups()
  {
    $sql = "SELECT * FROM cat_groups";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    return $all_groups;
  }

  public function add_cat($name, $description, $img_src, $group_id)
  {
    try {
      $sql = "INSERT INTO `cats` (`name`, `description`, `img_src`) VALUES (:name, :description, :img_src)";
      $stmt = $this->conn->prepare($sql);

      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);
      $stmt->bindParam(':img_src', $img_src, PDO::PARAM_STR);
      $stmt->execute();

      $new_cat_id = $this->conn->lastInsertId();

      $sql = "INSERT INTO `cat_group_relationship` (`cat_id`, `group_id`) VALUES (:cat_id, :group_id)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':cat_id', $new_cat_id, PDO::PARAM_STR);
      $stmt->bindParam(':group_id', $group_id, PDO::PARAM_STR);

      $stmt->execute();

      $info = [
        'status' => 'success',
        "text" => 'Немає у тебе сердця',
      ];

      echo json_encode($info);
    } catch (\Throwable $th) {
      $info = [
        'status' => 'error',
        "text" => $th->getMessage(),
      ];

      echo json_encode($info);
    }
  }

  public function update_cat($name, $description, $img_src, $id): array
  {
    try {
      $sql =  "UPDATE `cats` SET `name`=:name,`description`=:description,`img_src`=:img_src WHERE `id`=:id";
      $stmt = $this->conn->prepare($sql);

      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);
      $stmt->bindParam(':img_src', $img_src, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();

      return [
          'status' => 'success',
          "text" => 'Кота Змінено',
      ];

    } catch (\PDOException $e) {

      return [
          'status' => 'error',
          'text' => $e->getMessage(),
      ];
    }
  }

  public function delete_cat($id): array
  {
      try {
        $sql = "DELETE FROM `cats` WHERE `id`=:id; DELETE FROM `cat_group_relationship` WHERE `cat_id` = :cat_id  ;";

          $stmt = $this->conn->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_STR);
          $stmt->bindParam(':cat_id', $id, PDO::PARAM_STR);
          $stmt->execute();

          return [
              'status' => 'success',
              "text" => 'Кота видалено',
          ];
      }catch (\PDOException $e) {

          return [
              'status' => 'error',
              'text' => $e->getMessage(),
          ];
      }

  }
}
