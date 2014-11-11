$(document).ready(function(){
   $("a.external").live('click', function() {
      url = $(this).attr("href");
      window.open(url, '_blank');
      return false;
    });
});



