{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Управление категориями
{/block}

{block 'layout_content'}
    <div class="">
        <a href="{router page='admin'}instituts/add" class="btn btn-primary">Добавить новый объект</a>
    </div>

    <div id="listInstituts">
        <div id="toolbar">
        </div>
        <table class="js-tableInstituts"
               data-show-toolbar="false"
               data-show-columns="false"
               data-sort-name="id"
               data-sort-order="desc"
               data-toolbar="#toolbar"
               data-search="false"

               data-show-refresh="false"
               data-show-toggle="false"
               data-show-export="false"
               data-show-pagination-switch="false"

               data-detail-view="false"
               data-minimum-count-columns="2"
               data-pagination="true"
               data-id-field="id"
               data-page-list="[10, 25, 50, 100, ALL]"
               data-page-size="25"
               data-show-footer="false"
               data-side-pagination="server"
        >
            <thead>
            <tr>
                <th data-field="id" data-sortable="true">#ID</th>
                <th data-field="name" data-sortable="false">Название</th>
                <th data-field="number" data-sortable="false">Номер</th>
                <th data-field="adress" data-sortable="true">Адрес</th>
                <th data-field="actions" data-formatter="formatterAction" >Действие</th>

            </tr>
            </thead>
        </table>
    </div>

    <script>
        function formatterAction (value, row, index) {
            return '<a href="'+row['edit_url']+'" class="btn btn-primary">Редактировать</a> <a href="#" data-map-id="'+row['id']+'" class="btn btn-danger js-institut-remove">Удалить</a>';
        }
    </script>
{/block}

