$(document).ready(function () {
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


    if($('html').attr('lang') === "fr"){
        $('.datepicker').pickadate({
            selectYears: 2,
            monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            formatSubmit: 'yyyy/mm/dd',
            format: 'dd/mm/yyyy',
            today: '',
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
                        maxTickets = response;
                            $('ul.tickets li').remove();
                            $('.remove_ticket_link').remove();
                        if(response < 1){
                            minTickets = 0;
                        }
                        if(response < 10){
                            Materialize.toast('Attention ! il ne reste que ' + response + ' tickets à cette date !', 4000, 'deep-orange darken-4 rounded');
                        }
                        if (response > 0) {
                            // Get the ul that holds the collection of tickets
                            $collectionHolder = $('ul.tickets');
                            // add a delete link to all of the existing tag form li elements
                            $collectionHolder.find('li').each(function () {
                                addTicketFormDeleteLink($(this));
                            });
                            // add the "add a ticket" anchor and li to the tickets ul
                            $collectionHolder.append($newLinkLi);
                            // count the current form inputs we have (e.g. 2), use that as the new
                            // index when inserting a new item (e.g. 2)
                            $collectionHolder.data('index', $collectionHolder.find(':input').length);
                            $addTicketLink.on('click', function (e) {
                                // prevent the link from creating a "#" on the URL
                                e.preventDefault();
                                // add a new ticket form (see next code block)
                                addTicketForm($collectionHolder, $newLinkLi);
                            });
                        }
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
            selectYears: 2,
            format: 'dd/mm/yyyy',
            today: '',
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
                        var selectedTimestamp = parseInt(context.select / 1000);
                        var route = Routing.generate('countavailabletickets', {'timestamp': selectedTimestamp});
                        $.ajax({
                            async: true,
                            type: "GET",
                            url: route,
                            dataType: "json",
                            success: function (response) {
                                maxTickets = response;
                                $('ul.tickets li').remove();
                                $('.remove_ticket_link').remove();
                                if(response < 1){
                                    minTickets = 0;
                                }
                                if(response < 10){
                                    Materialize.toast('Warning ! there are only ' + response + ' tickets left on this date !', 4000, 'deep-orange darken-4 rounded');
                                }
                                if (response > 0) {
                                    // Get the ul that holds the collection of tickets
                                    $collectionHolder = $('ul.tickets');
                                    // add a delete link to all of the existing tag form li elements
                                    $collectionHolder.find('li').each(function () {
                                        addTicketFormDeleteLink($(this));
                                    });
                                    // add the "add a ticket" anchor and li to the tickets ul
                                    $collectionHolder.append($newLinkLi);
                                    // count the current form inputs we have (e.g. 2), use that as the new
                                    // index when inserting a new item (e.g. 2)
                                    $collectionHolder.data('index', $collectionHolder.find(':input').length);
                                    $addTicketLink.on('click', function (e) {
                                        // prevent the link from creating a "#" on the URL
                                        e.preventDefault();
                                        // add a new ticket form (see next code block)
                                        addTicketForm($collectionHolder, $newLinkLi);
                                    });
                                }
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
                    }
            },
            disable: hollidays
        });
    }
});
