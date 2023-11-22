<div class="container pt-4">
  <h1>Коти</h1>
  <div class="product-container">

  </div>
</div>

<script>
  $(document).ready(function() {
    get_products(<?= $_SESSION['user_id'] ?>)
  });
</script>