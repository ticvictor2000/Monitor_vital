var type = document.getElementById('type');
var netdevices = document.getElementById('netdevices');
var ports = document.getElementById('ports');
var error = document.getElementById('error');

netdevices.style.display = 'none';
ports.style.display = 'none';

type.onchange = function() {
     if (type.value != 'null') {
          // Request to PHP the netdevices
          $.ajax({
               type: 'POST',
               url: '../code/php/ports.php',
               data: {
                    act: 'get_dev',
                    type: type.value
               },
               beforeSend: function() {
                    console.log('Loading...');
               },
               success: function(data){
                    if (data == false) {
                         error.innerHTML = 'Hubo un error interno';
                    } else {
                         error.innerHTML = '';
                         var arr = JSON.parse(data);
                         for (var i = 0; i < arr.length; i++) {
                              var option = document.createElement('option');
                              option.innerHTML = arr[i]['BRAND'] + ' ' + arr[i]['MODEL'] + ' [' + arr[i]['MACND'] + ']';
                              option.setAttribute('value', arr[i]['MACND']);
                              netdevices.appendChild(option);
                         }
                         netdevices.style.display = 'block';
                    }
               }
          });
     }
}
