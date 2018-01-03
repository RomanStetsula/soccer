$(function() {
    console.log( "ready!" );

    var table = $('#players_table').DataTable();

    $('#players_table tbody').on( 'click', '.delete', function () {

        var id = $( this ).data('id');

        console.log(id);

        $.ajax({
            url: "/delete_rel/" + id,
            context: document.body,
            success: function (data) {
                console.log(data);
            }
        });

        table
            .row($(this).parents('tr'))
            .remove()
            .draw();

    });

});
