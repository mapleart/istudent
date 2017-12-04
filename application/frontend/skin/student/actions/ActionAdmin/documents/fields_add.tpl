{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {if $oDocumentScheme}

        Редактирование полей схемы документа

    {/if}
{/block}

{block 'layout_content'}

    {if $oDocumentScheme}
        <div>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="{$oDocumentScheme->getEditUrl()}" class="nav-link">Основное</a>
                </li>
                <li class="nav-item">
                    <a href="{$oDocumentScheme->getEditUrlFields()}" class="nav-link">Поля формы</a>
                </li>
                <li class="nav-item">
                    <a href="{$oDocumentScheme->getEditUrlFieldsAdd()}" class="nav-link">Добавление нового поля</a>
                </li>
            </ul>
        </div>
    {/if}


            <form action="" method="post" enctype="multipart/form-data" id="form-field">
                <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
                <input type="hidden" name="document_scheme_id" value="{$oDocumentScheme->getId()}" />

                <fieldset>
                    <legend>Поле</legend>

                    <div class="form-group row required">
                        <label class="col-sm-2 col-form-label">Название поля <sup>*</sup></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="{$_aRequest.name}" placeholder="Название поля" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="input-type">Тип</label>
                        <div class="col-sm-10">
                            <select name="type" id="input-type" {$_aRequest} onchange="ls.document_fields.changeFieldType($(this))" class="form-control">
                                    <option {if $_aRequest.type == 'select'}selected{/if} value="select">Список</option>
                                    <option {if $_aRequest.type == 'radio'}selected{/if}  value="radio">Переключатель</option>
                                    <option {if $_aRequest.type == 'checkbox'}selected{/if}  value="checkbox">Флажок</option>
                                    <option {if $_aRequest.type == 'text'}selected{/if}  value="text">Текст</option>
                                    <option {if $_aRequest.type == 'textarea'}selected{/if}  value="textarea">Текстовая область</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="input-sort-order">Автозаполнение</label>
                        <div class="col-sm-10">
                            <select name="code" class="form-control">
                                <option value="">Не использовать </option>
                                <option {if $_aRequest.code == 'phone'}selected{/if} value="phone">Телефон</option>
                                <option {if $_aRequest.code == 'mail'}selected{/if}  value="mail">Почта</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="input-sort-order">Сортировка</label>
                        <div class="col-sm-10">
                            <input type="text" name="sorting" value="{$_aRequest.sorting}" placeholder="Опция" id="input-sort-order" class="form-control">
                        </div>
                    </div>
                </fieldset>
                <fieldset class="js-fieldValue">
                    <legend>Значение</legend>
                    <table id="field-value" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <td class="text-left required">Значение опции</td>
                            <td class="text-right">Порядок сортировки</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        {$lastRow = 0}
                        {foreach $_aRequest.field_value as $row=>$aValue}
                            {$lastRow = $row}
                            <tr id="field-value-row{$row}">
                                <td class="text-center"><input type="hidden" name="field_value[{$row}][field_value_id]" value="{$aValue.id}" />
                                    <input type="text" name="field_value[{$row}][name]" value="{$aValue.name}" placeholder="Значение поля" class="form-control" />
                                </td>
                                <td class="text-right"><input type="text" name="field_value[{$row}][sorting]" value="{$aValue.sorting}" placeholder="Сортировка" class="form-control" /></td>
                                <td class="text-right"><button type="button" onclick="$('#field-value-row{$row}').remove();" data-toggle="tooltip" title="Удалить" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                            </tr>
                        {/foreach}
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="text-right"><button type="button" onclick="ls.document_fields.addfieldValue();" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Добавить">Новое</button></td>
                        </tr>
                        </tfoot>
                    </table>
                </fieldset>

                <br>
                <button type="submit" class="btn btn-primary" name="submit_add_field" >Сохранить</button>

            </form>

    <script>
        $(function () {
            $('#input-type').change();
            ls.document_fields.field_value_row = {$lastRow+1};
        })
    </script>
{/block}
