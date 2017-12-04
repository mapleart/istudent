<div class="role-notificationItem media {block name="classes"}{/block}  {if $oNotification->getIsNew()}new{/if}" style="padding: 10px; border-bottom: 1px solid #eee; {if $oNotification->getIsNew()}background: #dbd7ff;{/if}" data-id="{$oNotification->getId()}">
    <div class="d-flex mr-3">
        {if $oNotification->getIsAnonymous()}
            <img src="{$aTemplateWebPathPlugin['notification']}images/anonymous.png" title="{$aLang.plugin.notification.user_anonimous}">
        {else}

        {/if}

    </div>

    <div class="media-body">
        <div class="d-flex w-100 justify-content-between">
            <p class="mb-0">{block name="text"}{/block}</p>
           {* <small><a href="#" data-toggle="notification" data-id="{$oNotification->getId()}"> <i class="ion-close"></i> </a></small> *}
        </div>

        <div class="notification-btns">{block name="btns"}{/block}</div>
        <p class="help-block" style="margin: 0; font-size: 12px;">{date_format date=$oNotification->getAddDate() format="j F Y H:i"}</p>


        {block name="right"}{/block}
    </div>
</div>
