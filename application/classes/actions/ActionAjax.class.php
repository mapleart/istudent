<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Экшен обработки ajax запросов
 * Ответ отдает в JSON фомате
 *
 * @package actions
 * @since 1.0
 */
class ActionAjax extends Action
{
    protected $oUserCurrent=null;
    /**
     * Инициализация
     */
    public function Init()
    {
        $this->oUserCurrent=$this->User_GetUserCurrent();
        /**
         * Устанавливаем формат ответа
         */
        $this->Viewer_SetResponseAjax('json');
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {

        $this->AddEvent('ajaxmaplist', 'EventAjaxMapList');
        $this->AddEvent('getmarkers', 'EventGetMarkers');
        $this->AddEvent('get-location-info', 'EventGetLocationInfo');
        $this->AddEvent('get-location-city', 'EventGetLocationCity');
        $this->AddEvent('remove-map-location', 'EventRemoveMarker');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */



    /**
     *
     * @return string
     */
    public function EventAjaxMapList()
    {
        $this->Viewer_SetResponseAjax();

        if (!$this->oUserCurrent || !$this->oUserCurrent->isAdministrator()) {
            $this->Message_AddError($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            return Router::Action('error');
        }

        $iPerPage = getRequestStr('limit');
        $iPage = (getRequestStr('offset') / $iPerPage) + 1;

        /**
         * По какому полю сортировать
         */
        $sOrder = 'id';
        if (getRequest('sort')) {
            $sOrder = getRequestStr('sort');
        }



        /**
         * В каком направлении сортировать
         */
        $sOrderWay = 'desc';
        if (getRequest('order')) {
            $sOrderWay = getRequestStr('order');
        }
        $aFilter = array(
            '#order'=>array($sOrder=>$sOrderWay, 'date_edit'=>'desc'),
            '#page'=>array($iPage, $iPerPage)
        );


        $type=getRequest('moderation_type', 'all');

        /* if( $type == 'filled'){
             $aFilter['moderation']=PluginHelper_ModuleEvent::MODERATION_FILLED;
         }
         elseif( $type == 'error'){
             $aFilter['moderation']=PluginHelper_ModuleEvent::MODERATION_ERROR;
         }elseif($type== 'successes') {
             $aFilter['moderation']=PluginHelper_ModuleEvent::MODERATION_SUCCESSES;
         }else{
             $aFilter['moderation IN'] = array(PluginHelper_ModuleEvent::MODERATION_FILLED, PluginHelper_ModuleEvent::MODERATION_ERROR);
         }*/


        $sSearchString = getRequestStr('search');
        if ($sSearchString) {
            $aFilter['#where']=array('title LIKE ? OR description LIKE ?' => array('%' . $sSearchString . '%', '%' . $sSearchString . '%'));

        }


        /**
         * Получаем список юзеров
         */
        $aResult = $this->Maps_GetMapItemsByFilter($aFilter);
        $aMaps = [];

        $aCopyFields = ['id', 'title', 'description', 'date_add', 'address', 'category_id', 'lat', 'lng', 'sorting'];
        foreach ($aResult['collection'] as $oEvent) {
            $aEvent = [];
            foreach ($aCopyFields as $sFieldName) {
                $aEvent[$sFieldName] = call_user_func([$oEvent, 'get' . func_camelize($sFieldName)]);
            }

            $aEvent['edit_url']=$oEvent->getEditUrl();

            $aMaps[] = $aEvent;
        }

        $this->Viewer_AssignAjax("rows", $aMaps);
        $this->Viewer_AssignAjax("total", $aResult['count']);

    }


    public function EventGetMarkers()
    {
        $sw_lat = getRequest('sw_lat');
        $sw_lon = getRequest('sw_lng');

        $ne_lat = getRequest('ne_lat');
        $ne_lon = getRequest('ne_lng');

        $sSearchString = getRequestStr('search');

        $sWhere = "
        (CASE WHEN {$sw_lat} < {$ne_lat} THEN lat BETWEEN {$sw_lat} AND {$ne_lat}
            ELSE lat BETWEEN {$sw_lat} AND 180 OR lat BETWEEN -180 AND {$ne_lat}
        END) 
        AND
        (CASE WHEN {$sw_lon} < {$ne_lon}
            THEN lng BETWEEN {$sw_lon} AND {$ne_lon}
            ELSE lng BETWEEN  {$sw_lon} AND 180 OR lng BETWEEN -180 AND {$ne_lon}
        END)
        {AND target_type = ?d}
        {AND category_id = ?d}
        {AND (title LIKE ? OR description LIKE ?)}
        
        ";

        // DBSIMPLE_SKIP
        $aFilter = array(
            '#where'=>array($sWhere=>array(
                getRequest('target_type')  ? getRequest('target_type') : DBSIMPLE_SKIP,
                getRequest('category_id') &&  getRequest('category_id') != 'all' ? getRequest('category_id') : DBSIMPLE_SKIP,
                $sSearchString ? ('%' . $sSearchString . '%'):DBSIMPLE_SKIP,
                $sSearchString ? ('%' . $sSearchString . '%'):DBSIMPLE_SKIP
            ))
        );
        /* if(getRequest('city_id') && getRequest('city_id') != 'all'){
             $aFilter['city_id IN'] = array(getRequest('city_id'));
         }

         if(getRequest('category_id')){
             $aFilter['category_id']=getRequest('category_id');
         }

         if ($sSearchString) {
             $aFilter['#where']=array(($posSql.' AND title LIKE ? OR description LIKE ?') => array('%' . $sSearchString . '%', '%' . $sSearchString . '%'));

         }else{
             $aFilter['#where']=array($posSql=>array());
         }/*/


        $aMaps=$this->Maps_GetMapItemsByFilter($aFilter);
        $aMapsNormal=array();
        foreach ($aMaps as $oMap){
            $aMapsNormal[$oMap->getId()]=array(
                'id'=>$oMap->getId(),
                'lat'=>$oMap->getLat(),
                'lng'=>$oMap->getLng(),
                'title'=>$oMap->getTitle(),

            );
        }

        $this->Viewer_AssignAjax('aMaps', $aMapsNormal);
    }


    public function EventGetLocationInfo()
    {
        if(!$oMap = $this->Maps_GetMapById(getRequest('id'))){
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));

        };

        $aMapsNormal=array();
        $aMapsNormal[$oMap->getId()]=array(
            'id'=>$oMap->getId(),
            'lat'=>$oMap->getLat(),
            'lng'=>$oMap->getLng(),
            'title'=>$oMap->getTitle(),

        );

        $this->Viewer_AssignAjax('aMaps', $aMapsNormal);



        $oViewer=$this->Viewer_GetLocalViewer();

        $oViewer->Assign('oMap',$oMap);
        $oViewer->Assign('oUserCurrent',$this->oUserCurrent);
        /**
         * Устанавливаем переменные для ajax ответа
         */
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch("ajax.location_info.tpl"));
        $this->Viewer_AssignAjax('name', $oMap->getTitle());
    }

