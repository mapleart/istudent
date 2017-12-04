{include file="./user.tpl"}

{function profile }          {* short-hand *}
    {$oProfile=$data['item']}
    {if $oProfile}
        {call name=user data=['item'=>$oProfile]}
    {/if}
{/function}