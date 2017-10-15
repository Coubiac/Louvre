function getfulldays() {
    var route = Routing.generate('findunavailabledates');
    var dates = [];
    $.ajax({
        async: false,
        type: "GET",
        url: route,
        dataType: "json",
        success: function (response) {

            for (var i = 0; i < response.length; i++) {
                var date = new Date(response[i] * 1000);

                dates.push([date.getFullYear(), date.getMonth(), date.getDate()]);
            }
        }
    });
    return dates;
}



$(document).ready(function () {

    //-------------------------------------------
    //Material Design
    $('.parallax').parallax();
    $('select').material_select();

    //Datepicker pour date de visite

    var halfDay = $("#appbundle_order_fullDayTicket option[value='0']");
    var fullday = $("#appbundle_order_fullDayTicket option[value='1']");
    var now = new Date();
    var hour = ('0'+now.getHours()  ).slice(-2);


    var fulldays = getfulldays();
    var closedDays = GetHollidays();
    var hollidays = closedDays.concat(fulldays);
    if(hour >= 18){
        var nowClosed =[];
        nowClosed.push([ now.getFullYear(), now.getMonth(), now.getDate() ]);
        hollidays = hollidays.concat(nowClosed);
    }


    if(lang === "fr"){
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 2,
            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            formatSubmit: 'yyyy/mm/dd',
            format: 'dd/mm/yyyy',
            today: 'Aujourd\'hui',
            clear: 'Effacer',
            close: 'Ok',
            min: new Date(),
            firstDay: 1,
            closeOnSelect: false,
            onSet: function (context) {
                var selectedTimestamp = parseInt(context.select / 1000);
                var route = Routing.generate('countavailabletickets', {'timestamp': selectedTimestamp});
                $.ajax({
                    async: true,
                    type: "GET",
                    url: route,
                    dataType: "json",
                    success: function (response) {
                        if(response < 1000){
                            Materialize.toast('Attention ! il ne reste que ' + response + ' tickets à cette date !', 4000, 'deep-orange darken-4 rounded');
                        }
                        $('.ticketform').collection({
                            min: 0,
                            max: response,
                            init_with_n_elements: 1,
                            hide_useless_buttons: true,
                            allow_up: false,
                            prototype_name: '',
                            name_prefix: '',
                            allow_down: false
                        });
                        dateOfVisit = new Date(parseInt(context.select));

                        if (transformDate(dateOfVisit) === transformDate(now) && hour >= 14 && hour < 24){
                            fullday.remove();
                            $('select').material_select();
                        }
                        else{
                            fullday.insertBefore(halfDay);
                            $('select').material_select();
                        }
                    }
                });
            },

            disable: hollidays
        });

    }
    else{
        $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 2,
            format: 'dd/mm/yyyy',
            today: 'Today',
            clear: 'Clear',
            close: 'Ok',
            min: new Date(),
            firstDay: 1,
            closeOnSelect: false,
            onSet: function (context) {
                var selectedTimestamp = context.select / 1000;



                var route = Routing.generate('countavailabletickets', {'timestamp': selectedTimestamp});
                console.log(context.select);
                $.ajax({
                    async: true,
                    type: "GET",
                    url: route,
                    dataType: "json",
                    success: function (response) {

                        if(response < 1000){
                            alert('Attention ! il ne reste que ' + response + ' tickets à cette date !')
                        }
                        $('.ticketform').collection({
                            min: 0,
                            max: response,
                            init_with_n_elements: 1,
                            hide_useless_buttons: true,
                            allow_up: false,
                            prototype_name: '',
                            name_prefix: '',
                            allow_down: false
                        });
                        dateOfVisit = new Date(parseInt(context.select));

                        if (transformDate(dateOfVisit) === transformDate(now) && hour >= 14 && hour < 24){
                            fullday.remove();
                            $('select').material_select();
                        }
                        else{
                            fullday.insertBefore(halfDay);
                            $('select').material_select();
                        }
                    }
                });
            },
            disable: hollidays
        });
    }



});



