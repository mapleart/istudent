
{$sText=''}
{$sBtns=''}
{$sAdditionaItem=''}

{$oNotification->View()}
{$aData = $oNotification->getData()}

{include file="../../../../functions/document.tpl"}
{capture assign="sItemTemplate"}{call name=document data=['item'=>$aData.target]}{/capture}

{capture assign="sText"}
    {$iStatus = $oNotification->getTargetSubtype()}
    Статус вашей заявки на справку  "{$sItemTemplate}" изменен:
    <strong>{if $iStatus == ModuleDocument::DOCUMENT_NEW}Отправлено{elseif $iStatus == ModuleDocument::DOCUMENT_PROCESS}В обработке{elseif $iStatus == ModuleDocument::DOCUMENT_AGREEMENT}На согласовании{elseif $iStatus == ModuleDocument::DOCUMENT_SUCCESS}Справка готова{elseif $iStatus == ModuleDocument::DOCUMENT_REJECT}Отказ{/if}</strong>
{/capture}



{include file="./document.tpl" sText=$sText}
