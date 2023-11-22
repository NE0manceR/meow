// $('.header__search input').on('focus blur', function () {
//   $('.header__search').toggleClass('active');
// })

// $('.toggle-login-modal').on('click', function () {
//   show_logination_modal();
// })

// function show_logination_modal() {
//   $('.bcg').fadeToggle();
//   $('.logination-modal').toggle();
// }
$('.bcg, .toggle-login-modal').on('click', function () {
  $('.regestration-modal, .logination-modal').hide();
  $('.bcg').fadeOut();
})

$('.header-login-btn').on('click', function () {
  $('.logination-modal').toggle();
  $('.bcg').fadeToggle();
})

$('.header-regestration-btn').on('click', function () {
  $('.regestration-modal').toggle();
  $('.bcg').fadeToggle();
})

console.log(window.location);
function get_products(id) {
  $.ajax({
    url: window.location.origin + "/views/products.php",
    method: "POST",
    data: {
      user_id: id
    },
    dataType: "json",
    success: function (response) {
      // Опрацьовуйте отримані дані (response) тут
      $('.product-container').html('');
      response.forEach(element => {
        let src = element.img_src.includes('http') ? element.img_src : window.location.href + element.img_src;
        $('.product-container').append(`
            <div class="card">
              <img src="${src}" alt="${element.name}">
              <div class="card__description">
                <h3>${element.name}</h3>
                <span>${element.description}</span>
              </div>
            </div>
          `)
      });
    },
    error: function (xhr, status, error) {
      console.log("Помилка запиту: " + status);
      console.log("Текст помилки: " + xhr.responseText);
    }
  });
}

$('.admin__update-cat select').on('change', function () {
  let current_cat = all_cats.find(({
    id
  }) => id == $(this).val());

  $('.admin__update-cat input[name="name"]').val(current_cat.name);
  $('.admin__update-cat input[name="description"]').val(current_cat.description);
  $('.admin__update-cat input[name="img_src"]').val(current_cat.img_src);
})

$('.delete-cat-btn').on('click', function () {
  if ($('.admin__update-cat select').val() !== null) {
    $.ajax({
      url: window.location.origin + "/views/products.php",
      method: "POST",
      data: {
        delete: $('.admin__update-cat select').val()
      },
      dataType: "json",
      success: function (response) {
        console.log(response)

        alert(response.status.text);

        $('.cat_select option').remove();
        $('.cat_select').append(`
          <option value='0' disabled selected>Оберіть кота</option>
        `);

        response.products.forEach(element => {
          $('.cat_select').append(`
          <option value='${element.id}'>${element.name}</option>
      `)
        });

        $('.admin__update-cat input').val('');
      },
      error: function (xhr, status, error) {
        console.log("Помилка запиту: " + status);
        console.log("Текст помилки: " + xhr.responseText);
      }
    });
  }
})
$('.add-cat-btn').on('click', add_cat);

function add_cat() {
  let form_data = new FormData();

  $('.admin__add-cat-from input, .admin__add-cat-from textarea, .admin__add-cat-from select').each(function () {
    let name = $(this).attr('name');
    if ($(this).is('input[type="file"]')) {
      // Перевірка, чи існує файл перед додаванням до FormData
      console.log($(this)[0].files.length > 0, 'qw');
      if ($(this)[0].files.length > 0) {
        form_data.append(name, $(this)[0].files[0]);
      }
    } else {
      let value = $(this).val();
      form_data.append(name, value);
    }
  });
  for (const pair of form_data.entries()) {
    console.log(pair[0], pair[1]);
  }
  if (
    form_data.get('name').length > 2 &&
    (form_data.has('img_src') && form_data.get('img_src').length > 3 || form_data.has('file')) && form_data.has('group_id') && form_data.get('group_id') !== null) {


    $.ajax({
      url: window.location.origin + "/views/products.php",
      method: "POST",
      data: form_data,
      dataType: "json",
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status == 'error') {
          alert('Таки котан вже є');
        } else {
          alert(response.text);
        }
      },
      error: function (xhr, status, error) {
        console.log("Помилка запиту: " + status);
        alert('Такий котан вже є');
      }
    });
  } else {
    alert(' Я сильно не валідував але щось напевно пусте')
  }

}

$('.update-btn').on('click', update_cat);

