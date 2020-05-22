<!DOCTYPE html>
<html lang="es" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Monitor vital</title>
          <link rel="stylesheet" href="code/css/index.css" />
     </head>
     <body>
          <?php if(!isset($_SESSION)) : session_start(); endif; ?>
          <?php if(isset($_SESSION['user'])) : header('Location: view'); endif; ?>

          <form class="c-form login-form">
               <fieldset class="c-form__fieldset">
                    <legend class="c-form__title">
                         <span>Identifíquese</span>
                         <br />
                         <span id="datetime"></span>
                    </legend>

                    <div class="u-spacer--sm"></div>

                    <label class="c-formGroup" for="user">
                         <input class="c-formGroup__input u-ripple" type="text" id="user" placeholder="Nombre de usuario">
                         <span class="c-formGroup__title">Usuario</span>
                         <i class="c-formGroup__icon"><svg><use xlink:href="#icon-email" /></svg></i>
                    </label>

                    <div class="u-spacer"></div>

                    <label class="c-formGroup" for="pass">
                         <input class="c-formGroup__input u-ripple" type="password" id="pass" placeholder="Contraseña">
                         <span class="c-formGroup__title">Contraseña</span>
                         <i class="c-formGroup__icon"><svg><use xlink:href="#icon-padlock" /></svg></i>
                    </label>

                    <div class="u-spacer--sm"></div>

                    <a class="c-form__link" id="forgot_btn" href="#">Solicitar acceso</a>
                    <button type="button" class="c-form__button u-ripple">Acceder</button>
               </fieldset>
          </form>

          <form class="c-form forgot-form">
               <fieldset class="c-form__fieldset">
                    <legend class="c-form__title">
                         <span>Solicitud de acceso</span>
                         <br />
                         <span>Si ha perdido su contrseña puede recuperarla introduciendo los siguientes datos</span>
                    </legend>

                    <div class="u-spacer--sm"></div>

                    <label class="c-formGroup" for="name">
                         <input class="c-formGroup__input u-ripple" type="text" id="name" placeholder="Nombre completo">
                         <span class="c-formGroup__title">Nombre completo</span>
                         <i class="c-formGroup__icon"><svg><use xlink:href="#icon-email" /></svg></i>
                    </label>

                    <div class="u-spacer"></div>

                    <label class="c-formGroup" for="email">
                         <input class="c-formGroup__input u-ripple" type="email" id="email" placeholder="Correo electrónico">
                         <span class="c-formGroup__title">Correo electrónico</span>
                         <i class="c-formGroup__icon"><svg><use xlink:href="#icon-email" /></svg></i>
                    </label>

                    <div class="u-spacer--sm"></div>

                    <a class="c-form__link" id="access_btn" href="#">Acceder</a>
                    <button type="button" class="c-form__button u-ripple">Enviar solicitud</button>
               </fieldset>
          </form>

          <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
               <symbol id="icon-email" viewBox="0 0 16 16">
                    <title>Email icon</title>
                    <path d="M8,10c-0.266,0-0.5-0.094-1-0.336L0,6v7c0,0.55,0.45,1,1,1h14c0.55,0,1-0.45,1-1V6L9,9.664C8.5,9.906,8.266,10,8,10z M15,2  H1C0.45,2,0,2.45,0,3v0.758l8,4.205l8-4.205V3C16,2.45,15.55,2,15,2z" />
               </symbol>

               <symbol id="icon-padlock" viewBox="0 0 402 402">
                    <title>Padlock icon</title>
                    <path d="M357 191c-5-6-11-8-19-8h-9v-55c0-35-13-65-38-90S236 0 201 0s-65 13-90 38-38 55-38 90v55h-9c-8 0-14 2-19 8-6 5-8 12-8 19v165c0 7 2 14 8 19 5 5 11 8 19 8h274c8 0 14-3 19-8 6-5 8-12 8-19V210c0-7-2-14-8-19zm-83-8H128v-55c0-20 7-37 21-52 15-14 32-21 52-21s37 7 52 21c14 15 21 32 21 52v55z" />
               </symbol>
          </svg>
          <script type="text/javascript" src="code/js/index_time.js"></script>
          <script type="text/javascript" src="code/js/index.js"></script>
     </body>

</html>
