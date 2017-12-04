{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Управление метками на карте
{/block}

{block 'layout_content'}

    <div class="">
        <a href="{router page='admin'}maps/add" class="btn btn-primary">Добавить новый объект</a>
    </div>
    <div id="mapList">
        <div id="toolbar">
        </div>
        <table class="js-mapList-table"
               data-show-toolbar="false"
               data-show-columns="false"
               data-sort-name="id"
               data-sort-order="asc"
               data-toolbar="#toolbar"
               data-search="true"

               data-show-refresh="false"
               data-show-toggle="false"
               data-show-export="false"
               data-show-pagination-switch="false"

               data-detail-view="false"
               data-minimum-count-columns="2"
               data-pagination="true"
               data-id-field="user_id"
               data-page-list="[10, 25, 50, 100, ALL]"
               data-page-size="25"
               data-show-footer="false"
               data-side-pagination="server"
        >
            <thead>
            <tr>

                <th data-field="id" data-sortable="true" >#ID</th>
                <th data-field="title" data-sortable="true">Название</th>
                <th data-field="description" data-sortable="true">Описание</th>
                {*<th data-field="category_id" data-sortable="true" data-formatter="formatterCategory">Категория</th>*}

                <th data-field="location" data-sortable="true" data-formatter="formatterLocation">Коорд.</th>
                <th data-field="actions" data-formatter="formatterAction" >Действие</th>

            </tr>
            </thead>
        </table>
    </div>

    <script>

        var categories = {json var=$aCategories};
        var categoriesTitle = {json var=$aLang.plugin.sfishing.form.categories};

        function formatterCategory(value, row, index) {
            var type = categories[row['category_id']];
            return categoriesTitle[type];
        }
        function formatterLocation (value, row, index) {
            return row['lat']+', '+row['lng'];
        }

        function formatterAction (value, row, index) {
            return '<a href="'+row['edit_url']+'" class="btn btn-primary">Редактировать</a> <a href="#" data-map-id="'+row['id']+'" class="btn btn-danger js-map-remove">Удалить</a>';
        }

        $(function () {



            //  инициализируем таблицу
            var $table = $('.js-mapList-table').first();
            $table.bootstrapTable({
                url: aRouter.ajax + '/ajaxmapList',
                queryParams: function (params) {
                    params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
                    params['moderation_type']=$('input[type=radio][name=moderation_type]:checked').val();

                    return params;
                }
            });

            $('input[type=radio][name=moderation_type]').change(function() {
                $table.bootstrapTable('refresh', {
                    moderationTypeRef: this.value
                });
            });


            $(document).on('click', '.js-remove-table-event', function (e) {

            });


            $(document).on('click', '.js-map-remove', function (e) {
                if(!confirm('Вы действительно хотите удалить?')) return false;
                e.preventDefault();
                var target = $(this);


                ls.goolemap.deleteLocation(target.data('map-id'), function () {
                    $table.bootstrapTable('refresh', {

                    });
                });


            });
        });
    </script>
{/block}
