/**
 * Инициализации модулей
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

jQuery(document).ready(function ($) {
    // Хук начала инициализации javascript-составляющих шаблона
    ls.hook.run('ls_template_init_start', [], window);

    /**
     * Иниц-ия модулей ядра
     */
    ls.init({
        production: false
    });

    ls.dev.init({});

    /**
     * Notification
     */
    ls.notification.init();

    /**
     * Form validate
     */
    $('.js-form-validate').parsley();

    /**
     * Modals
     */




    $('[data-toggle="main-menu"]').click(function () {
        var $item = $(this).parent();
        if($item.hasClass('open')){
            return false;
        }


        var $open_menu = $('.nav-item.open .nav-item-sub');
        $open_menu.parent().removeClass('open');
        $open_menu.slideUp('fast', function () {
            $(this).parent().removeClass('open');
        });

        var $menu =$item.find('.nav-item-sub');
        $menu.slideDown('fast', function () {
            $(this).parent().addClass('open');
        });
        return false;
    });

    /**
     * Авторизация/регистрация
     */
    ls.auth.init();







    var $table = $('.js-tableInstituts').first();

    $table.bootstrapTable({
        url: aRouter.admin + 'ajax/instituts/list/',
        locale: 'ru-RU',
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function (params) {
            params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
            return params;
        }
    });



    var $table = $('.js-tableGroups').first();

    $table.bootstrapTable({
        url: aRouter.admin + 'ajax/groups/list/',
        locale: 'ru-RU',
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function (params) {
            params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
            return params;
        }
    });

    var $table = $('.js-tableDocuments').first();

    $table.bootstrapTable({
        url: aRouter.admin + 'ajax/documents/list/',
        locale: 'ru-RU',
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        queryParams: function (params) {
            params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
            return params;
        }
    });



});