function update_cat() {
  let form_data = {};
  $('.admin__update-cat').serializeArray().forEach(({
    name,
    value
  }) => {
    form_data[name] = value;
  })

  form_data['update'] = 'update';
  if (form_data['name'].length > 2 && form_data['img_src'].length > 3 && form_data.hasOwnProperty('id')) {
    $.ajax({
      url: window.location.origin + "/views/products.php",
      method: "POST",
      data: form_data,
      dataType: "json",
      success: function (response) {
        alert(response.status.text);

        $('.cat_select option').remove();
        $('.cat_select').append(`
          <option value='0' disabled selected>Оберіть кота</option>
        `);

        response.products.forEach(element => {
          $('.cat_select').append(`
          <option value='${element.id}'>${element.name}</option>
      `)
        });

        $('.admin__update-cat input').val('');
      },
      error: function (xhr, status, error) {
        console.log("Помилка запиту: " + status);
        console.log("Текст помилки: " + xhr.responseText);
      }
    });
  }
}

function login_func(user_status) {
  let status = user_status;
  if (status) {

    if ($('.login-form input[name="email"]').val().length > 3 && $('.login-form input[name="password"]').val() != '') {
      $('.login-form input').css('border', '1px solid #ced4da');
      $.ajax({
        url: window.location.origin + '/views/login.php',
        method: "POST",
        dataType: "json",
        data: {
          email: $('.login-form input[name="email"]').val(),
          password: $('.login-form input[name="password"]').val()
        },
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response.error) {
            $('.login-modal-info-text:nth-child(2)').html(response.text).css('color', 'red');
          } else {
            $('.login-modal-info-text:nth-child(2)').html(response.text).css('color', 'green');

            setTimeout(() => {
              $('.header-login-btn').hide();
              $('.header-regestration-btn').hide();
              $('.logout-btn').fadeIn();
              console.log($('.admin-btn'));
              $('.admin-btn').fadeIn();
              $('.logination-modal').toggle();
              $('.bcg').fadeToggle();
              get_products(response.id);
              $('.product-container').html('');
              response.all_cats.forEach(element => {
                $('.product-container').append(`
                  <div class="card">
                    <img src="${element.img_src}" alt="${element.name}">
                    <div class="card__description">
                      <h3>${element.name}</h3>
                      <span>${element.description}</span>
                    </div>
                  </div>
                `)
              });
            }, 1300)

          }
        },
        error: function (response) { }
      });
    } else {
      $('.login-form input').css('border', '1px solid red');
    }
  }
}

function logout(id) {
  $.ajax({
    url: window.location.origin + "/views/logout.php", // Замініть це на шлях до вашого серверного файлу
    method: "POST", // Використовуйте "POST" якщо потрібно
    dataType: "json",
    data: {
      id,
      logout: 1
    },
    success: function (response) {
      $('.logout-btn').hide();
      $('.admin-btn').hide();
      $('.header-login-btn, .header-regestration-btn').fadeIn();
      get_products(-1);
    },
    error: function (response) { }
  });
}
function registration(stat) {
  console.log(stat);
  let status = stat;
  if (status) {
    if (
      $('.registration-form input[name="email"]').val().length > 3 &&
      $('.registration-form input[name="password"]').val() != '' &&
      $('.registration-form input[name="name"]').val() != ''
    ) {
      let test = {
        email: $('.registration-form input[name="email"]').val(),
        password: $('.registration-form input[name="password"]').val(),
        name: $('.registration-form input[name="name"]').val(),
      }

      $.ajax({
        url: window.location.origin + '/views/registration.php',
        method: "POST",
        dataType: "json",
        data: {
          email: $('.registration-form input[name="email"]').val(),
          password: $('.registration-form input[name="password"]').val(),
          name: $('.registration-form input[name="name"]').val(),
        },
        dataType: "json",
        success: function (response) {
          console.log(response);
          if (response.status == 'error') {
            $('.registration-modal-info-text').html(response.text).css('color', 'red');
          } else {
            $('.registration-modal-info-text').html(response.text).css('color', 'green');
            $('.registration-form input').css('border', '1px solid green');

            setTimeout(() => {
              $('.regestration-modal').toggle();
              $('.bcg').fadeToggle();
              $('.header-login-btn').hide();
              $('.header-regestration-btn').hide();
              $('.logout-btn').fadeIn();
              $('.admin-btn').fadeIn();
              get_products(1);
            }, 1200)
          }
        },
        error: function (response) {
          $('.registration-modal-info-text').html(response.text).css('color', 'red');
          $('.header-login-btn, .header-regestration-btn').fadeOut();

        }
      });
    } else {
      $('.registration-form input').css('border', '1px solid red');
    }
  }
}

$('.header__search input').keypress(search_func)

$('.header__search button').on('click', search_func);

function search_func() {
  if (event.which === 13 || event.type == 'click') {
    alert("Поки що не мяу");
  }
}