function Easter(y) // Takes a given year (y) then returns Date object of Easter Sunday
{
    /*
     Easter Date Function for JavaScript implemented by Furgelnod ( https://furgelnod.com )
     Using algorithm published at The Date of Easter (on aa.usno.navy.mil, Oct 2007)
     (https://web.archive.org/web/20071015045929/http://aa.usno.navy.mil/faq/docs/easter.php)
     The algorithm is credited to J.-M. Oudin (1940) and is reprinted in the
     Explanatory Supplement to the Astronomical Almanac, ed. P. K. Seidelmann (1992).
     See Chapter 12, "Calendars", by L. E. Doggett.
     */
    try {
        y = Number(y);
        if (y != y) {
            throw new TypeError("Value must be a number.");
        }
        else if (y > 275760 || y < -271820) {
            throw new RangeError("Value be between -271820 and 275760 due to technical limitations of Date constructor.");
        }
    }
    catch (e) {
        console.error(e);
    }

    y = Math.floor(y);
    var c = Math.floor(y / 100);
    var n = y - 19 * Math.floor(y / 19);
    var k = Math.floor(( c - 17 ) / 25);
    var i = c - Math.floor(c / 4) - Math.floor(( c - k ) / 3) + 19 * n + 15;
    i = i - 30 * Math.floor(i / 30);
    i = i - Math.floor(i / 28) * ( 1 - Math.floor(i / 28) * Math.floor(29 / ( i + 1 )) * Math.floor(( 21 - n ) / 11) );
    var j = y + Math.floor(y / 4) + i + 2 - c + Math.floor(c / 4);
    j = j - 7 * Math.floor(j / 7);
    var l = i - j;
    var m = 3 + Math.floor(( l + 40 ) / 44);
    var d = l + 28 - 31 * Math.floor(m / 4);
    var z = new Date();
    z.setFullYear(y, m - 1, d);
    return z;
} // -- easterDate


function GetHollidays() { //Fonction qui renvoit un tableau des jours de fermeture

    var today = new Date;
    var year = today.getFullYear();


    // DATES VARIABLES
    function easterMonday(year) {     //Lundi de Paques
        var easter = Easter(year);
        var easterMonday = new Date(easter.getTime() + (24 * 60 * 60 * 1000));

        return [easterMonday.getFullYear(), easterMonday.getMonth(), easterMonday.getDate()];
    }

    var easterMondays = [easterMonday(year), easterMonday(year + 1), easterMonday(year + 2)];

    function pentecostMonday(year) {    //Lundi de Pentecôte
        var easter = Easter(year);
        var pentecostMonday = new Date(easter.getTime() + (50 * 24 * 60 * 60 * 1000));


        return [pentecostMonday.getFullYear(), pentecostMonday.getMonth(), pentecostMonday.getDate()];

    }

    var pentecostMondays = [pentecostMonday(year), pentecostMonday(year + 1), pentecostMonday(year + 2)];

    function ascent(year) {    //Jeudi de l'acencion
        var easter = Easter(year);

        var ascent = new Date(easter.getTime() + (39 * 24 * 60 * 60 * 1000));

        return [ascent.getFullYear(), ascent.getMonth(), ascent.getDate()];
    }

    var ascents = [ascent(year), ascent(year + 1), ascent(year + 2)];

    // Dates Fixes
    var year1 = year + 1;
    var year2 = year + 2;
    var newYearDays = [[year, 0, 1], [year1, 0, 1], [year2, 0, 1]];
    var laborDays = [[year, 4, 1], [year1, 4, 1], [year2, 1, 1]];
    var ww2Victorys = [[year, 4, 8], [year1, 4, 8], [year2, 4, 8]];
    var july14ths = [[year, 6, 14], [year1, 6, 14], [year2, 6, 14]];
    var assumptions = [[year, 7, 15], [year1, 7, 15], [year2, 7, 15]];
    var toussaints = [[year, 10, 1], [year1, 10, 1], [year2, 10, 1]];
    var ww1Victorys = [[year, 10, 11], [year1, 10, 11], [year2, 10, 11]];
    var christmas = [[year, 11, 25], [year1, 11, 25], [year2, 11, 25]];


    //Jours de Fermetures Hebdomadaires
    var closedDays = [0, 2];

    //Jours Complets



    var hollidayarray = easterMondays.concat(
        pentecostMondays,
        ascents,
        newYearDays,
        laborDays,
        ww2Victorys,
        july14ths,
        assumptions,
        toussaints,
        ww1Victorys,
        christmas
    ); //TODO: Rajouter les jours de fermeture


    //hollidayarray.push(0,2);

    return hollidayarray;


}

function transformDate(date){

    var year   = date.getFullYear();
    var month    = ('0'+(date.getMonth() + 1 )).slice(-2);
    var day    = ('0'+date.getDate()   ).slice(-2);
    return  day + "/" + month + "/" + year;
}