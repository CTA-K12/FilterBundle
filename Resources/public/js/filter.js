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
            var html = '<table';
            html += ' id="filter-interface-table"';
            html += ' class="table table-striped table-bordered table-hover table-responsive"';
            html += ' data-rows="1">';
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
                html += '<div';
                html += ' class="modal fade"';
                html += ' id="' + data['associations'][key].code + 'Modal"';
                html += ' tabindex="-1"';
                html += ' role="dialog"';
                html += ' aria-labelledby="' + data['associations'][key].code + 'ModalLabel">';
                html += '<div';
                html += ' class="modal-dialog"';
                html += ' role="document">';
                html += '<div';
                html += ' class="modal-content">';
                html += '<div';
                html += ' class="modal-header">';
                html += '<button type="button"';
                html += ' class="close"';
                html += ' data-dismiss="modal"';
                html += ' aria-label="Close"><span';
                html += ' aria-hidden="true">&times;</span></button>';
                html += '<h4';
                html += ' class="modal-title"';
                html += ' id="' + data['associations'][key].code + 'ModalLabel">Edit ' + data['associations'][key].name + '</h4>';
                html += '</div>';
                html += '<div';
                html += ' class="modal-body">';
                html += '<form';
                html += ' id="' + data['associations'][key].code + 'Form">';
                html += '<div';
                html += ' class="form-group">';
                html += '<label';
                html += ' for="cell-join"';
                html += ' class="control-label">' + data['associations'][key].name + ':</label>';
                html += '<select';
                html += ' class="form-control change-cell-dropdown"';
                html += ' id="' + data['associations'][key].code + '-cell-join"';
                html += ' name="cell-join"';
                html += ' data-association-id="' + data['associations'][key].associationId + '">';
                html += '<option></option>';
                for (var i = 0; i < data['associations'][key].cells.length; i++) {
                    html += '<option value="' + data['associations'][key].cells[i].id + '">';
                    html += data['associations'][key].cells[i].description;
                    html += '</option>';
                }
                html += '<option';
                html += ' value="-1">Other</option>';
                html += '</select>';
                html += '</div>';
                html += '<div';
                html += ' class="form-group hidden">';
                html += '<select';
                html += ' id="' + data['associations'][key].code + '-new-cell"';
                html += ' name="new-cell[]"';
                html += ' class="form-control new-cell"';
                html += ' multiple="multiple">';
                for (var i = 0; i < data['associations'][key].values.length; i++) {
                    html += '<option value="' + data['associations'][key].values[i].id + '">';
                    html += data['associations'][key].values[i].name;
                    html += '</option>';
                }
                html += '</select>';
                html += '</div>';
                html += '<input type="hidden"';
                html += ' name="trail-entity-id"';
                html += ' value="' + data['associations'][key].trailEntityId + '" />';
                html += '<input type="hidden"';
                html += ' name="association-id"';
                html += ' value="' + data['associations'][key].associationId + '" />';
                html += '</form>';
                html += '</div>';
                html += '<div';
                html += ' class="modal-footer">';
                html += '<button type="button"';
                html += ' class="btn btn-default"';
                html += ' data-dismiss="modal">Close</button>';
                html += '<button type="button"';
                html += ' class="btn btn-primary modal-save"';
                html += ' data-code="' + data['associations'][key].code + '"';
                html += ' data-url="' + data['url'] + '"';
                html += ' >Set ' + data['associations'][key].name + '</button>';
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
                var button = $(event.relatedTarget);
                var divId = button.data('div-id');
                var modal = $(this);
                modal.find('.new-cell option').removeAttr('selected');
                modal.find('.modal-save').data('div-id', divId);
            });
            
            $('.modal-save').on('click', function () {
                var button = $(this);
                var url = button.data('url');
                var modal = $('#' + button.data('code') + 'Modal');
                var form = $('#' + button.data('code') + 'Form');
                var div = $('#' + button.data('div-id'));
                var data = form.serializeArray();
                $.ajax({
                    url: url,
                    data: data,
                    method: 'POST'
                }).done(function(data) {
                    div.html(data.description);
                    div.data('cell-id', data.id);
                    updateDefinition();
                    modal.modal('hide');
                });
            });
        });
    });

    function addRow(association)
    {
        var html = '';
        html += '<tr';
        html += ' class="filter-row">';
        var i = 0;
        for (var key in association) {
            if (0 < i) {
                html += '<td>AND</td>';
            }
            var row = $('#filter-interface-table').data('rows');
            if (undefined === row) {
                row = 0;
            }
            html += '<td>';
            html += '<div';
            html += ' id="' + association[key].code + '-' + row + '"';
            html += ' class="filter-cell"';
            html += ' data-cell-id="*">';
            html += 'No ' + association[key].name + ' selected';
            html += '</div>';
            html += '<div>';
            html += '<button';
            html += ' type="button"';
            html += ' class="btn btn-default"';
            html += ' data-toggle="modal"';
            html += ' data-target="#' + association[key].code + 'Modal"';
            html += ' data-whatever="' + association[key].code + '"';
            html += ' data-div-id="' + association[key].code + '-' + row + '" >';
            html += 'Edit ' + association[key].name;
            html += '</button>';
            html += '</div>';
            html +='</td>';
            i++;
        }
        html += '</tr>';

        return html;
    }
    
    function updateDefinition()
    {
        var value = '';
        var rows = $('.filter-row');
        var n = rows.length;
        for (var i = 0; i < n; i++) {
            if (0 < i) {
                value += 'v';
            }
            var cells = $(rows[i]).find('.filter-cell');
            var n2 = cells.length;
            for (var i2 = 0; i2 < n2; i2++) {
                if (0 < i2) {
                    value += '^';
                }
                value += $(cells[i2]).data('cell-id');
                console.log($(cells[i2]));
            }
        }
        $('#mesd_filterbundle_filter_filterRow').val(value);
    }
});
