{include file="./user.tpl"}

{function sender }          {* short-hand *}
    {$oSender=$data['item']}

    {if $oSender}
        {call name=user data=['item'=>$oSender]}
    {/if}
{/function}

