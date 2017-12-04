{extends 'Component@email.email'}

{block 'content'}
    Статус вашей справки был изменен на {$oDocument->getStatusText()}
{/block}