
$(document).ready(function() {

    function checkDateRange(start, end) {
        // Parse the entries
        var startDate = Date.parse(start);
        var endDate = Date.parse(end);
        // Make sure they are valid
        if (isNaN(startDate)) {
            alert("The start date provided is not valid, please enter a valid date.");
            return false;
        }
        if (isNaN(endDate)) {
            alert("The end date provided is not valid, please enter a valid date.");
            return false;
        }
        var now = new Date();
        now = Date.parse(now);
        var dif = (startDate - now) / (86400000 * 7);
        if (dif < -0.2){
            alert("La fecha inicial debe no puede ser anterior al dia de hoy");
            return false;
        }
        // Check the date range, 86400000 is the number of milliseconds in one day
        var difference = (endDate - startDate) / (86400000 * 7);
        if (difference < 0) {
            alert("La fecha inicial debe ser anterior a la final");
            return false;
        }

        return true;
    }

    $( "#patientSaveForm" ).submit(function( event ) {

        var fechaDesdeValue = $("#fecha_desde").val()
        var fechaHastaValue = $("#fecha_hasta").val()

        if(!checkDateRange(fechaDesdeValue, fechaHastaValue))
        {
            event.preventDefault();
        }
    });

});