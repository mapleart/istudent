{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Администрирование
{/block}

{block 'layout_content'}
    <div class="">
        <a href="{router page='admin/moderate/documents'}">Заявки на справки</a>
    </div>

    <div class="">
        <a href="{router page='admin/documents/list'}">Управление шаблонами справок</a>
    </div>



    <div class="">
        <a href="{router page='admin/maps/list'}">Метки на карте</a>
    </div>

    <hr>

    <div class="">
        <a href="{router page='admin/instituts/list'}">Управление институтами</a>
    </div>
    <div class="">
        <a href="{router page='admin/groups/list'}">Управление группами</a>
    </div>
{/block}