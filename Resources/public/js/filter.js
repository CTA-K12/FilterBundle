'use strict';

$( document ).ready(function()
{
    $('#mesd_filterbundle_filter_filterCategory').on('change', function()
    {
        var url = $(this).data('url');
        url = url.replace('-1', $(this).val());
        $.ajax({
            url: url
        }).done(function(data) {
            var html = '<table id="filter-interface-table" class="table table-striped table-bordered table-hover table-responsive" data-rows="1">';
            html += '<tr>';
            var i = 0;
            for (var key in data['associations']) {
                if (0 < i) {
                    html += '<th></th>';
                }
                html += '<th>' + data['associations'][key].name + '</th>'
                i++;
            }
            html += '</tr>';
            html += addRow(data['associations']);
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
            for (var key in data['associations']) {
                html += '<div class="modal fade" id="' + data['associations'][key].code + 'Modal" tabindex="-1" role="dialog" aria-labelledby="' + data['associations'][key].code + 'ModalLabel">';
                html += '<div class="modal-dialog" role="document">';
                html += '<div class="modal-content">';
                html += '<div class="modal-header">';
                html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '<h4 class="modal-title" id="' + data['associations'][key].code + 'ModalLabel">Edit ' + data['associations'][key].name + '</h4>';
                html += '</div>';
                html += '<div class="modal-body">';
                html += '<form>';
                html += '<div class="form-group">';
                html += '<label for="cell-join" class="control-label">' + data['associations'][key].name + ':</label>';
                html += '<select class="form-control change-cell-dropdown" id="' + data['associations'][key].code + '-cell-join" data-association-id="' + data['associations'][key].id + '">';
                html += '<option></option>';
                for (var i = 0; i < data['associations'][key].cells.length; i++) {
                    html += '<option value="' + data['associations'][key].cells[i].id + '">';
                    html += data['associations'][key].cells[i].description;
                    html += '</option>';
                }
                html += '<option value="-1">Other</option>';
                html += '</select>';
                html += '</div>';
                html += '<div class="form-group hidden">';
                html += '<select id="' + data['associations'][key].code + '-new-cell" class="form-control new-cell" multiple="multiple">';
                for (var i = 0; i < data['associations'][key].values.length; i++) {
                    html += '<option value="' + data['associations'][key].values[i].id + '">';
                    html += data['associations'][key].values[i].name;
                    html += '</option>';
                }
                html += '</select>';
                html += '</div>';
                html += '</form>';
                html += '</div>';
                html += '<div class="modal-footer">';
                html += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                html += '<button type="button" class="btn btn-primary modal-save" data-modal="' + data['associations'][key].code + 'Modal">Set ' + data['associations'][key].name + '</button>';
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
                html += '<td colspan="' + ((data['associations'].length * 2) - 1) + '">OR</td>';
                html += '</tr>';
                html += addRow(data['associations']);
                var table = $('#filter-interface-table');
                table.append(
                    html
                )
                table.data('rows', table.data('rows') + 1);
            });
            
            $('.change-cell-dropdown').on('change', function () {
                var dropdown = $(
                    '#' + $(this).attr('id').replace('-cell-join', '-new-cell')
                );
                if ('Other' === $(this).children(':selected').text()) {
                    dropdown.parent().removeClass('hidden');
                } else {
                    dropdown.parent().addClass('hidden');
                }
            });

            $('.modal').on('show.bs.modal', function (event) {
                /*
                var button = $(event.relatedTarget); // Button that triggered the modal
                var recipient = button.data('whatever'); // Extract info from data-* attributes
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                */
                var modal = $(this);
                modal.find('.new-cell option').removeAttr('selected');
            });
            
            $('.modal-save').on('click', function () {
                var modal = $(this).closest('.modal');
                console.log(modal.find('form'));
                console.log(modal.find('form').serialize());
                console.log(modal.find('form')[0]);
                var data = modal.find('form')[0].serialize();
                console.log(data);
                var url = modal.data('url');
                $.ajax({
                    url: url,
                    data: data
                }).done(function(data) {
                    console.log('cool');
                    $('#' + modal.data('modal')).modal('hide');
                });
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
});
