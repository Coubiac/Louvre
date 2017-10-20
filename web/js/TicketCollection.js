var $collectionHolder;

// setup an "add a ticket" link
    var $addTicketLink = $('<a href="#" class="add_ticket_link waves-effect waves-light btn deep-orange accent-3 circle">+</a>');

var $newLinkLi = $('<li></li>').append($addTicketLink);

function addTicketForm($collectionHolder, $newLinkLi) {

    if (maxTickets > 0) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // You need this only if you didn't set 'label' => false in your tickets field in TaskType
        // Replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        // newForm = newForm.replace(/__name__label__/g, index);

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a ticket" link li
        var $newFormLi = $('<li></li>').append(newForm);
        // add a delete link to the new form
        addTicketFormDeleteLink($newFormLi);

        $newLinkLi.before($newFormLi);

        $('select').material_select();
        maxTickets--;

    }

}

function addTicketFormDeleteLink($ticketFormLi) {
    var $removeFormA = $('<a href="#" class="remove_ticket_link waves-effect waves-light btn deep-orange accent-3">-</a>');
    $ticketFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        maxTickets++;
        if(maxTickets >= 0){
            $('ul.tickets').append($newLinkLi);
        }

        // remove the li for the tag form
        $ticketFormLi.remove();

    });
}