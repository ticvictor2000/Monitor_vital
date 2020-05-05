var reg_btn = document.getElementById('register');

reg_btn.onclick = function(){
     var name = document.getElementById('name_field').value;
     var user = document.getElementById('user_field').value;
     var pass = document.getElementById('pass_field').value;
     var role = document.getElementById('role_field').value;

     var result = document.getElementById('result');

     $.ajax({
          type: 'POST',
          url: '../code/php/register_user.php',
          data: {
               name: name,
               user: user,
               pass: pass,
               role: role
          },
          success: function(data){
               result.innerHTML = data;
          }
     });
}
