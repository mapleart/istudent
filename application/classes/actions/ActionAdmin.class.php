<?php

class ActionAdmin extends Action
{

    public function Init()
    {
        /**
         * Если нет прав доступа - перекидываем на 404 страницу
         */
        if(!$this->User_IsAuthorization() or !$oUserCurrent=$this->User_GetUserCurrent() or !$oUserCurrent->isAdministrator()) {
            return parent::EventNotFound();
        }
        $this->SetDefaultEvent('index');

    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index',  'EventIndex');
        $this->AddEventPreg('/^invite$/i',  'EventInvite');

        $this->AddEventPreg('/^ajax$/i','/^request-status$/i',  'AjaxEventRequestStatus');


        $this->AddEventPreg('/^instituts$/i', '/^list/i', 'EventInstitutsList');

        $this->AddEventPreg('/^instituts$/i', '/^add$/i', 'EventInstitutsAdd');
        $this->AddEventPreg('/^instituts$/i', '/^edit$/i', '/^\d+$/i', 'EventInstitutsEdit');

        $this->AddEventPreg('/^ajax$/i','/^instituts$/i', '/^list/i', 'AjaxEventInstitutsList');

        ///
        ///

        $this->AddEventPreg('/^groups$/i', '/^list/i', 'EventGroupsList');

        $this->AddEventPreg('/^groups$/i', '/^add$/i', 'EventGroupsAdd');
        $this->AddEventPreg('/^groups$/i', '/^edit$/i', '/^\d+$/i', 'EventGroupsEdit');

        $this->AddEventPreg('/^ajax$/i','/^groups$/i', '/^list/i', 'AjaxEventGroupsList');

        ////

        $this->AddEventPreg('/^maps$/i', '/^list$/i', 'EventMapList');
        $this->AddEventPreg('/^maps$/i', '/^add$/i', 'EventMapAdd');
        $this->AddEventPreg('/^maps$/i', '/^edit$/i', '/^\d+$/i', 'EventMapEdit');

        ///
        ///
        $this->AddEventPreg('/^ajax$/i','/^documents$/i', '/^list/i', 'AjaxEventDocumentsList');

        $this->AddEventPreg('/^documents$/i', '/^list$/i', '/^$/i', 'EventDocumentSchemeList');
        $this->AddEventPreg('/^documents$/i', '/^add$/i', '/^$/i', 'EventAddDocumentScheme');

        $this->AddEventPreg('/^documents$/i', '/^edit/i', '/^\d+$/i', '/^$/i', 'EventDocumentSchemeEdit');
        $this->AddEventPreg('/^documents$/i', '/^edit/i', '/^\d+$/i', '/^field/i', '/^\d+$/i', 'EventDocumentSchemeEditField');
        $this->AddEventPreg('/^documents$/i', '/^edit/i', '/^\d+$/i', '/^fields/i',  '/^$/i', 'EventAddDocumentSchemeFields');
        $this->AddEventPreg('/^documents$/i', '/^edit/i', '/^\d+$/i', '/^fields/i', '/^add/i',  '/^$/i', 'EventAddDocumentSchemeFieldAdd');
       // $this->AddEventPreg('/^documents$/i', '/^edit$/i', '/^\d+$/i', '/^fields$/i', '/^$/i', 'EventAdminOptions');


        /////
        ///
        $this->AddEventPreg('/^moderate$/i', '/^documents$/i', '/^$/i', 'EventModerateDocumentList');
        $this->AddEventPreg('/^ajax$/i','/^documents-request$/i', '/^list/i', 'AjaxEventDocumentsRequestsList');


    }

    public function EventIndex(){
        $this->SetTemplateAction('index');
    }

