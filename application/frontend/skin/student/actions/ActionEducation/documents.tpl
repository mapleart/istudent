{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}
{block 'layout_page_title'}
   Документы
{/block}
{block 'layout_page_title_help'}
    Данный раздел позволяет оформить заявление или запрос на получение справки.
{/block}


{block 'layout_content'}
    {*include file="./menu.tpl"*}

    <div class="card  mb-4">
       <div class="card-header">
          Отправить запрос
       </div>
       <div class="card-body">
             <select class="form-control js-chousen-documents" >
                <option value="">-Выберите шаблон-</option>
                 {foreach $aDocuments as $document}
                    <option value="{$document->getId()}">{$document->getName()}</option>
                 {/foreach}
                 <p class="help"></p>
             </select>
          <script>
             $('.js-chousen-documents').change(function () {
                 if($(this).val() != ''){
                     window.location = aRouter.education+'documents/request/'+$(this).val();
                 }
             })
          </script>

       </div>
    </div>

    <div>
        <div class="card  mb-4" >
            <div class="card-header">
                Мои документы
            </div>

            <ul class="list-group list-group-flush" >
                {foreach $aDocumentsUser as $documentUser}
                    {$documentScheme=$documentUser->getScheme()}
                    <li class="list-group-item">

                        <p><a href="{$documentUser->getUrlFull()}">{$documentScheme->getName()}</a>  {$documentUser->getBadge()}</p>
                        {*<div>
                            {$aValues = $documentUser->getValues()}
                            {foreach $aValues as $oValue}
                                {$oField = $oValue->getField()}
                                <p>
                                    {if oField}
                                        {$oField->getName()}:
                                    {/if}
                                    {$oValue->getValue()}
                                </p>
                            {/foreach}
                        </div>*}
                    </li>

                {/foreach}
            </ul>
        </div>


    </div>
{/block}
