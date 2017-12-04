{extends 'layouts/layout.map.tpl'}

{block 'layout_page_title'}
    Уведомления (<span class="js-notificationCount">{$iCountNotification}</span>)
{/block}

{block 'layout_content'}

    <div class="role-notification-list-wrap clearfix">

        {if count($aNotifications)}
            {foreach $aNotifications as $oNotification}


                {$oSender=$oNotification->getSender()} {*Отправитель*}
                {$oRecipient=$oNotification->getRecipient()} {*Получатель*}


                {* шаблон отправителя *}
                {include file="../../functions/sender.tpl"}
                {capture assign="sSenderTemplate"}{call name=sender data=['item'=>$oSender]}{/capture}

                {* шаблон для своего типа *}
                {$sTargetType = $oNotification->getTargetType()}
                {include file="./types/$sTargetType/main.tpl" oSender=$oSender oRecipient=$oRecipient oNotification=$oNotification sSenderTemplate=$sSenderTemplate}


                {*$oNotification->View()*}
            {/foreach}

        {else}
            <div class="alert alert-warning" role="alert">
                Новых уведомлений нет
            </div>
        {/if}


    </div>
{/block}


<style>
    .role-notificationItem {
        padding: 10px;
        border-radius: 5px;
        background-color: #fff;
        border: 1px solid #e1e4e8;
    }
    .role-notificationItem.new {
        background-color: #d0f5b6;
        border: 1px solid #9fec7d;
    }
</style>
{include file='footer.tpl'}


