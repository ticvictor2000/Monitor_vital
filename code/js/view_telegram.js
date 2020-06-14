var resp_change_gen_cnf = document.getElementById('resp_change_gen_cnf');
var loading_change_gen_cnf = document.getElementById('loading_change_gen_cnf');
var change_gen_cnf = document.getElementById('change_gen_cnf');

var resp_change_use_cnf = document.getElementById('resp_change_use_cnf');
var loading_change_user_cnf = document.getElementById('loading_change_user_cnf');
var change_use_cnf = document.getElementById('change_use_cnf');

var resp_change_rte_cnf = document.getElementById('resp_change_rte_cnf');
var loading_change_rte_cnf = document.getElementById('loading_change_rte_cnf');
var change_rte_cnf = document.getElementById('change_rte_cnf');

loading_change_gen_cnf.style.display = 'none';
loading_change_user_cnf.style.display = 'none';
loading_change_rte_cnf.style.display = 'none';

change_gen_cnf.onsubmit = function(e) {
     var botToken = document.getElementById('botToken');
     var admins = document.getElementById('admins');
     var groupId = document.getElementById('groupId');

     var sure = confirm("¿Está seguro de la operación?\nEl bot podría quedarse inutilizable");
     if (sure == true) {
          $.ajax({
               type: 'POST',
               url: '../code/php/telegram.php',
               data: {
                    act: 'change_gen_cnf',
                    botToken: botToken.value,
                    admins: admins.value,
                    groupId: groupId.value
               },
               beforeSend: function() {
                    loading_change_gen_cnf.style.display = 'block';
               },
               success: function(data){
                    loading_change_gen_cnf.style.display = 'none';

                    if (typeof data != 'string') {
                         resp_change_gen_cnf.className = 'error';
                         resp_change_gen_cnf.innerHTML = 'Error interno al actualizar las preferencias generales de Telegram';
                    }

                    if (data.substr(0,1) == '*') {
                         resp_change_gen_cnf.className = 'error';
                         resp_change_gen_cnf.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) == '-') {
                         resp_change_gen_cnf.className = 'warn';
                         resp_change_gen_cnf.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                         resp_change_gen_cnf.className = 'ok';
                         resp_change_gen_cnf.innerHTML = data;
                    }
               }
          });
     } else {
          alert('Operación cancelada');
     }

     e.preventDefault();
}

change_use_cnf.onsubmit = function(e) {
     var mode = document.getElementById('mode');
     var users_list = document.getElementById('users_list');

     $.ajax({
          type: 'POST',
          url: '../code/php/telegram.php',
          data: {
               act: 'change_use_cnf',
               mode: mode.value,
               users_list: users_list.value
          },
          beforeSend: function() {
               loading_change_user_cnf.style.display = 'block';
          },
          success: function(data){
               loading_change_user_cnf.style.display = 'none';

               if (typeof data != 'string') {
                    resp_change_use_cnf.className = 'error';
                    resp_change_use_cnf.innerHTML = 'Error interno al actualizar las preferencias generales de Telegram';
               }

               if (data.substr(0,1) == '*') {
                    resp_change_use_cnf.className = 'error';
                    resp_change_use_cnf.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    resp_change_use_cnf.className = 'warn';
                    resp_change_use_cnf.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    resp_change_use_cnf.className = 'ok';
                    resp_change_use_cnf.innerHTML = data;
               }
          }
     });

     e.preventDefault();
}

change_rte_cnf.onsubmit = function(e) {
     var docs_path = document.getElementById('docs_path');
     var imgs_path = document.getElementById('imgs_path');

     $.ajax({
          type: 'POST',
          url: '../code/php/telegram.php',
          data: {
               act: 'change_rte_cnf',
               docs_path: docs_path.value,
               imgs_path: imgs_path.value
          },
          beforeSend: function() {
               loading_change_rte_cnf.style.display = 'block';
          },
          success: function(data){
               loading_change_rte_cnf.style.display = 'none';

               if (typeof data != 'string') {
                    resp_change_rte_cnf.className = 'error';
                    resp_change_rte_cnf.innerHTML = 'Error interno al actualizar las preferencias generales de Telegram';
               }

               if (data.substr(0,1) == '*') {
                    resp_change_rte_cnf.className = 'error';
                    resp_change_rte_cnf.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    resp_change_rte_cnf.className = 'warn';
                    resp_change_rte_cnf.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    resp_change_rte_cnf.className = 'ok';
                    resp_change_rte_cnf.innerHTML = data;
               }
          }
     });

     e.preventDefault();
}
