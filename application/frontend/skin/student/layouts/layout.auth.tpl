{extends 'layouts/layout.base.tpl'}


{block 'layout_options' append}
    {$mods = "$mods auth"}
{/block}

{block 'layout_body'}



    <!-- Page Content -->
    <div class="role-miniContainer js-root-container">

        <div class="logo">
            <a href="{router page='/'}">Я.Студент</a>
        </div>
        <div class="role-miniWrapper">


            {hook run='content_begin'}

            {* Основной заголовок страницы *}
            {block 'layout_page_title' hide}
                <h1 class="page-header">
                    {$smarty.block.child}
                </h1>
            {/block}

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

            {hook run='content_end'}
        </div>



    </div>
    <!-- /.container -->
{/block}