<?php if(!isset($_SESSION)) : session_start(); endif; ?>
<?php require_once '../code/php/helpers.php'; ?>
<?php require_once '../code/php/classes.php'; ?>
<?php $db = new Db();  ?>
<?php $pdo = $db->connect(); ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/logo.jpg">
  <link rel="icon" type="image/png" href="../assets/img/logo.jpg">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Gestión de usuarios</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../code/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../code/css/demo.css" rel="stylesheet" />
</head>

<body class="">

  <div class="wrapper ">
    <div class="sidebar" data-color="<?= getColor(); ?>" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">
        Monitor vital
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="./index.php">
              <i class="material-icons">dashboard</i>
              <p>Página principal</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./user.php">
              <i class="material-icons">person</i>
              <p>Mi perfil</p>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./users.php">
              <i class="material-icons">group</i>
              <p>Gestión de usuarios</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./ports.php">
              <i class="material-icons">settings_input_component</i>
              <p>Puertos</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./netdevices.php">
              <i class="material-icons">router</i>
              <p>Dispositivos de red</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./medicaleq.php">
              <i class="material-icons">local_hospital</i>
              <p>Equipamiento médico</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./telegram.php">
              <i class="material-icons">send</i>
              <p>Telegram</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./security.php">
              <i class="material-icons">security</i>
              <p>Panel de seguridad</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Gestión de usuarios</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index.php">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <?php if (count(geterrs())>1): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">notifications</i>
                        <span class="notification"><?= count(geterrs()); ?></span>
                    <p class="d-lg-none d-md-block">
                      Some Actions
                    </p>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                       <?php foreach(geterrs() as $warn): ?>
                            <a class="dropdown-item" href="#"><?= $warn['MENSAJE'] ?></a>
                       <?php endforeach; ?>
                  </div>
                </li>
              <?php endif; ?>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="user.php"><?= $_SESSION['user']['NAME'] ?></a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" id="logoff" href="../code/php/logoff.php">Cerrar sesión</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Usuarios</h4>
                  <p class="card-category">Datos generales</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>ID</th>
                        <th>ID de telegram</th>
                        <th>Correo electrónico</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Rol</th>
                      </thead>
                      <tbody>
                        <?php $noAdmin = true; ?>
                        <?php if($_SESSION['user']['ROLE'] == 'admin'): $noAdmin = false; endif; ?>
                        <?php foreach ($db->getUsers($pdo,$noAdmin) as $user): ?>
                            <tr>
                              <td><?= $user['ID'] ?></td>
                              <td><?= $user['TID'] ?></td>
                              <td><?= $user['EMAIL'] ?></td>
                              <td><?= $user['NAME'] ?></td>
                              <td><?= $user['SURNAME'] ?></td>
                              <td><?= $user['ROLE'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Usuarios</h4>
                  <p class="card-category">Crear un usuario</p>
                  <p id="resp_msg"></p>
                  <img src="../assets/gif/loading.gif" height="50px" width="50px" id="loading"></img>
                </div>
                <div class="card-body">
                  <form id="new_user" action="">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Nombre de usuario</label>
                          <input type="text" class="form-control" id="username">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Correo electrónico</label>
                          <input type="email" class="form-control" id="email">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Rol</label>
                          <input type="text" class="form-control" id="role" list="roles">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Nombre</label>
                          <input type="text" class="form-control" id="name">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Apellidos</label>
                          <input type="text" class="form-control" id="surname">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Usuario de telegram</label>
                          <input type="text" class="form-control" id="tusername">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contraseña</label>
                          <input type="password" class="form-control" id="password">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Confirmar contraseña</label>
                          <input type="password" class="form-control" id="passwordc">
                        </div>
                      </div>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Crear usuario</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Usuarios</h4>
                  <p class="card-category">Eliminar un usuario</p>
                  <p id="resp_msg_del"></p>
                  <img src="../assets/gif/loading.gif" height="50px" width="50px" id="loading_del"></img>
                </div>
                <div class="card-body">
                  <form id="del_user" action="">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Nombre de usuario</label>
                          <input type="text" class="form-control" id="dusername">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Confirmar nombre de usuario</label>
                          <input type="text" class="form-control" id="dusernamec">
                        </div>
                      </div>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Borrar usuario</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Usuarios</h4>
                  <p class="card-category">Restablecer contraseña</p>
                  <p id="resp_msg_2"></p>
                  <img src="../assets/gif/loading.gif" height="50px" width="50px" id="loading_2"></img>
                </div>
                <div class="card-body">
                  <form id="reset_passwd" action="">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Nombre de usuario</label>
                          <input type="text" class="form-control" id="reset_username">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Contraseña</label>
                          <input type="password" class="form-control" id="npassword">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Confirmar contraseña</label>
                          <input type="password" class="form-control" id="npasswordc">
                        </div>
                      </div>
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Restablecer contraseña</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="https://creative-tim.com/presentation">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
              <li>
                <a href="https://www.creative-tim.com/license">
                  Licenses
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
          </div>
        </div>
      </footer>
    </div>
  </div>

  <datalist id="roles">
      <?php if ($_SESSION['user']['ROLE'] == 'admin'): ?>
           <option value="admin">
      <?php endif; ?>
      <option value="tech">
      <option value="medical">
  </datalist>
  <!--   Core JS Files   -->
  <script src="../code/js/core/jquery.min.js"></script>
  <script src="../code/js/core/popper.min.js"></script>
  <script src="../code/js/core/bootstrap-material-design.min.js"></script>
  <script src="../code/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../code/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../code/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../code/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../code/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../code/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../code/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../code/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../code/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../code/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../code/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../code/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../code/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../code/js/plugins/arrive.min.js"></script>
  <!--  Forms   -->
  <script src="../code/js/view_users.js"></script>
  <!-- Chartist JS -->
  <script src="../code/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../code/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../code/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="../code/js/demo.js"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
</body>

</html>
