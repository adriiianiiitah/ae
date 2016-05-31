
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

  $("#owl-demo").owlCarousel({
    items:1,
    loop:true,
    margin:10,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    animateOut: 'fadeOutUp',
    //animateIn: 'fadeOutUp',
  });

  $('.play').on('click',function(){
    owl.trigger('autoplay.play.owl',[1000])
  })
  $('.stop').on('click',function(){
    owl.trigger('autoplay.stop.owl')
  })

    var owl = $("#slider-marcas");

    owl.owlCarousel({
      loop:true,
      margin:10,
      responsiveClass:true,
      navText: ['<i class="fa fa-arrow-left"></i>','<i class="fa fa-arrow-right"></i>'],
      responsive:{
          0:{
              items:1,
              nav:true
          },
          450:{
              items:2,
              nav:false
          },
          600:{
              items:3,
              nav:false
          },
          1000:{
              items:5,
              nav:true
          }
      }
  });

  $('#flat-slider').slider({
    orientation: 'horizontal',
    range:       true,
    values:      [0,1200]
  });

  $( "#fecha_nacimiento" ).datepicker({
    dateFormat: "yy-mm-dd"
  });





  $('#form-login').submit(function(e){
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "index.php?ctrl=usuario&action=login",
        data: $('#form-login').serialize(),//_data,
        dataType: "json",
        success: function(data) {
          if(data){
              $('#mensaje').append( '<div class="alert alert-success succes-message" role="alert">¡Correcto!</div>' );
              //alert(data);
              window.location.href = "index.php";
          } else {
            $('#mensaje').append( '<div class="alert alert-danger error-message" role="alert">Las credenciales no coinciden.</div>' );
          }  
        },
        error: function(result) {
          $('#mensaje').append( '<div class="alert alert-danger error-message" role="alert">Las credenciales no coinciden.</div>' );
        }
    });
  });

});


function login() {
      }




