$(function() {
  $( "#fecha" ).datepicker({
    dateFormat: "yy-mm-dd"
  });

  $( "#fecha_nacimiento" ).datepicker({
    dateFormat: "yy-mm-dd"
  });


  $( "#fecha_inicio" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#fecha_fin" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $("#btn-delete-color").click(function() {
      //console.log($(this));
    });

    /*  AJAX  */


    $('#producto').one('click', function() {
      $(this).load("index.php?ctrl=productos&action=productos");
    });

    $('#categoria').one('click', function() {
      $(this).load("index.php?ctrl=categorias&action=categorias");
    });


});