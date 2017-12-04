<?php

class ActionEducation extends Action
{

    protected $sMenuHeadItemSelect = 'education';

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->SetDefaultEvent('documents');


    }

    /**
     * Регистрация евентов
     *
     */
    protected function RegisterEvent()
    {


        $this->AddEventPreg('/^documents$/i','/^request$/i', '/^\d+$/i', '/^$/i', 'EventDocumentsRequest');
        $this->AddEventPreg('/^documents$/i',  '/^$/i', 'EventDocuments');

        $this->AddEventPreg('/^document$/i','/^view$/i', '/^\d+$/i', '/^$/i', 'EventDocumentView');

    }


    protected function EventDocuments()
    {
        /**
         * Устанавливаем шаблон вывода
         */
        $this->Viewer_Assign('aDocuments', $this->Document_GetDocumentSchemeItemsAll());
        $this->Viewer_Assign('aDocumentsUser', $this->Document_GetDocumentItemsByUserId($this->User_GetUserCurrent()->getId()));
        $this->SetTemplateAction('documents');
    }


    protected function EventDocumentView()
    {
        if (!$oDocument = $this->Document_GetDocumentById((int) $this->GetParam(1))) {
            $this->Message_AddError("Такого документа не существует", $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('education') . 'documents/');
            return false;
        }
        /**
         * Устанавливаем шаблон вывода
         */
        $this->Viewer_Assign('oDocument', $oDocument);

        $this->Viewer_Assign('oDocumentScheme', $oDocument->getScheme());
        $this->Viewer_Assign('aDocumentsUser', $this->Document_GetDocumentItemsByUserId($this->User_GetUserCurrent()->getId()));
        $this->SetTemplateAction('document_view');
    }
    /**
     * Главная страница
     *
     */
    protected function EventDocumentsRequest()
    {
        /**
         * Устанавливаем шаблон вывода
         */
        if (!$oDocumentScheme = $this->Document_GetDocumentSchemeById((int) $this->GetParam(1))) {
            $this->Message_AddError("Такой схемы документа не существует", $this->Lang_Get('error'), true);
            Router::Location(Router::GetPath('education') . 'documents/');
            return false;
        }

        if(isPost('add_document')){
            $this->Security_ValidateSendForm();

            $oDocument = Engine::GetEntity('ModuleDocument_EntityDocument');

            $oDocument->setDocumentSchemeId($oDocumentScheme->getId());
            $oDocument->setStatus(ModuleDocument::DOCUMENT_NEW);
            $oDocument->setUserId($this->User_GetUserCurrent()->getId());
            //$oDocument->setSendMail(getRequest('send_mail'));
            


            $bOk = true;
            $aErrors = array();
            if (!$oDocument->_Validate()) {
                //$aErrors = array_merge($aErrors, $oOption->_getValidateErrors());
                foreach ($oDocument->_getValidateErrors() as $aErrorItem){
                    $aErrors[] = $aErrorItem;
                }
                $bOk = false;

            }

            $aValidFields=array();
            $aFields = getRequest('document_field', array());

            foreach ($aFields as $id=>$value) {
                if(!$oDocumentSchemeField=$this->Document_GetDocumentSchemeFieldById($id)){
                    $aErrors[] = 'Неверное поле';
                    continue;
                }

                $oDocumentValue = Engine::GetEntity('ModuleDocument_EntityDocumentValue');
                $oDocumentValue->setDocumentSchemeId($oDocumentScheme->getId());
                $oDocumentValue->setDocumentSchemeFieldId($oDocumentSchemeField->getId());
                if(in_array($oDocumentSchemeField->getType(), array('select', 'radio', 'checkbox')) ){
                    if(!$oDocumentSchemeFieldValue=$this->Document_GetDocumentSchemeFieldValueById($value)){
                        $aErrors[] = 'Неверно заполнено поле '.$oDocumentSchemeField->getName();
                        continue;
                    }
                    $oDocument->setDocumentSchemeFieldValueId($oDocumentSchemeFieldValue->getId());
                }


                $oDocumentValue->setName($oDocumentSchemeField->getName());
                $oDocumentValue->setValue($value);
                $aValidFields[]=$oDocumentValue;
            }

            if($bOk){
                if($oDocument->Save()){
                    /**
                     * Сохраняем значения
                     */
                    foreach ($aValidFields as $oValueEnt){
                        $oValueEnt->setDocumentId($oDocument->getId());
                        $oValueEnt->Save();
                    }

                    $this->Message_AddNoticeSingle('Ваш запрос отправлен сотрудникам универа', true);
                    Router::Location(Router::GetPath('education/documents'));

                }
            }else{

                foreach($aErrors as $sFieldKey => $aErrorsGroup) {
                    foreach ($aErrorsGroup as $sError)
                        $this->Message_AddError($sError,$this->Lang_Get('error'));
                }

                $_REQUEST['field_value'] = $aFields;
            }
        }

        $this->Viewer_Assign('oDocumentScheme', $oDocumentScheme);

        $this->Viewer_Assign('aDocuments', $this->Document_GetDocumentSchemeItemsAll());
        $this->SetTemplateAction('request');
    }

    /**
     * Выполняется при завершении каждого эвента
     */
    public function EventShutdown()
    {
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
    }


}