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
          if (type.value == 'ap') {
               // Display the only port because is a wireless AP
               // Request to PHP the port location
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
                              resp.innerHTML = 'Error interno al obtener el puerto del AP';
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
                              for (var i = 0; i < 1; i++) {
                                   // Parse data
                                   if (arr[0]['LOCATION'] == 'null') {
                                        arr[0]['LOCATION'] = '';
                                   }

                                   // Generate table row
                                   var tr = document.createElement('tr');

                                   var td_name = document.createElement('td');
                                   td_name.innerHTML = 'Único';
                                   ports.style.display = 'block';
                                   tr.appendChild(td_name);

                                   var td_input = document.createElement('td');
                                   var input = document.createElement('input');
                                   input.type = 'text';
                                   input.className = 'form-control';
                                   input.id = 'unique';
                                   input.value = arr[0]['LOCATION'];
                                   td_input.appendChild(input);
                                   tr.appendChild(td_input);
                                   ports_form.appendChild(tr);
                                   upd_ports_btn.style.display = 'block';
                              }
                         }
                    }
               });
          } else {
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
}

upd_ports_btn.onclick = function() {
     var ports_arr = ports_form.getElementsByTagName('input');
     var ports_arr_clean = [];
     for (var i = 0; i < ports_arr.length; i++) {
          ports_arr_clean[i] = [netdevices.value, ports_arr[i].id, ports_arr[i].value];
     }
     var ports_json = JSON.stringify(ports_arr_clean);
     var ap = false;
     if (type == 'ap') {
          ap = true;
     }

     $.ajax({
          type: 'POST',
          url: '../code/php/ports.php',
          data: {
               act: 'upd_ports',
               json: ports_json,
               ap: ap
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
                    resp.innerHTML = data;
                    ports.style.display = 'none';
                    upd_ports_btn.style.display = 'none';
               }
          }
     });
}
