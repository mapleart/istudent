{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}
{block 'layout_page_title'}
   {$oDocumentScheme->getName()}
{/block}
{block 'layout_page_title_help' }
    от {date_format date=$oDocument->getAddDate() format="j F Y"}


{/block}



{block 'layout_content'}
    <div class="" style="padding: 15px; background: #fff7d8; color: #000; border: 1px solid #dfcd9d">

        


        
        <h2 style="font-size: 15px;"> <strong style="font-weight: 900; ">{$oDocumentScheme->getName()}</strong></h2>

        <div class="form-group">
            <label>Фамилия:</label>
            {$oDocument->getUser()->getLastName()}
        </div>
        <div class="form-group">
            <label>Имя:</label>
            {$oDocument->getUser()->getFirstName()}
        </div>

        <div class="form-group">
            <label> Отчество:</label>
            {$oDocument->getUser()->getParentName()}
        </div>

        <div class="form-group">
            <label>Група:</label>
            {if $group = $oDocument->getUser()->getGroup()}
                {$group->getName()}

            {/if}
        </div>


        <div class="form-group">
            <label>Номер зачетной книжки:</label>
            {$oDocument->getUser()->getCardNumber()}
        </div>

        {$aValues = $oDocument->getValues()}
        {foreach $aValues as $oValue}
            {$oField = $oValue->getField()}
            <div class="form-group">
                {if oField}
                    <label>
                        {$oField->getName()}:
                    </label>
                {/if}

                {$oValue->getValue()}
            </div>
        {/foreach}

    </div>
{/block}
