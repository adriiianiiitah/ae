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


    $('#producto').one('focusin', function() {
      $(this).html('');
      $(this).load("index.php?ctrl=productos&action=productos");
    });

    $('#categoria').one('focusin', function() {
      $(this).html('');
      $(this).load("index.php?ctrl=categorias&action=categorias");
    });

    $('#categoria').change( function() {
      var id = $(this).val();
      $('#subcategoria').html('');
      $('#subcategoria').load("index.php?ctrl=subcategorias&action=subcategorias&categoria_id="+id);
    });


    $('#subcategoria').one('focusin', function() {
      var id = $('#categoria').val();
      $(this).html('');
      $(this).load("index.php?ctrl=subcategorias&action=subcategorias&categoria_id="+id);
    });

    $('#color').one('focusin', function() {
      $(this).html('');
      $(this).load("index.php?ctrl=colores&action=colores");
    });
});