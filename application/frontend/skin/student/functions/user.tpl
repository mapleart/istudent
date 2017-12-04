{function user }          {* short-hand *}
    {$oUser=$data['item']}
    {if $oUser}
        <a href="{$oUser->getUserWebPath()}" class="user-mini">{$oUser->getFio()}</a>
    {/if}
{/function}