    protected function AjaxEventRequestStatus()
    {
        $this->Viewer_SetResponseAjax('json');
        if(!$oDocument = $this->Document_GetDocumentById(getRequest('id'))){
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return false;
        }

        $iStatus = getRequest('status');

        if(!in_array($iStatus, array( ModuleDocument::DOCUMENT_REJECT, ModuleDocument::DOCUMENT_SUCCESS, ModuleDocument::DOCUMENT_NEW, ModuleDocument::DOCUMENT_AGREEMENT, ModuleDocument::DOCUMENT_PROCESS))){
            $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
            return false;
        }

        $oDocument->setStatus($iStatus);
        if($oDocument->Update()){
            $oUser=$this->User_GetUserById($oDocument->getUserId());
            $this->Notify_Send(
                $oUser->getMail(),
                'document_request.tpl',
                'Новый статус для вашей справки '.$oDocument->getScheme()->getName(),
                array(
                    'sMailTo'   => $oUser->getMail(),
                    'oUserFrom' => $this->User_GetUserCurrent(),
                    'oDocument' => $oDocument,
                )
            );

            $aNotificationData=array(
                'target_type'=>'document',
                'target_subtype'=>$oDocument->getStatus(),
                'target_id'=>$oDocument->getId(),
                'meta'=>array(
                    'system'=>true,
                    'is_anonymous'=>true,
                )
            );
            $this->Notification_createNotification($this->User_GetUserCurrent(), $oUser, $aNotificationData);

            $this->Message_AddNoticeSingle('Статус успешно изменен');

            return true;
        }

    }
    protected function EventModerateDocumentList(){
        $this->SetTemplateAction('moderate/documents');
    }

    protected function AjaxEventDocumentsRequestsList(){
        $this->Viewer_SetResponseAjax('json');

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
            '#order'=>array($sOrder=>$sOrderWay),
            '#page'=>array($iPage, $iPerPage)
        );

        /**
         * Получаем список юзеров
         */
        $aResult = $this->Document_GetDocumentItemsByFilter($aFilter);
        $aResultItems = [];

        $aCopyFields = ['id', 'name', ];
        foreach ($aResult['collection'] as $oDocument) {
            $aGroups = [];
            foreach ($aCopyFields as $sFieldName) {
                $aGroups[$sFieldName] = call_user_func([$oDocument, 'get' . func_camelize($sFieldName)]);
            }

            if ($oScheme = $oDocument->getScheme()){
                $aGroups['name']=$oScheme->getName();
            }

            if($oUser = $this->User_GetUserById($oDocument->getUserId())){
                $aGroups['student']=$oUser->getFio();
                if($oGroup = $oUser->getGroup()){

                    $aGroups['group']=$oGroup->getName();

                }
            }

            $aGroups['status']=$oDocument->getBadge();
            $aGroups['add_date']=$oDocument->getAddDate();


            $aGroups['link']='<a href="'.$oDocument->getUrlFull().'">'.$oScheme->getName().'</a>';
            $aGroups['edit_url']=$oDocument->getEditUrl();

            $aGroups['statuses_admin']=array(
                array(
                    'status'=>ModuleDocument::DOCUMENT_NEW,
                    'name'=>'Отправлено'
                ),
                array(
                    'status'=>ModuleDocument::DOCUMENT_PROCESS,
                    'name'=>'В обработке'
                ),
                array(
                    'status'=>ModuleDocument::DOCUMENT_AGREEMENT,
                    'name'=>'На согласовании'
                ),
                array(
                    'status'=>ModuleDocument::DOCUMENT_SUCCESS,
                    'name'=>'Справка готова'
                ),
                array(
                    'status'=>ModuleDocument::DOCUMENT_REJECT,
                    'name'=>'Отказ'
                ),
            );

            $aResultItems[] = $aGroups;
        }

