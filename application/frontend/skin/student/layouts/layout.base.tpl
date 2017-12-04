{**
 * Основной лэйаут
 *
 * @param string  $layoutNavContent         Название навигации
 * @param string  $layoutNavContentPath     Кастомный путь до навигации контента
 * @param string  $layoutShowSystemMessages Показывать или нет системные сообщения
 *}

{extends './layout.tpl'}

{block 'layout_options' append}
    {$layoutShowSidebar = $layoutShowSidebar|default:true}
    {$layoutShowSystemMessages = $layoutShowSystemMessages|default:true}
{/block}

{block 'layout_head_styles' append}
    <!-- Custom Fonts -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
{/block}

{block 'layout_head' append}
    {* Получаем блоки для вывода в сайдбаре *}
    {if $layoutShowSidebar}
        {show_blocks group='right' assign=layoutSidebarBlocks}

        {$layoutSidebarBlocks = trim( $layoutSidebarBlocks )}
        {$layoutShowSidebar = !!$layoutSidebarBlocks && $layoutShowSidebar}
    {/if}
{/block}

{block 'layout_body'}

    {**
     * Юзербар
     *}

    <!-- Page Content -->
    <div class="role-main-container role-main js-root-container clearfix">


        <div class="role-wrapper">

            <div class="role-content">
                <div class="content-fix">

                    <div class="d-sm-none mb-15" style=" margin: 0 -5px;  background: #efefef; ">
                        <div class="" data-toggle="navbar" style="line-height: 20px; font-size: 18px; padding: 15px; display: inline-block; color: #000; border-right: 1px solid #d9d9d9; cursor: pointer; "><i class="ion-navicon-round"></i></div>
                        <div class="" data-toggle="navbar" style="line-height: 20px; font-size: 18px; padding: 15px; display: inline-block; color: #000;  cursor: pointer; padding-left: 25px; "><i class="ion-ios-arrow-thin-left"></i> Меню</div>
                    </div>

                    {hook run='content_begin'}

                    <div class="mb-2">
                        {* Основной заголовок страницы *}
                        {block 'layout_page_title' hide}
                            <h1 class="page-header" style="font-size: 18px; margin-bottom: 4px; font-weight: bold; color: #16213B;">
                                {$smarty.block.child}
                            </h1>
                        {/block}
                        {block 'layout_page_title_help' hide}
                            <div class="page-header-help" style="font-size: 14px; color: #25345e;">
                                {$smarty.block.child}
                            </div>
                        {/block}
                    </div>


                    {block 'layout_content_header'}
                        {* Системные сообщения *}
                        {if $layoutShowSystemMessages}
                            {if $aMsgError}
                                {component 'alert' text=$aMsgError mods='error' close=true}
                            {/if}

                            {if $aMsgNotice}
                                {component 'alert' text=$aMsgNotice close=true}
                            {/if}
                        {/if}
                    {/block}

                    {block 'layout_content'}{/block}


                    <!-- Footer -->
                    {*<footer>
                        {block 'layout_footer'}
                            {hook run='footer_begin'}
                            <div class="row">
                                <div class="col-lg-12">
                                    <p>Copyright &copy; Your Website {date('Y')}</p>
                                </div>
                            </div>
                        {/block}
                    </footer>*}

                    {hook run='content_end'}
                </div>
            </div>


            {**
             * Сайдбар
             * Показываем сайдбар
             *}
            {*if $layoutShowSidebar}
                <div class="col-md-4">
                    {$layoutSidebarBlocks}
                </div>
            {/if*}
        </div>

        <div class="role-navbar">


            <div>
                <div class="logo">
                    <a href="{router page='/'}">Я.{if $oUserCurrent->isTutor()}Староста{else}Студент{/if}</a>
                </div>

                <div>
                    <img src="{cfg 'path.skin.web'}/images/avatar.jpg" style="width: 60px; display: block; margin: 10px auto 5px; height: 60px; -webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;">
                </div>
                <ul class="nav nav-pills" style="text-align: center; display: block;">
                    <li class="nav-item dropdown" style="float: none; display: inline-block;">
                        <a class="nav-link dropdown-toggle" href="{router page="/"}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {$oUserCurrent->getDisplayName()}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            {if $oUserCurrent->isAdmin()}
                                <a href="{router page='admin'}"  class="dropdown-item">Управление</a>
                            {/if}
                            <a href="{router page='auth/logout'}?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="dropdown-item">Выход</a>
                        </div>
                    </li>

                </ul>
            </div>

            <div class="main-nav">
                <div class="nav-item  open">
                    <a href="#" data-toggle="main-menu"><i class="ion-person"></i> Личные данные <i class="nav-trigger ion-chevron-down"></i></a>
                    <div class="nav-item-sub">
                        <div class="nav-item {if $sActive='index'}active{/if}">
                            <a href="{router page='/'}">Основная информация </a>
                        </div>
                        {*<div class="nav-item">
                            <a href="#">Профиль подготовки</a>
                        </div>*}

                        <div class="nav-item {if $sAction == ''}active{/if}">
                            <a href="{router page='notification'}" >Уведомления {if $iCountNotifications > 0}<span class="badge badge-primary pull-right">{$iCountNotifications}</span>{/if}</a>
                        </div>

                        <div class="nav-item {if $sAction == 'education' && $sEvent=='documents'}active{/if}">
                            <a href="{router page='education'}documents/">Документы</a>
                        </div>

                    </div>
                </div>

                <div class="nav-item open">
                    <a href="#"><i class="ion-android-bookmark"></i> Запись на секцию</a>
                    <div class="nav-item-sub">
                        <div class="nav-item {if $sAction == 'sport' && $sEvent == 'gyms'}active{/if}">
                            <a href="{router '/'}sport/gyms">Спортивные залы</a>
                        </div>
                        <div class="nav-item {if $sAction == 'culture' && $sEvent == 'dance'}active{/if}">
                            <a href="{router page='culture'}dance/">Танцевальная студия</a>
                        </div>
                        <div class="nav-item {if $sAction == 'culture' && $sEvent == 'vocal'}active{/if}" >
                            <a href="{router page='culture'}vocal/" class="">Вокальная студия</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- /.container -->

{/block}
