'use strict';

$( document ).ready(function()
{
    $('#mesd_filterbundle_filter_filterCategory').on('change', function()
    {
        var url = $(this).data('url');
        url = url.replace('-1', $(this).val());
        $.ajax({
            url: url
        }).done(function(association) {
            var html = '<table id="filter-interface-table" class="table table-striped table-bordered table-hover table-responsive" data-rows="1">';
            html += '<tr>';
            var i = 0;
            for (var key in association) {
                if (0 < i) {
                    html += '<th></th>';
                }
                html += '<th>' + association[key].name + '</th>'
                i++;
            }
            html += '</tr>';
            html += addRow(association);
            html += '</table>';
            if (1 < i) {
                html += '<a id="filter-row-add" class="btn btn-default" href="#">';
                html += 'Add Row';
                html += '</a>';
            }
            $('#filter-interface').html(
                html
            );
            html = '';
            for (var key in association) {
                html += '<div class="modal fade" id="' + association[key].code + 'Modal" tabindex="-1" role="dialog" aria-labelledby="' + association[key].code + 'ModalLabel">';
                html += '<div class="modal-dialog" role="document">';
                html += '<div class="modal-content">';
                html += '<div class="modal-header">';
                html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '<h4 class="modal-title" id="' + association[key].code + 'ModalLabel">Edit ' + association[key].name + '</h4>';
                html += '</div>';
                html += '<div class="modal-body">';
                html += '<form>';
                html += '<div class="form-group">';
                html += '<label for="cell-join" class="control-label">' + association[key].name + ':</label>';
                html += '<select class="form-control" id="cell-join">';
                html += $('#' + key + '-template').html();
                html += '</select>';
                html += '</div>';
                html += '<div class="form-group">';
                html += '<a href="#" class="btn btn-default">Add New Cell</a>';
                html += '</div>';
                html += '</form>';
                html += '</div>';
                html += '<div class="modal-footer">';
                html += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                html += '<button type="button" class="btn btn-primary">Send message</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            }
            $('#modals').html(
                html
            );
            $('#filter-row-add').on('click', function () {
                var html = '';
                html += '<tr>';
                html += '<td colspan="' + ((association.length * 2) - 1) + '">OR</td>';
                html += '</tr>';
                html += addRow(association);
                var table = $('#filter-interface-table');
                table.append(
                    html
                )
                table.data('rows', table.data('rows') + 1);
            });
        });
    });

    function addRow(association)
    {
        var html = '';
        html += '<tr>';
        var i = 0;
        for (var key in association) {
            if (0 < i) {
                html += '<td>AND</td>';
            }
            html += '<td>';
            html += '<div>';
            html += 'No ' + association[key].name + ' selected';
            html += '</div>';
            html += '<div>';
            html += '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#' + association[key].code + 'Modal" data-whatever="' + association[key].code + '">';
            html += 'Edit ' + association[key].name;
            html += '</button>';
            html += '</div>';
            html +='</td>';
            i++;
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
