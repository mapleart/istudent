{**
 * Главная
 *}
{extends 'layouts/layout.map.tpl'}
{block 'layout_page_title'}
   Спортивные залы
{/block}
{block 'layout_page_title_help'}
    Данный раздел позволяет найти ближайший спортзал, получить информацию о его работе, оставить заявку на посещение.
{/block}

{block 'layout_content'}
   <script >

       function initMap() {
           ls.goolemap.createViewMap({ModuleMaps::TYPE_GYM})
       }
   </script>

   <div id="googleMap-view" style="height: 400px; width: 100%;"></div>

   <input type="hidden" value="{$iCityActive}" id="mapInput-cityDefault">
   <input type="hidden" value="{$iCategoryActive}" id="mapInput-categoryDefault">
   <input type="hidden" value="{$iSeqrchActive}" id="mapInput-searchDefault">

   <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
   <script src="https://maps.googleapis.com/maps/api/js?key={$oConfig->Get('plugin.gmappost.google_api_key')}&libraries=places&callback=initMap" async defer></script>


{/block}
