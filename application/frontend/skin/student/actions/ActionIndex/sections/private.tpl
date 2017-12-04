{**
 * Главная
 *}
{extends 'layouts/layout.map.tpl'}

{block 'layout_page_title'}
    Основная информация
{/block}

{block 'layout_content'}
    <div class="form-row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Фамилия:</label>
                <input type="text" name="user['last_name']" value="{$oUserCurrent->getLastName()}" readonly class="form-control" >
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Имя:</label>
                <input type="text" name="user['first_name']"  value="{$oUserCurrent->getFirstName()}" readonly class="form-control" >
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label> Отчество:</label>
                <input type="text" name="user['parent_name']"  value="{$oUserCurrent->getParentName()}" readonly class="form-control" >
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Група:</label>
                {if $group = $oUserCurrent->getGroup()}
                    <input type="text" name="user['group_name']" value="{$group->getName()}" readonly class="form-control" >

                {/if}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Номер зачетной книжки:</label>
                <input type="text" name="user['card_number']"  value="{$oUserCurrent->getCardNumber()}" readonly class="form-control" >
            </div>
        </div>
    </div>


    <br>
    <br>

    <h1 class="page-header" style="font-size: 18px; margin-bottom: 4px; margin-bottomЖ 5px; font-weight: bold; color: #16213B;">
        Учебные корпуса
    </h1>

    <script >

        function initMap() {
            ls.goolemap.createViewMap({ModuleMaps::TYPE_CORPUS})
        }
    </script>

    <div id="googleMap-view" style="height: 400px; width: 100%;"></div>

    <input type="hidden" value="{$iCityActive}" id="mapInput-cityDefault">
    <input type="hidden" value="{$iCategoryActive}" id="mapInput-categoryDefault">
    <input type="hidden" value="{$iSeqrchActive}" id="mapInput-searchDefault">

    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={$oConfig->Get('plugin.gmappost.google_api_key')}&libraries=places&callback=initMap" async defer></script>

{/block}