
$(function () {

  function abso() {

    $('#owl-item img').css({
         
      width: $(window).width(),
      height: $(window).height() -83
    });
  }

  $(window).resize(function() {
      abso();         
  });
});




