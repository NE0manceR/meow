<?php
if ($_SESSION['user_id'] == -1) {
  header('Location: /');
}

?>

<script>
  let all_cats = {};
  $.ajax({
    url: window.location.origin + "/views/products.php",
    method: "POST",
    data: {
      groups: 'all_groups'
    },
    dataType: "json",
    success: function(response) {

      response.groups.forEach(element => {
        $('.group_select').append(`
            <option value='${element.group_id}'>${element.name}</option>
        `)
      });

      response.products.forEach(element => {
        $('.cat_select').append(`
            <option value='${element.id}'>${element.name}</option>
        `)
      });

      all_cats = response.products;
    },
    error: function(xhr, status, error) {
      console.log("Помилка запиту: " + status);
      console.log("Текст помилки: " + xhr.responseText);
    }
  });
</script>
<h1 class="container">
  Адмін панель
</h1>
<section class="container admin">
  <div>
    <h2>Додати кота</h2>
    <form class="admin__add-cat-from" action="">
      <label for="">
        Назва Кота
        <input type="text" name="name" placeholder="Кіт Василь">
      </label>
      <label for="">
        Лінк на картинку
        <input type="text" name="img_src" placeholder="https://img-cat_link">
      </label>
      <label for="">
        Додати картинку
        <input type="file" name="file">
      </label>
      <label for="">
        Опис
        <input type="text" name="description" placeholder="Опис">
      </label>

      <label for="">
        Група
        <select class="group_select" name="group_id" value="0">
          <option value='0' disabled selected>Оберіть групу</option>
        </select>
      </label>
      <button type="button" class="btn main-green add-cat-btn">Додати запис</button>
    </form>
  </div>

  <div>
    <h2>Змінити кота</h2>
    <form class="admin__update-cat" action="">
      <label for="">
        Усі коти
        <select class="cat_select" name="id" value="0">
          <option value='0' disabled selected>Оберіть кота</option>
        </select>
      </label>
      <label for="">
        Нова назва Кота
        <input type="text" name="name" placeholder="Кіт Василь">
      </label>
      <label for="">
        Новий лінк на картинку
        <input type="text" name="img_src" placeholder="https://img-cat_link">
      </label>
      <label for="">
        Новий опис
        <input type="text" name="description" placeholder="Опис">
      </label>


      <button type="button" class="btn main-yellow update-btn">Змінити запис</button>
    </form>
  </div>

  <div>
    <h2>Змінити кота</h2>
    <form class="admin__update-cat" action="">
      <label for="">
        Видалити кота
        <button type="button" class="btn alert-btn delete-cat-btn">Видалити</button>

      </label>
    </form>
  </div>
</section>