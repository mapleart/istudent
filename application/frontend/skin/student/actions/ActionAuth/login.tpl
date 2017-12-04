{**
 * Страница входа
 *}

{extends 'layouts/layout.auth.tpl'}




{block 'layout_content'}
    {component 'auth' template='login' showExtra=true}
{/block}