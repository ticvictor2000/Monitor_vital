var forgot_btn = document.getElementById('forgot_btn');
var forgot_form = document.getElementsByClassName('forgot-form')[0];
var login_form = document.getElementsByClassName('login-form')[0];
var access_btn = document.getElementById('access_btn');
var body = document.getElementsByTagName('body')[0];

forgot_form.style.display = 'none';

forgot_btn.onclick = function() {
     login_form.style.display = 'none';
     forgot_form.style.display = 'block';
}

access_btn.onclick = function() {
     login_form.style.display = 'block';
     forgot_form.style.display = 'none';
}
