var ls = ls || {};

ls.document_fields = (function ($) {

    this.field_value_row = 0;

    this.addfieldValue = function () {
        var html  = '<tr id="option-value-row' + ls.document_fields.field_value_row + '">';
        html += '  <td class="text-left"><input type="hidden" name="field_value[' + ls.document_fields.field_value_row + '][field_value_id]" value="" />';

        html += '    <div class="input-group">';
        html += '     <input type="text" name="field_value[' + ls.document_fields.field_value_row + '][name]" value="" placeholder="Значение" class="form-control" />';
        html += '    </div>';


        html += '  </td>';

        //html += '  <td class="text-center"><a href="" id="thumb-image' + ls.document_fields.field_value_row + '" data-toggle="image" class="img-thumbnail"><img src="{{ placeholder }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a><input type="hidden" name="field_value[' + ls.document_fields.field_value_row + '][image]" value="" id="input-image' + ls.document_fields.field_value_row + '" /></td>';
        html += '  <td class="text-right"><input type="text" name="field_value[' + ls.document_fields.field_value_row + '][sorting]" value="" placeholder="Сортировка" class="form-control" /></td>';
        html += '  <td class="text-right"><button type="button" onclick="$(\'#option-value-row' + ls.document_fields.field_value_row + '\').remove();" data-toggle="tooltip" title="Удаление" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#form-field tbody').append(html);

        ls.document_fields.field_value_row++;
    };
    this.changeFieldType = function ($select) {
        if( $.inArray($select.val(), ['select', 'radio', 'checkbox']) !== -1){
            $('.js-fieldValue').show();
        }else{
            $('.js-fieldValue').hide();

        }
    };

    $(function () {
    });
    return this;
}).call (ls.document_fields || {}, jQuery);