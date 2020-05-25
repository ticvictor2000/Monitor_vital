var forgot_btn = document.getElementById('forgot_btn');
var forgot_form = document.getElementsByClassName('forgot-form')[0];
var login_form = document.getElementsByClassName('login-form')[0];
var access_btn = document.getElementById('access_btn');
var body = document.getElementsByTagName('body')[0];


var login_form_h = document.getElementById('login_form');
var forgot_form_h = document.getElementById('forgot_form');
var send_login = document.getElementById('send_login');
var send_forgot = document.getElementById('send_forgot');
var response = document.getElementById('response');

forgot_form.style.display = 'none';

forgot_btn.onclick = function() {
     login_form.style.display = 'none';
     forgot_form.style.display = 'block';
}

access_btn.onclick = function() {
     login_form.style.display = 'block';
     forgot_form.style.display = 'none';
}

login_form_h.onsubmit = function(e) {
     var username = document.getElementById('user').value;
     var pass = document.getElementById('pass').value;

     $.ajax({
          type: 'POST',
          url: 'code/php/login.php',
          data: {
               username: username,
               pass: pass
          },
          success: function(data) {
               if (data == true) {
                    window.location.href = 'view';
               }
               if (data != true) {
                    response.innerHTML = '<span class="error">'+data+'</span>';
               }
          }
     });

     e.preventDefault();
}
