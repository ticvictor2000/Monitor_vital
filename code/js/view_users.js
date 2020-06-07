var form = document.getElementById('new_user');
var resp = document.getElementById('resp_msg');
var loading = document.getElementById('loading');

var form2 = document.getElementById('reset_passwd');
var resp2 = document.getElementById('resp_msg_2');
var loading2 = document.getElementById('loading_2');

var form_del = document.getElementById('del_user');
var resp_msg_del = document.getElementById('resp_msg_del');
var loading_del = document.getElementById('loading_del');

var form_edit = document.getElementById('edit_user');
var resp_msg_edit = document.getElementById('resp_msg_edit');
var loading_edit = document.getElementById('loading_edit');


loading.style.display = 'none';
loading2.style.display = 'none';
loading_del.style.display = 'none';
loading_edit.style.display = 'none'

form.onsubmit = function(e) {
     var username = document.getElementById('username');
     var tusername = document.getElementById('tusername');
     var email = document.getElementById('email');
     var role = document.getElementById('role');
     var name = document.getElementById('name');
     var surname = document.getElementById('surname');
     var password = document.getElementById('password');
     var passwordc = document.getElementById('passwordc');

     $.ajax({
          type: 'POST',
          url: '../code/php/register_user.php',
          data: {
               user: username.value,
               tuser: tusername.value,
               email: email.value,
               role: role.value,
               name: name.value,
               surname: surname.value,
               password: password.value,
               passwordc: passwordc.value
          },
          beforeSend: function() {
               loading.style.display = 'block';
          },
          success: function(data) {
               loading.style.display = 'none';

               if (typeof data != 'string') {
                    resp.className = 'error';
                    resp.innerHTML = 'Error interno al crear el usuario';
               }

               if (data.substr(0,1) == '*') {
                    resp.className = 'error';
                    resp.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    resp.className = 'warn';
                    resp.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    resp.className = 'ok';
                    resp.innerHTML = data;
               }
          }
     });

     e.preventDefault();
}

form2.onsubmit = function(e) {
     var username = document.getElementById('reset_username');
     var npassword = document.getElementById('npassword');
     var npasswordc = document.getElementById('npasswordc');

     $.ajax({
          type: 'POST',
          url: '../code/php/reset_passwd.php',
          data: {
               user: username.value,
               npassword: npassword.value,
               npasswordc: npasswordc.value
          },
          beforeSend: function() {
               loading2.style.display = 'block';
          },
          success: function(data) {
               loading2.style.display = 'none';

               if (typeof data != 'string') {
                    resp2.className = 'error';
                    resp2.innerHTML = 'Error interno al crear el usuario';
               }

               if (data.substr(0,1) == '*') {
                    resp2.className = 'error';
                    resp2.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    resp2.className = 'warn';
                    resp2.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    resp2.className = 'ok';
                    resp2.innerHTML = data;
               }
          }
     });

     e.preventDefault();
}

form_del.onsubmit = function(e) {
     var dusername = document.getElementById('dusername');
     var dusernamec = document.getElementById('dusernamec');

     var sure = confirm('¿Está seguro que quiere eliminar al usuario '+dusername.value+"? \nEsta acción es irreversible");

     if (sure == true) {
          $.ajax({
               type: 'POST',
               url: '../code/php/del_user.php',
               data: {
                    dusername: dusername.value,
                    dusernamec: dusernamec.value
               },
               beforeSend: function() {
                    loading_del.style.display = 'block';
               },
               success: function(data) {
                    loading_del.style.display = 'none';

                    if (typeof data != 'string') {
                         resp_msg_del.className = 'error';
                         resp_msg_del.innerHTML = 'Error interno al crear el usuario';
                    }

                    if (data.substr(0,1) == '*') {
                         resp_msg_del.className = 'error';
                         resp_msg_del.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) == '-') {
                         resp_msg_del.className = 'warn';
                         resp_msg_del.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                         resp_msg_del.className = 'ok';
                         resp_msg_del.innerHTML = data;
                    }
               }
          });
          e.preventDefault();
     } else {
          alert('Operación cancelada');
          e.preventDefault();
     }


}

form_edit.onsubmit = function(e) {
     
}
