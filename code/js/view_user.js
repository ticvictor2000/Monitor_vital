var form = document.getElementById('user_update');
var resp = document.getElementById('resp_msg');

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
                    act: 'upd_userdata'
               },
               beforeSend: function() {
                    console.log('Loading user update');
               },
               success: function(data){

               }
          });
     } else {
          // Change all fields
          $.ajax({
               type: 'POST',
               url: '../code/php/update_user.php',
               data: {
                    act: 'upd_userdata_all'
               },
               beforeSend: function() {
                    console.log('Loading user update');
               },
               success: function(data){
                    
               }
          });
     }

     e.preventDefault();
}
