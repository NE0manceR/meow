<script>
  $.ajax({
    url: "/api/get_products.php", // Замініть це на шлях до вашого серверного файлу
    method: "POST", // Використовуйте "POST" якщо потрібно
    data: {
      id: <?= $_SESSION['user_id']?>
    },
    dataType: "json",
    success: function(response) {
      console.log(response);
      // Опрацьовуйте отримані дані (response) тут
      response.forEach(element => {
        $('.product-container').append(`
          <div class="card">
            <img src="${window.location.href + element.img_src}" alt="${element.name}">
            <div class="card__description">
              <h3>${element.name}</h3>
              <span>${element.description}</span>
            </div>
          </div>
        `)
      });
    },
    error: function(xhr, status, error) {
      console.log("Помилка запиту: " + status);
    }
  });
</script>