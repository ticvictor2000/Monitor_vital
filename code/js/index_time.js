function time() {
     var datetime_container = document.getElementById('datetime');
     var datetime = new Date();

     var day = datetime.getDate();
     var month = datetime.getMonth()+1;
     var year = datetime.getFullYear();

     var hour = datetime.getHours();
     var minutes = datetime.getMinutes();
     if (minutes < 10) {
          minutes = '0'+minutes;
     }
     var seconds = datetime.getSeconds();
     if (seconds < 10) {
          seconds = '0'+seconds;
     }

     datetime_container.innerHTML = day+'/'+month+'/'+year+' '+hour+':'+minutes+':'+seconds;
}
time();
setInterval(time,1000);
