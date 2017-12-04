{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Заяки на долучение справок
{/block}

{block 'layout_content'}
    <div id="listDocumentsRequest">
        <div id="toolbar">
        </div>
        <table class="js-tableDocumentsRequest"
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
                <th data-field="link" data-sortable="false">Название</th>
                <th data-field="student" data-sortable="false">Студент</th>
                <th data-field="group" data-sortable="false">Группа</th>
                <th data-field="status" data-sortable="true">Статус</th>
                <th data-field="add_date" data-sortable="true">Дата добавления</th>
                <th data-field="actions" data-formatter="formatterAction" >Изменить статус</th>

            </tr>
            </thead>
        </table>
    </div>

    <script>
        var $table = $('.js-tableDocumentsRequest').first();


        $(document).on('click', '[data-toggle="request-status"]', function (e) {
            e.preventDefault();
            var target = $(this);

            var url = aRouter.admin + 'ajax/request-status/';
            var params = {
                id: target.data('id'),
                status: target.data('status')
            };
            ls.ajax.load(url, params, function (data) {
                if (data.bStateError) {
                    ls.msg.error(data.sMsgTitle,data.sMsg);
                } else {
                    ls.msg.notice(data.sMsgTitle,data.sMsg);
                    $table.bootstrapTable('refresh', {

                    });
                }

            })
        });


        $table.bootstrapTable({
            url: aRouter.admin + 'ajax/documents-request/list/',
            locale: 'ru-RU',
            method: 'POST',
            contentType: 'application/x-www-form-urlencoded',
            queryParams: function (params) {
                params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
                return params;
            }
        });



        function formatterAction (value, row, index) {
            var $aStatus = row['statuses_admin'];

            $sHtml = '';
            $.each($aStatus, function( key, val ) {
                $sHtml +='<a data-toggle="request-status" data-status="'+val.status+'" data-id="'+row['id']+'" href="">'+val.name+'</a> ';
            });
            return $sHtml;
        }
    </script>
{/block}