    public function EventGetLocationCity()
    {


        if(!$oCity=$this->Geo_GetCityById(getRequest('city_id'))){
            return false;
        }

        $sAddress = $oCity->getNameRu();
        // if($oRegion = $oCity->getRegion()){
        //   $sAddress = $oRegion->getNameRu() . ' ' . $sAddress;
        //}
        if($oCountry = $oCity->getCountry()){
            $sAddress = $oCountry->getNameRu() . ' ' . $sAddress;
        }


        $iPerPage = (int)getRequestStr('limit', 100);
        $iPage = getRequestStr('page', 1);

        /**
         * По какому полю сортировать
         */
        $sOrder = 'sorting';
        $sOrderWay = 'asc';


        $sWhere = 'city_id = ?d 
        {AND category_id = ?d}
        { AND (title LIKE ? OR description LIKE ?) }';

        $sSearchString = getRequestStr('search');

        $aFilter = array(
            '#where'=>array($sWhere=>array(
                getRequest('city_id'),
                getRequest('category_id') && getRequest('category_id') != 'all' ? getRequest('category_id') : DBSIMPLE_SKIP,
                $sSearchString ? ('%' . $sSearchString . '%'):DBSIMPLE_SKIP,
                $sSearchString ? ('%' . $sSearchString . '%'):DBSIMPLE_SKIP
            )),
            '#order'=>array($sOrder=>$sOrderWay, 'date_edit'=>'desc'),
            '#page'=>array($iPage, $iPerPage)
        );

        /**
         * Получаем список юзеров
         */
        $aResult = $this->Maps_GetMapItemsByFilter($aFilter);


        $oViewer=$this->Viewer_GetLocalViewer();

        $oViewer->Assign('aMap',$aResult['collection']);
        $oViewer->Assign('oUserCurrent',$this->oUserCurrent);
        /**
         * Устанавливаем переменные для ajax ответа
         */
        $this->Viewer_AssignAjax('sText', $oViewer->Fetch(Plugin::GetTemplatePath(__CLASS__)."ajax.location_list.tpl"));
        $this->Viewer_AssignAjax('address', $sAddress);
        $this->Viewer_AssignAjax('cityName', $oCity->getNameRu());

    }

    public function EventRemoveMarker(){
        if(!$oEvent=$this->Maps_GetMapById(getRequest('id'))){
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return false;
        }
        if($oEvent->Delete()){
            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.sfishing.notice.remove_map'));

        }
    }
}