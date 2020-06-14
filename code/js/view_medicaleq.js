var result = document.getElementById('result');
var loading = document.getElementById('loading');
var medialeq_form = document.getElementById('medialeq_form');
var medicaleq_btn = document.getElementById('medicaleq_btn');

loading.style.display = 'none';

$.ajax({
     type: 'POST',
     url: '../code/php/medicaleq.php',
     data: {
          act: 'req_medeq'
     },
     beforeSend: function() {
          loading.style.display = 'block';
     },
     success: function(data){
          var medeq = JSON.parse(data);

          for (var i = 0; i < medeq.length; i++) {
               var tr = document.createElement('tr');
               tr.innerHTML = '<td>'+medeq[i]['MACEQ']+'</td>';

               tr.innerHTML += '<td><input class="form-control" list="types_input" value="'+medeq[i]['TYPE']+'" name="'+medeq[i]['MACEQ']+'" /></td>';

               tr.innerHTML += '<td><input class="form-control" value="'+medeq[i]['BRAND']+'" name="'+medeq[i]['MACEQ']+'" /></td>';

               tr.innerHTML += '<td><input class="form-control" value="'+medeq[i]['MODEL']+'" name="'+medeq[i]['MACEQ']+'" /></td>';

               tr.innerHTML += '<td>'+medeq[i]['LAST_SEEN']+'</td>';
               medialeq_form.appendChild(tr);
          }

          loading.style.display = 'none';
     }
});

medicaleq_btn.onclick = function() {
     var trs = medialeq_form.getElementsByTagName('tr');
     var fields_arr = [];

     for (var i = 0; i < trs.length; i++) {
          var subarr = [];
          subarr.push(trs[i].getElementsByTagName('input')[0].name);
          subarr.push(trs[i].getElementsByTagName('input')[0].value);
          subarr.push(trs[i].getElementsByTagName('input')[1].value);
          subarr.push(trs[i].getElementsByTagName('input')[2].value);

          fields_arr.push(subarr);
     }

     $.ajax({
          type: 'POST',
          url: '../code/php/medicaleq.php',
          data: {
               act: 'act_medeq',
               json: JSON.stringify(fields_arr)
          },
          beforeSend: function() {
               loading.style.display = 'block';
          },
          success: function(data){
               loading.style.display = 'none';

               if (typeof data != 'string') {
                    result.className = 'error';
                    result.innerHTML = 'Error interno al crear el usuario';
               }

               if (data.substr(0,1) == '*') {
                    result.className = 'error';
                    result.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) == '-') {
                    result.className = 'warn';
                    result.innerHTML = data.substr(1,(data.length - 1));
               }

               if (data.substr(0,1) != '*' && data.substr(0,1) != '-') {
                    result.className = 'ok';
                    result.innerHTML = data;
               }
          }
     });
}
