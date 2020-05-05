var form = document.getElementById('nnd_form');

form.onsubmit = function() {
     var type = document.getElementById('type').value;
     var ip = document.getElementById('ip').value;
     var telnet = document.getElementById('telnet').value;
     var ssh = document.getElementById('ssh').value;
     var nports = document.getElementById('nports').value;
     var brand = document.getElementById('brand').value;
     var model = document.getElementById('model').value;
     var pass = document.getElementById('pass').value;

     var result = document.getElementById('result');

     $.ajax({
          type: 'POST',
          url: '../code/php/register_netdevice.php',
          data: {
               type: type,
               ip: ip,
               telnet: telnet,
               ssh: ssh,
               nports: nports,
               brand: brand,
               model: model,
               pass: pass
          },
          success: function(data){
               result.innerHTML = data;
          }
     });
}
