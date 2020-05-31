var form = document.getElementById('user_update');
var resp = document.getElementById('resp_msg');
var loading = document.getElementById('loading');

loading.style.display = 'none';

form.onsubmit = function(e) {
     var username = document.getElementById('username');
     var email = document.getElementById('email');
     var name = document.getElementById('name');
     var surname = document.getElementById('surname');
     var npassword = document.getElementById('npassword');
     var npasswordc = document.getElementById('npasswordc');

     if (npassword.value.trim() == '' && npasswordc.value.trim() == '') {
          // Change only info
          $.ajax({
               type: 'POST',
               url: '../code/php/update_user.php',
               data: {
                    act: 'upd_userdata',
                    user: username.value,
                    email: email.value,
                    name: name.value,
                    surname: surname.value
               },
               beforeSend: function() {
                    loading.style.display = 'block';
               },
               success: function(data) {
                    loading.style.display = 'none';

                    if (typeof data != 'string') {
                         resp.className = 'error';
                         resp.innerHTML = 'Error interno al actualizar el usuario';
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
     } else {
          // Change all fields
          $.ajax({
               type: 'POST',
               url: '../code/php/update_user.php',
               data: {
                    act: 'upd_userdata_all',
                    user: username.value,
                    email: email.value,
                    name: name.value,
                    surname: surname.value,
                    npassword: npassword.value,
                    npasswordc: npasswordc.value
               },
               beforeSend: function() {
                    loading.style.display = 'block';
               },
               success: function(data) {
                    loading.style.display = 'none';

                    if (typeof data != 'string') {
                         resp.className = 'error';
                         resp.innerHTML = 'Error interno al actualizar el usuario';
                    }

                    if (data.substr(0,1) == '*') {
                         resp.className = 'error';
                         resp.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) == '-') {
                         resp.className = 'error';
                         resp.innerHTML = data.substr(1,(data.length - 1));
                    }

                    if (data.substr(0,1) != '*') {
                         resp.className = 'ok';
                         resp.innerHTML = data;
                    }
               }
          });
     }

     e.preventDefault();
}
