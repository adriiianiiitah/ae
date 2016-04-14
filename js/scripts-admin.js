$(function() {
  $( "#fecha" ).datepicker({
    dateFormat: "yy-mm-dd"
  });

  $( "#fecha_nacimiento" ).datepicker({
    dateFormat: "yy-mm-dd"
  });


  $( "#desde" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#hasta" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#hasta" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      dateFormat: "yy-mm-dd",
      onClose: function( selectedDate ) {
        $( "#desde" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $("#btn-delete-color").click(function() {
      //console.log($(this));
    });
});