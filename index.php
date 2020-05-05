<!DOCTYPE html>
<html lang="es" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Monitor vital</title>
          <link rel="stylesheet" href="code/css/index.css" />
          <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Exo+2:700|Source+Code+Pro:400,600,700&amp;display=swap" />
     </head>
     <body>
          <?php if(!isset($_SESSION)) : session_start(); endif; ?>
          <?php if(isset($_SESSION['user'])) : header('Location: view'); endif; ?>
          <div id="login" class="auth">
               <div class="top_text">
                      <span class="text_info">Inicia sesión</span>
                      <br />
                      <?php if(isset($_SESSION['errors']['login'])): ?>
                           <span id="login_error" class="error"><?= $_SESSION['errors']['login'] ?></span>
                      <?php endif; ?>
               </div>
               <div class="vital_sign_monitor">
                    <div class="monitor_line">
                         <div class="col-9">
                              <div class="EKG"></div>
                         </div>
                         <div class="col-3">
                              <div class="number-1">136<span>&#9829;</span></div>
                         </div>
                         <div class="datetime" id="datetime">DD/MM/YYYY HH:II</div>
                    </div>
               </div>
               <div class="form">
                      <form id="form_login" action="code/php/login.php" method="post">
                             <input type="text" placeholder="Nombre de usuario" name="user"></input><br />
                             <input type="password" placeholder="Contraseña" name="pass"></input><br />
                             <button type="submit">Iniciar sesión</button>
                      </form>
               </div>
          </div>
          <script type="text/javascript" src="code/js/index_time.js"></script>
     </body>
</html>
