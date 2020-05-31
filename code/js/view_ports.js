var type = document.getElementById('type');
var netdevices = document.getElementById('netdevices');
var ports = document.getElementById('ports');
var resp = document.getElementById('result');
var ports_form = document.getElementById('ports_form');
var loading = document.getElementById('loading');
var upd_ports_btn = document.getElementById('upd_ports_btn');

netdevices.style.display = 'none';
loading.style.display = 'none';
ports.style.display = 'none';
upd_ports_btn.style.display = 'none';

type.onchange = function() {
     upd_ports_btn.style.display = 'none';
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
                    loading.style.display = 'block';
               },
               success: function(data){
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
                         resp.innerHTML = '';

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
                    loading.style.display = 'block';
               },
               success: function(data){
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
                         resp.innerHTML = '';

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
                              ports_form.appendChild(tr);
                              upd_ports_btn.style.display = 'block';
                         }
                    }
               }
          });
     }
}

upd_ports_btn.onclick = function() {
     
}
