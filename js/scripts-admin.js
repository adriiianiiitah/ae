$(function() {
  $( "#fecha" ).datepicker({
    dateFormat: "dd-mm-yy"
  });

  $( "#fecha_nacimiento" ).datepicker({
    dateFormat: "dd-mm-yy"
  });


  $( "#desde" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "dd-mm-yy",
      onClose: function( selectedDate ) {
        $( "#hasta" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#hasta" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "dd-mm-yy",
      onClose: function( selectedDate ) {
        $( "#desde" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $("#btn-delete-color").click(function() {
      //console.log($(this));
    });
});