        $this->Viewer_AssignAjax("rows", $aResultItems);
        $this->Viewer_AssignAjax("total", $aResult['count']);
    }

    protected function AjaxEventDocumentsList() {
        $this->Viewer_SetResponseAjax('json');

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
            '#order'=>array($sOrder=>$sOrderWay),
            '#page'=>array($iPage, $iPerPage)
        );

        /**
         * Получаем список юзеров
         */
        $aResult = $this->Document_GetDocumentSchemeItemsByFilter($aFilter);
        $aResultItems = [];

        $aCopyFields = ['id', 'name', ];
        foreach ($aResult['collection'] as $oDocument) {
            $aGroups = [];
            foreach ($aCopyFields as $sFieldName) {
                $aGroups[$sFieldName] = call_user_func([$oDocument, 'get' . func_camelize($sFieldName)]);
            }

            $aGroups['edit_url']=$oDocument->getEditUrl();


            $aResultItems[] = $aGroups;
        }

        $this->Viewer_AssignAjax("rows", $aResultItems);
        $this->Viewer_AssignAjax("total", $aResult['count']);
    }

    protected function EventDocumentSchemeList()
    {
        $this->SetTemplateAction('documents/list');
    }

    protected function EventAddDocumentScheme(){

        $this->SetTemplateAction('documents/add');



        if (isPost('submit_add')) {
            $this->Security_ValidateSendForm();
            $oEnt = Engine::GetEntity('ModuleDocument_EntityDocumentScheme');
            $oEnt->setId(getRequest('id'));
            $oEnt->setName(getRequest('name'));
            $oEnt->setDescription(getRequest('description'));
            $oEnt->setSorting(getRequest('sorting', 0));




            // for update process
            if ($oEnt->getId()) {

                $oEnt->_SetIsNew(false);
            }

            if (!$oEnt->_Validate()) {
                $this->Message_AddError($oEnt->_getValidateError(), $this->Lang_Get('error'));
                return false;
            }



            if ($oEnt->Save()) {



                $this->Message_AddNotice('Ok', '', true);
                Router::Location(Router::GetPath('admin') . 'documents/list/');


                // $oEnt->Update();
            }else{
                $this->Message_AddError($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            }
        }


    }

    public function EventDocumentSchemeEdit() {
        if (!$oDocumentScheme = $this->Document_GetDocumentSchemeById((int) $this->GetParam(1))) {
            $this->Message_AddError("Такой схемы документа не существует", $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('admin') . 'documents/list/');
            return false;
        }


        $_REQUEST = array_merge($_REQUEST, $oDocumentScheme->_getDataArray());
        $this->Viewer_Assign('oDocumentScheme', $oDocumentScheme);
        $this->SetTemplateAction('documents/add');
    }


    public function EventAddDocumentSchemeFields(){
        /**
         * Проверяем есть ли компания с таким УРЛ
         */
        if (!$oDocumentScheme = $this->Document_GetDocumentSchemeById((int) $this->GetParam(1))) {
            return parent::EventNotFound();
        }

        $aIds = getRequest('selected', array());
        $aDeleteEnt = array();
        foreach ($aIds as $iOptionId){
            $oOption = $this->Document_GetDocumentSchemeFieldById($iOptionId);
            $aDeleteEnt[]=$oOption;
            //$oOption->Delete();
        }
        if(count($aDeleteEnt)){
            foreach ($aDeleteEnt as $oEnt){
                $oEnt->Delete();
            }
            $this->Message_AddNoticeSingle('Сохранено', $this->Lang_Get('attention'));

        }

        $iPerPage = 30;
        $aFilter = array(
            'document_scheme_id'=>$oDocumentScheme->getId(),
            '#order'=>array('sorting'=>'asc'),

        );
        $aFields = $this->Document_GetDocumentSchemeFieldItemsByFilter($aFilter);


        $this->Viewer_Assign('fields',$aFields);


        $this->Viewer_Assign('iCountAll', count($aFields));

        $this->Viewer_Assign('oDocumentScheme',$oDocumentScheme);

        $this->SetTemplateAction('documents/fields');

    }
    /**
     * ДОбавляет к схеме новое поле
     * @return string
     */
    protected function EventAddDocumentSchemeFieldAdd(){


        /**
         * Проверяем есть ли компания с таким УРЛ
         */
        if (!$oDocumentScheme = $this->Document_GetDocumentSchemeById((int) $this->GetParam(1))) {
            return parent::EventNotFound();
        }


        if (isPost('submit_add_field')){
            $this->Security_ValidateSendForm();

            $oDocumentSchemeField = Engine::GetEntity('ModuleDocument_EntityDocumentSchemeField');

            $oDocumentSchemeField->setDocumentSchemeId($oDocumentScheme->getId());
            $oDocumentSchemeField->setType(getRequest('type'));
            $oDocumentSchemeField->setName(getRequest('name'));
            $oDocumentSchemeField->setDescription(getRequest('description', ''));
            $oDocumentSchemeField->setSorting(getRequest('sorting'));
            $oDocumentSchemeField->setCode(getRequest('code'));




            $bOk = true;
            $aErrors = array();
            if (!$oDocumentSchemeField->_Validate()) {
                //$aErrors = array_merge($aErrors, $oOption->_getValidateErrors());
                foreach ($oDocumentSchemeField->_getValidateErrors() as $aErrorItem){
                    $aErrors[] = $aErrorItem;
                }
                $bOk = false;
            }

            $aDataValues = getRequest('field_value', array());

            $aValuesEnt = array();
            if (in_array(getRequest('type'), ['select', 'radio', 'checkbox'])){
                foreach ($aDataValues as $aValue){
                    $oDocumentSchemeFieldValue = Engine::GetEntity('ModuleDocument_EntityDocumentSchemeFieldValue');
                    $oDocumentSchemeFieldValue->setName($aValue['name']);
                    $oDocumentSchemeFieldValue->setSorting($aValue['sorting']);
                    $aValuesEnt[]=$oDocumentSchemeFieldValue;
                    if (!$oDocumentSchemeFieldValue->_Validate()) {

                        foreach ($oDocumentSchemeFieldValue->_getValidateErrors() as $aErrorItem){
                            $aErrors[] = $aErrorItem;
                        }
                        $bOk = false;
                    }
                }


                if(count($aValuesEnt) < 1){
                    $aErrors[] = array('Не указано значение поля!');
                    $bOk = false;
                }
            }


            if($bOk){
                if($oDocumentSchemeField->Save()){
                    /**
                     * Сохраняем значения
                     */
                    foreach ($aValuesEnt as $oValueEnt){
                        $oValueEnt->setFieldId($oDocumentSchemeField->getId());
                        $oValueEnt->Save();
                    }

                    $this->Message_AddNoticeSingle('Поле для схемы документа успешно добавлено', $this->Lang_Get('attention'), true);
                    Router::Location($oDocumentScheme->getEditUrlFields());

                }
            }else{

                foreach($aErrors as $sFieldKey => $aErrorsGroup) {
                    foreach ($aErrorsGroup as $sError)
                        $this->Message_AddError($sError,$this->Lang_Get('error'));
                }

                $_REQUEST['field_value'] = $aDataValues;
            }


        }

        $this->Viewer_Assign('oDocumentScheme',$oDocumentScheme);
        $this->Viewer_AddHtmlTitle($oDocumentScheme->getName());
        $this->Viewer_AddHtmlTitle('Редактирование схемы документа');

        $this->SetTemplateAction('documents/fields_add');

    }

    /**
     * Показ и обработка формы приглаешния студентов и сотрудников
     *
     */

    protected function EventDocumentSchemeEditField(){
        /**
         * Проверяем есть ли компания с таким УРЛ
         */
        if (!$oDocumentScheme = $this->Document_GetDocumentSchemeById((int) $this->GetParam(1))) {
            return parent::EventNotFound();
        }
        if (!$oDocumentSchemeField = $this->Document_GetDocumentSchemeFieldById($this->GetParam(3))) {
            return parent::EventNotFound();
        }

        if (isPost('submit_add_field')){
            $this->Security_ValidateSendForm();

            $oDocumentSchemeField->setType(getRequest('type'));
            $oDocumentSchemeField->setName(getRequest('name'));
            $oDocumentSchemeField->setDescription(getRequest('description'));
            $oDocumentSchemeField->setCode(getRequest('code'));
            $oDocumentSchemeField->setSorting(getRequest('sorting'));

            $bOk = true;
            $aErrors = array();
            if (!$oDocumentSchemeField->_Validate()) {
                //$aErrors = array_merge($aErrors, $oOption->_getValidateErrors());
                foreach ($oDocumentSchemeField->_getValidateErrors() as $aErrorItem){
                    $aErrors[] = $aErrorItem;
                }
                $bOk = false;
            }

            $aDataValues = getRequest('field_value', array());

            $aValuesEnt = array();


            $aDeleted = array(); // удаленные значения
            $aSaved = array();
            if (in_array(getRequest('type'), ['select', 'radio', 'checkbox'])){
                foreach ($aDataValues as $aValue){
                    $oFieldValue = Engine::GetEntity('ModuleDocument_EntityDocumentSchemeFieldValue');
                    if(isset($aValue['field_value_id'])){
                        if(!in_array($aValue['field_value_id'], array())){
                            /**
                             * если нет такой опции, то удалим ее в будующем
                             */
                            $aDeleted[]=$aValue['field_value_id'];
                        }else{
                            $aSaved[]=$aValue['field_value_id'];
                        }
                        $oFieldValue->setId($aValue['field_value_id']);
                    }

                    $oFieldValue->setName($aValue['name']);
                    $oFieldValue->setSorting($aValue['sorting']);
                    $aValuesEnt[]=$oFieldValue;
                    if (!$oFieldValue->_Validate()) {

                        foreach ($oFieldValue->_getValidateErrors() as $aErrorItem){
                            $aErrors[] = $aErrorItem;
                        }
                        $bOk = false;
                    }
                }


                if(count($aValuesEnt) < 1){
                    $aErrors[] = array('Не указано значение поля!');
                    $bOk = false;
                }
            }


            if($bOk){
                if($oDocumentSchemeField->Save()){
                    /**
                     * Сохраняем значения
                     */

                    /**
                     * Сначала удалим все старые опции
                     */
                    foreach ($aDeleted as $iDeleteId){
                        if($oFieldValue=$this->Document_GetDocumentSchemeFieldValueById($iDeleteId)){
                            $oFieldValue->Delete();
                        }
                    }



                    foreach ($oDocumentSchemeField->getValues() as $oFieldValueOld){
                        if(!in_array($oFieldValueOld->getId(), $aSaved)){
                            $oFieldValueOld->Delete();
                        }
                    }


                    foreach ($aValuesEnt as $oValueEnt){
                        $oValueEnt->setFieldId($oDocumentSchemeField->getId());
                        $oValueEnt->Save();
                    }

                    $this->Message_AddNoticeSingle('Успешно отредактировано', $this->Lang_Get('attention'), true);
                    Router::Location($oDocumentScheme->getEditUrlFields());

                }
            }else{

                foreach($aErrors as $sFieldKey => $aErrorsGroup) {
                    foreach ($aErrorsGroup as $sError)
                        $this->Message_AddError($sError,$this->Lang_Get('error'));
                }

                $_REQUEST['field_value'] = $aDataValues;
            }

        }else{

            $_REQUEST = array_merge($_REQUEST, $oDocumentSchemeField->_getDataArray());
            $_REQUEST['field_value']=array();
            foreach ($oDocumentSchemeField->getValues() as $oFieldValue){
                $_REQUEST['field_value'][] =  $oFieldValue->_getDataArray();
            }
        }

        $this->Viewer_Assign('oDocumentScheme', $oDocumentScheme);
        $this->SetTemplateAction('documents/fields_add');

    }

    protected function EventInvite()
    {
        $this->sMenuSubItemSelect = 'invite';
        $this->Viewer_AddHtmlTitle('Пригласить');

        $this->Viewer_Assign('aGroup', $this->Group_GetGroupItemsAll());
       /**
         * Если отправили форму
         */
        if (isPost()) {
            $this->Security_ValidateSendForm();

            $bError = false;

            /**
             * Емайл корректен?
             */
            if (!$this->Validate_Validate('email', getRequestStr('invite_mail'), array('allowEmpty' => false))) {
                $this->Message_AddError($this->Validate_GetErrorLast());
                return;
            }

            if (!($oInvite = $this->Invite_GenerateInvite())) {
                    return $this->EventErrorDebug();
            }
            $sRefCode = $oInvite->getCode();

            /**
             * Если нет ошибок, то отправляем инвайт
             */
            if (!$bError) {
                /**
                 * Запускаем выполнение хуков
                 */
                $this->Invite_SendNotifyInvite(getRequestStr('invite_mail'), $sRefCode);
                $this->Message_AddNoticeSingle('Ссылка для регистрации отправлена');
            }
        }
    }

    protected function AjaxEventInstitutsList() {
        $this->Viewer_SetResponseAjax('json');

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
            '#order'=>array($sOrder=>$sOrderWay),
            '#page'=>array($iPage, $iPerPage)
        );

        /**
         * Получаем список юзеров
         */
        $aResult = $this->Institut_GetInstitutItemsByFilter($aFilter);
        $aResultItems = [];

        $aCopyFields = ['id', 'name',  'number', 'adress'];
        foreach ($aResult['collection'] as $oInstitut) {
            $aInstituts = [];
            foreach ($aCopyFields as $sFieldName) {
                $aInstituts[$sFieldName] = call_user_func([$oInstitut, 'get' . func_camelize($sFieldName)]);
            }

            $aInstituts['edit_url']=$oInstitut->getEditUrl();

            $aResultItems[] = $aInstituts;
        }

        $this->Viewer_AssignAjax("rows", $aResultItems);
        $this->Viewer_AssignAjax("total", $aResult['count']);
    }

    protected function EventInstitutsList()
    {
        $this->SetTemplateAction('instituts/list');
    }

    protected function EventInstitutsAdd(){

        $this->SetTemplateAction('instituts/add');



        if (isPost('submit_add')) {
            $this->Security_ValidateSendForm();
            $oEnt = Engine::GetEntity('ModuleInstitut_EntityInstitut');
            $oEnt->setId(getRequest('id'));
            $oEnt->setName(getRequest('name'));
            $oEnt->setNumber(getRequest('number'));
            $oEnt->setAdress(getRequest('adress'));
            $oEnt->setDescription(getRequest('description'));




            // for update process
            if ($oEnt->getId()) {

                $oEnt->_SetIsNew(false);
            }

            if (!$oEnt->_Validate()) {
                $this->Message_AddError($oEnt->_getValidateError(), $this->Lang_Get('error'));
                return false;
            }



            if ($oEnt->Save()) {



                $this->Message_AddNotice('Ok', '', true);
                Router::Location(Router::GetPath('admin') . 'instituts/list/');


                // $oEnt->Update();
            }else{
                $this->Message_AddError($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            }
        }


    }

    public function EventInstitutsEdit() {
        if (!$oInstitut = $this->Institut_GetInstitutById((int) $this->GetParam(1))) {
            $this->Message_AddError("Такой институт не существует", $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('admin') . 'instituts/list/');
            return false;
        }


        $_REQUEST = array_merge($_REQUEST, $oInstitut->_getDataArray());
        /*
         * чтобы при ошибке валидации показывалось старое изображение
         */

        $this->SetTemplateAction('instituts/add');
    }


    /*
     *
     */

    protected function AjaxEventGroupsList() {
        $this->Viewer_SetResponseAjax('json');

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
            '#order'=>array($sOrder=>$sOrderWay),
            '#page'=>array($iPage, $iPerPage)
        );

        /**
         * Получаем список юзеров
         */
        $aResult = $this->Group_GetGroupItemsByFilter($aFilter);
        $aResultItems = [];

        $aCopyFields = ['id', 'name',  'name_full', 'tutor_id', 'institut_id'];
        foreach ($aResult['collection'] as $oGroup) {
            $aGroups = [];
            foreach ($aCopyFields as $sFieldName) {
                $aGroups[$sFieldName] = call_user_func([$oGroup, 'get' . func_camelize($sFieldName)]);
            }

            $aGroups['edit_url']=$oGroup->getEditUrl();
            if($oInstitut = $this->Institut_GetInstitutById($oGroup->getInstitutId())){
                $aGroups['istitut']=$oInstitut->getName();

            }

            $aResultItems[] = $aGroups;
        }

        $this->Viewer_AssignAjax("rows", $aResultItems);
        $this->Viewer_AssignAjax("total", $aResult['count']);
    }

    protected function EventGroupsList()
    {
        $this->SetTemplateAction('groups/list');
    }

    protected function EventGroupsAdd(){

        $this->SetTemplateAction('groups/add');

        $aInstituts=$this->Institut_GetInstitutItemsAll();


        $this->Viewer_Assign('aInstituts', $aInstituts);
        if (isPost('submit_add')) {
            $this->Security_ValidateSendForm();
            $oEnt = Engine::GetEntity('ModuleGroup_EntityGroup');
            $oEnt->setId(getRequest('id'));
            $oEnt->setName(getRequest('name'));
            $oEnt->setNameFull(getRequest('name_full'));
                $oEnt->setTutorId(getRequest('tutor_id', 0));
            $oEnt->setInstitutId(getRequest('institut_id', 0));




            // for update process
            if ($oEnt->getId()) {

                $oEnt->_SetIsNew(false);
            }

            if (!$oEnt->_Validate()) {
                $this->Message_AddError($oEnt->_getValidateError(), $this->Lang_Get('error'));
                return false;
            }



            if ($oEnt->Save()) {



                $this->Message_AddNotice('Ok', '', true);
                Router::Location(Router::GetPath('admin') . 'groups/list/');


                // $oEnt->Update();
            }else{
                $this->Message_AddError($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            }
        }


    }

    public function EventGroupsEdit() {

        $aInstituts=$this->Institut_GetInstitutItemsAll();
        $this->Viewer_Assign('aInstituts', $aInstituts);


        if (!$oGroup = $this->Group_GetGroupById((int) $this->GetParam(1))) {
            $this->Message_AddError("Такой группы не существует", $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('admin') . 'groups/list/');
            return false;
        }
        $aStudents=$this->User_GetUserItemsByGroupId($oGroup->getId());
        $this->Viewer_Assign('aStudents', $aStudents);

        $_REQUEST = array_merge($_REQUEST, $oGroup->_getDataArray());
        /*
         * чтобы при ошибке валидации показывалось старое изображение
         */

        $this->SetTemplateAction('groups/add');
    }

    /**
     * Эвент списка меток
     *
     * @return string
     */
    protected function EventMapList()
    {
        $this->SetTemplateAction('maps/maps');
    }

    protected function EventMapAdd(){

        $this->SetTemplateAction('maps/add');

        $this->Viewer_Assign('aCategories', Config::Get('plugin.sfishing.map.categories'));



        if (isPost('submit_add')) {
            $this->Security_ValidateSendForm();
            $oEnt = Engine::GetEntity('ModuleMaps_EntityMap');
            $oEnt->setId(getRequest('id'));
            $oEnt->setTitle(getRequest('title'));
            $oEnt->setTargetType(getRequest('target_type'));
            $oEnt->setTargetId(getRequest('target_id', 0));
            $oEnt->setAddress(getRequest('address'));
            $oEnt->setSorting(getRequest('sorting'));
            $oEnt->setDescription(getRequest('description'));
            $oEnt->setCategoryId(getRequest('category_id'));
            $oEnt->setLat(getRequest('lat'));
            $oEnt->setLng(getRequest('lng'));

            $oEnt->setPhone(getRequest('phone'));
            $oEnt->setMail(getRequest('mail'));







            // for update process
            if ($oEnt->getId()) {

                $oEnt->_SetIsNew(false);
            }

            if (!$oEnt->_Validate()) {
                $this->Message_AddError($oEnt->_getValidateError(), $this->Lang_Get('error'));
                return false;
            }


            // теперь можно загрузить фото
            if (getRequest('delete_photo') ) {
                if(isset($oMap)){

                    if($this->Maps_DeletePhoto($oMap)){

                        $oEnt->setPhotoPath('');
                    }
                }
            } else if (isset($_FILES['photo']) and is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $this->Maps_DeletePhoto($oEnt);
                if (!$this->Maps_UploadPhoto($_FILES['photo'], $oEnt)) return false;
            }

            if ($oEnt->Save()) {



                $this->Message_AddNotice('Ok', '', true);
                Router::Location(Router::GetPath('admin') . 'maps/list/');


                // $oEnt->Update();
            }else{
                $this->Message_AddError($this->Lang_Get('not_access'), $this->Lang_Get('error'));
            }
        }


    }

    public function EventMapEdit() {
        if (!$oMap = $this->Maps_GetMapById((int) $this->GetParam(1))) {
            $this->Message_AddError($this->Lang_Get('plugin.sfishing.errors.not_found_location'), $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('admin') . 'maps/list/');
            return false;
        }

        $this->Viewer_Assign('aCategories', Config::Get('plugin.sfishing.map.categories'));

        $_REQUEST = array_merge($_REQUEST, $oMap->_getDataArray());
        /*
         * чтобы при ошибке валидации показывалось старое изображение
         */

        $this->SetTemplateAction('maps/add');
    }

}