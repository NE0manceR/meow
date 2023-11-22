<?php
class User_Model
{
  private $conn;

  function __construct(?Database $db = null)
  {
    $this->conn = $db;
  }

  public function login($email, $password_from_user)
  {
    try {
      $sql = "SELECT `id`, `name`, `email`, `password`, `salt` FROM `users` WHERE `email` = :email";
      $stmt = $this->conn->prepare($sql);

      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        $hashedPasswordFromDatabase = $result['password'];
        $salt_from_db = $result['salt'];

        if (password_verify($password_from_user . $salt_from_db, $hashedPasswordFromDatabase)) {
          $sql = "SELECT * FROM `cats`";
          $stmt = $this->conn->prepare($sql);
          $stmt->execute();
          $all_cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $_SESSION['user_id'] = $result['id'];

          $info = [
            "name" => $result["name"],
            "id" => $result["id"],
            "text" => "Мяу",
            "all_cats" => $all_cats,
          ];

          echo json_encode($info);
        } else {

          $info = [
            "error" => "Помилка автентифікації",
            "text" => "Не валідні дані"
          ];
          echo json_encode($info);
        }
      } else {
        $info = [
          "error" => "Помилка автентифікації",
          "text" => "Не валідні дані"
        ];
        echo json_encode($info);
      }
    } catch (PDOException $e) {
      echo "Помилка виконання SQL-запиту: " . $e->getMessage();
    }
  }

  public function logout()
  {
    // Перевірка, чи надісланий параметр logout
    session_unset(); // Знищити всі змінні сесії
    session_destroy(); // Знищити сесію
    // Якщо потрібно, ви можете виконати інші дії, наприклад, перенаправити користувача на сторінку виходу або вивести повідомлення
    echo json_encode(["message" => "Сесія видалена успішно"]);
    exit;
  }

  public function registration($name, $email, $user_password)
  {
    try {
      $sql = "INSERT INTO `users` (`name`, `email`, `password`, `salt`) VALUES (:name, :email, :password, :salt)";
      $stmt = $this->conn->prepare($sql);

      $password = $user_password;
      $salt = bin2hex(random_bytes(16));
      $hashedPassword = password_hash($password . $salt, PASSWORD_BCRYPT);

      $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':salt', $salt, PDO::PARAM_STR);
      $stmt->execute();

      $new_user_id = $this->conn->lastInsertId();

      session_start();
      $_SESSION['user_id'] = $new_user_id;

      $info = [
        'status' => 'success',
        "text" => 'Акаунт створено',
      ];

      echo json_encode($info);
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
        $info = [
          'status' => 'error',
          "text" => 'Акаунт вже існує',
        ];

        echo json_encode($info);
      } else {
        $info = [
          'status' => 'error',
          "text" => $e->getMessage(),
        ];

        echo json_encode($info);
      }
    }
  }
}
