{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}
{block 'layout_page_title'}
    Отправить запрос:<br> {$oDocumentScheme->getName()}
{/block}
{block 'layout_content'}
    {include file="./menu.tpl"}

    <form action=""  method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

        <div class="form-row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Фамилия:</label>
                    <input type="text" name="user['last_name']" value="{$oUserCurrent->getLastName()}" readonly class="form-control" >
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Имя:</label>
                    <input type="text" name="user['first_name']"  value="{$oUserCurrent->getFirstName()}" readonly class="form-control" >
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label> Отчество:</label>
                    <input type="text" name="user['parent_name']"  value="{$oUserCurrent->getParentName()}" readonly class="form-control" >
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Група:</label>
                    {if $group = $oUserCurrent->getGroup()}
                        <input type="text" name="user['group_name']" value="{$group->getName()}" readonly class="form-control" >

                    {/if}
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Номер зачетной книжки:</label>
                    <input type="text" name="user['card_number']"  value="{$oUserCurrent->getCardNumber()}" readonly class="form-control" >
                </div>
            </div>
        </div>

        {foreach $oDocumentScheme->getFields() as $field}
            {if $field->getType() == 'text'}
                <div class="form-group">
                    <label >{$field->getName()}:</label>
                    <input type="text"  name="document_field[{$field->getId()}]" class="form-control">
                </div>
            {/if}

            {if $field->getType() == 'select'}
                <div class="form-group">
                    <label>{$field->getName()}:</label>
                    <select class="form-control" name="document_field[{$field->getId()}]">
                        {foreach $field->getValues() as $value}
                            <option value="{$value->getId()}">{$value->getName()}</option>
                        {/foreach}
                    </select>
                </div>
            {/if}
        {/foreach}

        <button type="submit" name="add_document" class="btn btn-primary">Отправить</button>
    </form>
{/block}