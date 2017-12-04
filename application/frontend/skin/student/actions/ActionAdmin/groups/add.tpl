{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Управление группами
{/block}

{block 'layout_content'}

    <form action="{router page='admin'}groups/add/" method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <input type="hidden" name="id" value="{$_aRequest.id}" />


        <div class="form-group">
            <label>Название Группы</label>
            <input type="text" name="name" value="{$_aRequest.name}" class="form-control" placeholder="УТСб-14-1" />
        </div>
        <div class="form-group">
            <label>Расшифровка</label>
            <input type="text" name="name_full" value="{$_aRequest.name_full}" class="form-control" placeholder="Управление в технических системах" />
        </div>
        <div class="form-group">
            <label>Институт</label>
            <select class="form-control" name="institut_id">
                {foreach $aInstituts as $oInstitut}
                    <option value="{$oInstitut->getId()}">{$oInstitut->getName()}</option>
                {/foreach}
            </select>
        </div>

        <div class="form-group">
            <label>Староста</label>
            <select class="form-control" name="tutor_id">
                <option {if !$_aRequest.tutor_id}selected{/if}>Не выбрано</option>

                {foreach $aStudents as $oStudent}
                    <option {if $_aRequest.tutor_id == $oStudent->getId()}selected{/if} value="{$oStudent->getId()}">{$oStudent->getFio()}</option>
                {/foreach}
            </select>
        </div>



        <input type="submit" value="Сохранить" name="submit_add" class="btn  btn-primary" />

    </form>

{/block}
