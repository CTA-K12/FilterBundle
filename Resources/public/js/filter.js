
$(document).ready(function ()
{
    'use strict';
    var solvent = $('#mesd_filterbundle_filter_filterRow').val();
    if ('' !== solvent) {
        console.log(solvent);
        solvent = JSON.parse(solvent);
        console.log(solvent);
    }

    $('#mesd_filterbundle_filter_filterCategory').on('change', function ()
    {
        var url = $(this).attr('data-url');
        url = url.replace('-1', $(this).val());
        $.ajax({
            url: url
        }).done(function (data) {
            loadTable(data);
        });
    });

    function loadModals (associations) {
        var html = '';
        for (var key in associations) {
            html += '<div';
            html += ' class="modal fade"';
            html += ' id="' + associations[key].code + 'Modal"';
            html += ' tabindex="-1"';
            html += ' role="dialog"';
            html += ' aria-labelledby="' + associations[key].code + 'ModalLabel">';
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
            html += ' id="' + associations[key].code + 'ModalLabel">Edit ' + associations[key].name + '</h4>';
            html += '</div>';
            html += '<div';
            html += ' class="modal-body">';
            html += '<form';
            html += ' id="' + associations[key].code + 'Form">';
            html += '<div';
            html += ' class="form-group">';
            html += '<label';
            html += ' for="cell-join"';
            html += ' class="control-label">' + associations[key].name + ':</label>';
            html += '<select';
            html += ' class="form-control change-cell-dropdown"';
            html += ' id="' + associations[key].code + '-cell-join"';
            html += ' name="cell-join"';
            html += ' data-association-id="' + associations[key].associationId + '">';
            html += '<option></option>';
            for (var i = 0; i < associations[key].cells.length; i++) {
                html += '<option value="' + JSON.stringify(associations[key].cells[i].solvent) + '">';
                html += associations[key].cells[i].description;
                html += '</option>';
            }
            html += '<option';
            html += ' value="-1">Other</option>';
            html += '</select>';
            html += '</div>';
            html += '<div';
            html += ' class="form-group hidden">';
            html += '<select';
            html += ' id="' + associations[key].code + '-new-cell"';
            html += ' name="new-cell[]"';
            html += ' class="form-control new-cell"';
            html += ' multiple="multiple">';
            for (i = 0; i < associations[key].values.length; i++) {
                html += '<option value="' + associations[key].values[i].id + '">';
                html += associations[key].values[i].name;
                html += '</option>';
            }
            html += '</select>';
            html += '</div>';
            html += '<input type="hidden"';
            html += ' name="trail-entity-id"';
            html += ' value="' + associations[key].trailEntityId + '" />';
            html += '<input type="hidden"';
            html += ' name="association-id"';
            html += ' value="' + associations[key].associationId + '" />';
            html += '</form>';
            html += '</div>';
            html += '<div';
            html += ' class="modal-footer">';
            html += '<button type="button"';
            html += ' class="btn btn-default"';
            html += ' data-dismiss="modal">Close</button>';
            html += '<button type="button"';
            html += ' class="btn btn-primary modal-save"';
            html += ' data-code="' + associations[key].code + '"';
            html += ' >Set ' + associations[key].name + '</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        }
        $('#modals').html(
            html
        );
    }

    function loadTable (data) {
        var html = '<table';
        html += ' id="filter-interface-table"';
        html += ' class="table table-striped table-bordered table-hover table-responsive"';
        html += ' data-rows="1">';
        html += '<tbody>';
        html += '<tr>';
        var i = 0;
        for (var key in data.associations) {
            if (0 < i) {
                html += '<th></th>';
            }
            html += '<th>' + data.associations[key].name + '</th>';
            i++;
        }
        html += '</tr>';
        html += addRow(data.associations);
        html += '</tbody>';
        html += '</table>';
        if (1 < i) {
            html += '<a id="filter-row-add" class="btn btn-default" href="#">';
            html += 'Add Row';
            html += '</a>';
        }
        $('#filter-interface').html(
            html
        );
        loadModals(data.associations);
        $('#filter-row-add').on('click', function () {
            var html = '';
            html += '<tr>';
            html += '<td colspan="' + ((data.associations.length * 2) - 1) + '">OR</td>';
            html += '</tr>';
            html += addRow(data.associations);
            var table = $('#filter-interface-table');
            table.append(
                html
            );
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
            var divId = button.attr('data-div-id');
            var modal = $(this);
            modal.find('.new-cell option').removeAttr('selected');
            modal.find('.modal-save').attr('data-div-id', divId);
        });

        $('.modal-save').on('click', function () {
            var button = $(this);
            var modal = $('#' + button.attr('data-code') + 'Modal');
            var form = $('#' + button.attr('data-code') + 'Form');
            var div = $('#' + button.attr('data-div-id'));
            var associationId = div.attr('data-association-id');
            var data = form.serializeArray();
            var cellJoin = $('#' + button.attr('data-code') + '-cell-join');
            var description = '';
            var selectedValues = '';
            var selectedValuesString = '';
            var selectedText = cellJoin.children(':selected').text();
            if ('Other' === selectedText) {
                var newCell = $('#' + button.attr('data-code') + '-new-cell');
                selectedValues = newCell.val();
                n = selectedValues.length;
                for (var i = 0; i < n; i++) {
                    selectedValues[i] = parseInt(selectedValues[i]);
                }
                selectedValues.sort(function (a, b)
                {
                    return a - b;
                });
                selectedValuesString = JSON.stringify(selectedValues);
                var selectedOptions = newCell.children('option:selected');
                description = '';
                i = 0;
                var n = selectedOptions.length;
                selectedOptions.each(function() {
                    if (0 < i) {
                        description += ', ';
                        if ((i + 1) === n) {
                            description += 'or ';
                        }
                    }
                    description += $(this).text();
                    i++;
                });
            } else {
                description = selectedText;
                selectedValuesString = cellJoin.val();
            }
            div.html(description);
            div.attr('data-cell-solvent', selectedValuesString);
            updateDefinition();
            modal.modal('hide');
        });
    }

    function addRow(association)
    {
        var html = '';
        html += '<tr';
        html += ' class="filter-row">';
        var i = 0;
        var row = $('#filter-interface-table').attr('data-rows');
        if (undefined === row) {
            row = 0;
        }
        for (var key in association) {
            if (0 < i) {
                html += '<td>AND</td>';
            }
            html += '<td>';
            html += '<div';
            html += ' id="' + association[key].code + '-' + row + '"';
            html += ' class="filter-cell"';
            html += ' data-cell-solvent="[-1]"';
            html += ' data-association-id="' + association[key].associationId + '">';
            html += 'No ' + association[key].name + ' selected';
            html += '</div>';
            html += '<div>';
            html += '<button';
            html += ' type="button"';
            html += ' class="btn btn-default"';
            html += ' data-toggle="modal"';
            html += ' data-div-id="' + association[key].code + '-' + row + '" >';
            html += 'Edit ' + association[key].name;
            html += '</button>';
            html += '</div>';
            html += '</td>';
            i++;
        }
        $('#filter-interface-table').attr('data-rows', parseInt(row) + 1);
        html += '</tr>';

        return html;
    }
    
    function updateDefinition()
    {
        var rows = $('.filter-row');
        var n = rows.length;
        var rowSolvent = [];
        for (var i = 0; i < n; i++) {
            var cells = $(rows[i]).find('.filter-cell');
            var n2 = cells.length;
            var cellSolvent = [];
            for (var i2 = 0; i2 < n2; i2++) {
                var cell = $(cells[i2]);
                var solvent = JSON.parse(cell.attr('data-cell-solvent'));
                var associationId = cell.attr('data-association-id');
                var json = {
                    associationId: associationId,
                    solvent: solvent
                };
                cellSolvent.push(json);
            }
            rowSolvent.push(cellSolvent);
        }
        var value = JSON.stringify(rowSolvent);
        $('#mesd_filterbundle_filter_filterRow').val(value);
    }
});
