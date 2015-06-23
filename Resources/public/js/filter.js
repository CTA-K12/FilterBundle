'use strict';

$( document ).ready(function()
{
    $('#mesd_filterbundle_filter_filterCategory').on('change', function()
    {
        var url = $(this).data('url');
        url = url.replace('-1', $(this).val());
        $.ajax({
            url: url
        }).done(function(msg) {
            var html = '<table id="filter-interface-table" class="table table-striped table-bordered table-hover table-responsive">';
            html += '<tr>';
            for (var i = 0; i < msg.length; i++) {
                if (0 < i) {
                    html += '<th></th>';
                }
                html += '<th>' + msg[i] + '</th>'
            }
            html += '</tr>';
            html += addRow(msg);
            html += '</table>';
            if (1 < msg.length) {
                html += '<a id="filter-row-add" class="btn btn-default" href="#">Add Row</a>';
            }
            $('#filter-interface').html(
                html
            );
            $('#filter-row-add').on('click', function () {
                var html = '';
                html += '<tr>'
                html += '<td colspan="' + ((msg.length * 2) - 1) + '">OR</td>'
                html += '</tr>'
                html += addRow(msg)
                $('#filter-interface-table').append(
                    html
                );
            });
        });
    });

    function addRow(msg)
    {
        var html = '';
        html += '<tr>';
        for (var i = 0; i < msg.length; i++) {
            if (0 < i) {
                html += '<td>AND</td>';
            }
            html += '<td>';
            html += '<div>';
            html += 'No ' + msg[i] + ' selected';
            html += '</div>';
            html += '<div>';
            html += '<a class="btn btn-default" href="#">Edit Cell</a>';
            html += '</div>';
            html +='</td>';
        }
        html += '</tr>';

        return html;
    }
    
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('whatever'); // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.modal-title').text('New message to ' + recipient);
        modal.find('.modal-body input').val(recipient);
    });
});
