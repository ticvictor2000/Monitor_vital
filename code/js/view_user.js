var form = document.getElementById('user_update');
var errors = document.getElementById('errors');

form.onsubmit = function(e) {
     var username = document.getElementById('username');
     var email = document.getElementById('email');
     var name = document.getElementById('name');
     var surname = document.getElementById('surname');
     var npassword = document.getElementById('npassword');
     var npasswordc = document.getElementById('npasswordc');

     if (npassword.value.trim() == '' && npasswordc.value.trim() == '') {

     } else {
          errors.innerHTML = ''
     }

     $.ajax({
          type: 'POST',
          url: '../code/php/update_user.php',
          data: {
               act: 'upd_userdata'
          },
          beforeSend: function() {
               console.log('Loading user update');
          },
          success: function(data){

          }
     });

     e.preventDefault();
}
