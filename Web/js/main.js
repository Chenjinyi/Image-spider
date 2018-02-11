
$(document).ready(function() {
	
$('.menu , .linee').on('click', function() {
  $('.menu').toggleClass('over')
  $('.linea1').toggleClass('overL1')
  $('.linea2').toggleClass('overL2')
  $('.linea3').toggleClass('overL3')
  $('.main-menu').toggleClass('overmain')
  
});
$("html").niceScroll({cursorwidth: '8px',cursorcolor:"#222222" , cursorborder:"1px solid #FFF" , autohidemode: false, zindex: 9999 });
});