var type = document.getElementById('type');
var devices = document.getElementById('ndev');
var ports = document.getElementById('ports');
var error = document.getElementById('error');
var locations = document.getElementById('locations');
var update_btn = document.getElementById('update_locations');

devices.style.display = 'none';
ports.style.display = 'none';
update_btn.style.display = 'none';

type.onchange = function() {
     $.ajax({
          type: 'POST',
          url: '../code/php/ports.php',
          data: {
               type: type.value,
               act: 'get_dev'
          },
          success: function(data){
               if (data == '[]') {
                    for (var i = devices.options.length-1; i >= 0; i--) {
                         devices.options[i] = null;
                    }
                    var option = document.createElement("option");
                    devices.add(option,0);
                    devices.options[0].innerHTML = '-No se encontraron dispositivos-';
                    devices.options[0].value = 'null';
                    devices.options[0].disabled = true;
                    devices.style.display = 'block';
               } else {
                    for (var i = devices.options.length-1; i >= 0; i--) {
                         devices.options[i] = null;
                    }
                    console.log(data);
                    /*
                    var ldev = JSON.parse(data);
                    for (var i = 0; i < ldev.length; i++) {
                         var option = document.createElement('option');
                         option.value = ldev[i]['MACND'];
                         option.innerHTML = ldev[i]['BRAND']+' '+ldev[i]['MODEL']+' '+'['+ldev[i]['MACND']+']';
                         devices.appendChild(option);
                    }
                    devices.value = ldev[0]['MACND'];
                    devices.style.display = 'block';
                    devices.onchange();
                    */
               }
          }
     });
}

devices.onchange = function loadPorts() {
     $.ajax({
          type: 'POST',
          url: '../code/php/ports.php',
          data: {
               mac: devices.value,
               act: 'get_ports'
          },
          beforeSend: function() {
               ports.innerHTML = '';
               ports.style.display = 'none';
               update_btn.style.display = 'none';
               error.innerHTML = 'Cargando...';
          },
          success: function(data){
               if (data == false) {
                    error.innerHTML = 'Hubo un error interno';
               } else {
                    error.innerHTML = '';
                    ports.innerHTML = data;
                    ports.style.display = 'block';
                    update_btn.style.display = 'block';
                    locations.onclick();
               }
          }
     });
}

locations.onclick = function() {
     $.ajax({
          type: 'POST',
          url: '../code/php/ports.php',
          data: {
               act: 'req_locations'
          },
          beforeSend: function() {
               var option = locations.lastElementChild;
               while (option) {
                    locations.removeChild(option);
                    option = locations.lastElementChild;
               }
          },
          success: function(data){
               var lcts = JSON.parse(data);
               for (var i = 0; i < lcts.length; i++) {
                    if (lcts[i]['LOCATION'] != null) {
                         var options = locations.getElementsByTagName['option'];
                         var location = document.createElement('option');
                         location.value = lcts[i]['LOCATION'];
                         locations.appendChild(location);
                    }
               }
               var loc_arr = [];
               for (var i = 0; i < locations.childElementCount; i++) {
                    loc_arr.push(locations.getElementsByTagName('option')[i].value);
               }
               var unique_loc = [];
               $.each(loc_arr, function(i, el){
                    if($.inArray(el, unique_loc) === -1) unique_loc.push(el);
               });
               var option = locations.lastElementChild;
               while (option) {
                    locations.removeChild(option);
                    option = locations.lastElementChild;
               }
               for (var i = 0; i < unique_loc.length; i++) {
                    var option = document.createElement('option');
                    option.value = unique_loc[i];
                    locations.appendChild(option);
               }
          }
     });
}

update_btn.onclick = function() {
     var inputs = ports.getElementsByTagName('input');
     var ports_locations = [];
     for (var i = 0; i < inputs.length; i++) {
          var int_arr = [];
          int_arr.push(inputs[i].value);
          int_arr.push(inputs[i].getAttribute('pname'));
          ports_locations.push(int_arr);
     }

     ports_locations_json = JSON.stringify(ports_locations);

     $.ajax({
          type: 'POST',
          url: '../code/php/ports.php',
          data: {
               act: 'upd_ports',
               pl: ports_locations_json,
               mac: devices.value
          },
          beforeSend: function() {
               ports.innerHTML = '';
               ports.style.display = 'none';
               update_btn.style.display = 'none';
               error.innerHTML = 'Cargando...';
          },
          success: function(data){
               if (data == false) {
                    error.innerHTML = 'Hubo un error interno, quizás no cambiaste nada';
               } else {
                    devices.onchange();
               }
          }
     });
}
