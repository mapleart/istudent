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

    <form action="{router page='admin'}documents/add/" method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <input type="hidden" name="id" value="{$_aRequest.id}" />


        <div class="form-group">
            <label>Название документа</label>
            <input type="text" name="name" value="{$_aRequest.name}" class="form-control" placeholder="Справка о подтверждении обучения" />
        </div>

        <div class="form-group">
            <label>Описане</label>
            <textarea name="description" class="form-control">{$_aRequest.description}</textarea>
        </div>

        <input type="submit" value="Сохранить" name="submit_add" class="btn  btn-primary" />
    </form>

{/block}
