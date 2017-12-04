{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {if !$oDocumentScheme}
        Добавление схемы документа
    {else}
        Редактирование схемы документа

    {/if}
{/block}

{block 'layout_content'}

    {if $oDocumentScheme}
        <div>
            <ul class="nav nav-pills">
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

            </ul>
        </div>
    {/if}

    <form action="" method="post" enctype="multipart/form-data" id="form-field">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                    <td class="text-left">
                        Имя
                    </td>
                    <td class="text-right">
                        Сортировка
                    </td>
                    <td class="text-right">
                        Действия
                    </td>
                </tr>
                </thead>
                <tbody>
                {$aSelected = $_aRequest.selected}
                {if !$_aRequest.selected}
                    {$aSelected= []}
                {/if}
                {if $fields}
                    {foreach $fields as $field}
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="selected[]" value="{$field->getId()}"  {if $field->getId()|in_array:$aSelected} checked="checked" {/if}  />
                            </td>
                            <td class="text-left">{$field->getName()}</td>
                            <td class="text-right">{$field->getSorting()}</td>
                            <td class="text-right"><a href="{$field->getEditUrl()}" data-toggle="tooltip" title="Редактировать" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                        </tr>
                    {/foreach}
                {else}
                    <tr>
                        <td class="text-center" colspan="4">Полей нет</td>
                    </tr>
                {/if}
                </tbody>
            </table>

        </div>

        <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="confirm('Данное действие необратимо. Вы уверены?') ? $('#form-field').submit() : false;" data-original-title="Удалить"><i class="fa fa-trash-o"></i></button>

    </form>

{/block}
