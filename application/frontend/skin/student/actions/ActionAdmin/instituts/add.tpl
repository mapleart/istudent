{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Управление категориями
{/block}

{block 'layout_content'}

    <form action="{router page='admin'}instituts/add/" method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <input type="hidden" name="id" value="{$_aRequest.id}" />


        <div class="form-group">
            <label>Название института</label>
            <input type="text" name="name" value="{$_aRequest.name}" class="form-control" placeholder="ИГИН" />
        </div>

        <div class="form-group">
            <label>Номер корпуса</label>
            <input type="number" name="number" value="{$_aRequest.number}" class="form-control" placeholder="3" />
        </div>

        <div class="form-group">
            <label>Адрес</label>
            <input type="text" name="adress" value="{$_aRequest.adress}" class="form-control" placeholder="ул. 50 лет октября, 86" />
        </div>
        {*<div class="form-group">
            <label>{$aLang.plugin.sfishing.form.category}</label>
            <select name="category_id" class="form-control">
                {foreach $aCategories as $key=>$sCategory}
                    <option value="{$key+1}" {if $_aRequest.category_id == ($key+1)}selected="selected"{/if}>{$aLang.plugin.sfishing.form.categories[$sCategory]}</option>
                {/foreach}
            </select>
        </div>*}
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description"  class="form-control" placeholder="">{$_aRequest.description}</textarea>
        </div>




        <input type="submit" value="Сохранить" name="submit_add" class="btn  btn-primary" />

    </form>

    <style>
        #googleMap-input {
            width: 100%;
            height: 500px;
        }
    </style>
{/block}
