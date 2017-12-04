{**
 * Восстановление пароля.
 * Пароль отправлен на емэйл пользователя.
 *}

{extends 'layouts/layout.auth.tpl'}

{block 'layout_page_title'}
	{$aLang.auth.reset.title}
{/block}

{block 'layout_content'}
	{$aLang.auth.reset.notices.success_send_password}
{/block}