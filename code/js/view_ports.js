var type = document.getElementById('type');
var netdevices = document.getElementById('netdevices');
var ports = document.getElementById('ports');
var error = document.getElementById('error');
var ports_form = document.getElementById('ports_form');

netdevices.style.display = 'none';
ports.style.display = 'none';

type.onchange = function() {
     if (type.value != 'null') {
          // Reset values of netdevices
          netdevices.value = 'null';
          // Hide the table
          ports.style.display = 'none';
          // Request to PHP the netdevices
          $.ajax({
               type: 'POST',
               url: '../code/php/ports.php',
               data: {
                    act: 'get_dev',
                    type: type.value
               },
               beforeSend: function() {
                    console.log('Loading Net_devices');
               },
               success: function(data){
                    if (data == false) {
                         error.innerHTML = 'Hubo un error interno';
                    } else {
                         error.innerHTML = '';
                         for (var i = 0; i < netdevices.getElementsByTagName('option').length; i++) {
                              if (i != 0) {
                                   netdevices.removeChild(netdevices.getElementsByTagName('option')[i]);
                              }
                         }
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

netdevices.onchange = function() {
     if (type.value != 'null') {
          // Request to PHP the ports
          $.ajax({
               type: 'POST',
               url: '../code/php/ports.php',
               data: {
                    act: 'get_ports',
                    mac: netdevices.value
               },
               beforeSend: function() {
                    console.log('Loading ports');
               },
               success: function(data){
                    if (data == false) {
                         error.innerHTML = 'Hubo un error interno';
                    } else {
                         error.innerHTML = '';
                         var arr = JSON.parse(data);
                         // Remove older rows
                         ports_form.innerHTML = '';
                         for (var i = 0; i < arr.length; i++) {
                              // Parse data
                              if (arr[i]['LOCATION'] == 'null') {
                                   arr[i]['LOCATION'] = '';
                              }

                              // Generate table row
                              var tr = document.createElement('tr');

                              var td_name = document.createElement('td');
                              td_name.innerHTML = arr[i]['NAME'];
                              ports.style.display = 'block';
                              tr.appendChild(td_name);

                              var td_input = document.createElement('td');
                              var input = document.createElement('input');
                              input.type = 'text';
                              input.className = 'form-control';
                              input.id = arr[i]['NAME'];
                              input.value = arr[i]['LOCATION'];
                              td_input.appendChild(input);
                              tr.appendChild(td_input);
                              console.log(arr);
                              ports_form.appendChild(tr);
                         }
                    }
               }
          });
     }
}
