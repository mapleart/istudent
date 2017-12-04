{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Регистрация студентов
{/block}

{block 'layout_content'}

<form action="" method="POST" enctype="multipart/form-data">
    {component 'field' template='hidden.security-key'}

    <div class="form-group">
        <label>Введите E-mail:</label>
        <input name="invite_mail" class="form-control" placeholder="e-mail">
    </div>

    <div class="form-row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Фамилия:</label>
                <input type="text" name="last_name" value="" placeholder="Иванов"  class="form-control" >
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="first_name"  value="" placeholder="Иван"  class="form-control" >
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label> Отчество:</label>
                <input type="text" name="parent_name" placeholder="Иванович" value=""  class="form-control" >
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Группа:</label>

                <select name="group_id" class="form-control">
                    {foreach $aGroup as $group}
                        <option value="{$group->getId()}">{$group->getName()}</option>
                    {/foreach}
                </select>


            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Номер зачетной книжки:</label>
                <input type="text" name="card_number" placeholder="14-01-001620" value=""  class="form-control" >
            </div>
        </div>
    </div>

    {* Кнопки *}
    {component 'button' mods='primary' text='Отправить'}
</form
{/block}
