var form_new = document.getElementById('form_new');
var resp_new = document.getElementById('resp_new');
var loading_new = document.getElementById('loading_new');

var form_del = document.getElementById('form_del');
var resp_del = document.getElementById('resp_del');
var loading_del = document.getElementById('loading_del');

loading_new.style.display = 'none';
loading_del.style.display = 'none';

form_new.onsubmit = function(e) {
     var type = document.getElementById('type');
     var ip = document.getElementById('ip');
     var telnet = document.getElementById('telnet');
     var ssh = document.getElementById('ssh');
     var brand = document.getElementById('brand');
     var model = document.getElementById('model');
     var pass = document.getElementById('pass');

     $.ajax({
          type: 'POST',
          url: '../code/php/register_netdevice.php',
          data: {
               type: type.value,
               ip: ip.value,
               telnet: telnet.value,
               ssh: ssh.value,
               brand: brand.value,
               model: model.value,
               pass: pass.value
          },
          beforeSend: function() {
               loading_new.style.display = 'block';
          },
          success: function(data) {
               loading_new.style.display = 'none';

               if (typeof data != 'string') {
                    resp_new.className = 'error';
                    resp_new.innerHTML = 'Error interno al crear el dispositivo de red';
               }

               if (data.substr(0,1) == '*') {
                    resp_new.className = 'error';
                    resp_new.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    resp_new.className = 'warn';
                    resp_new.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    resp_new.className = 'ok';
                    resp_new.innerHTML = data;
               }
          }
     });

     e.preventDefault();
}

form_del.onsubmit = function(e) {
     var macnd = document.getElementById('macnd');
     var sure = confirm('¿Está seguro que quiere eliminar el dispositivo de red '+macnd.value+"? \nEsta acción es irreversible");

     if (sure == true) {
          $.ajax({
               type: 'POST',
               url: '../code/php/delete_netdevice.php',
               data: {
                    macnd: macnd.value
               },
               beforeSend: function() {
                    loading_del.style.display = 'block';
               },
               success: function(data) {
                    loading_del.style.display = 'none';

                    if (typeof data != 'string') {
                         resp_del.className = 'error';
                         resp_del.innerHTML = 'Error interno al eliminar el dispositivo de red';
                    }

                    if (data.substr(0,1) == '*') {
                         resp_del.className = 'error';
                         resp_del.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) == '-') {
                         resp_del.className = 'warn';
                         resp_del.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                         resp_del.className = 'ok';
                         resp_del.innerHTML = data;
                    }
               }
          });
     } else {
          alert('Operación cancelada');
     }


     e.preventDefault();
}
