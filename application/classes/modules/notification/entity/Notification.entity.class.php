<?php

class ModuleNotification_EntityNotification extends EntityORM
{


    protected $aValidateRules = array(

    );

    protected $aRelations = array(
        'sender' => array(EntityORM::RELATION_TYPE_BELONGS_TO, 'ModuleUser_EntityUser', 'sender_id'),
        'recipient' => array(EntityORM::RELATION_TYPE_BELONGS_TO, 'ModuleUser_EntityUser', 'recipient_id')
    );

    public function Init()
    {
    }

    /**
     * Вызывается перед сохранением сущности
     * @return bool|void
     */
    protected function beforeSave()
    {
        /*
         * если сущность новая - поставить дату и автора
         */
        if ($this->_isNew()) {
            $this->setAddDate(date('Y-m-d H:i:s'));
        }

        return parent::beforeSave();
    }


    public function getData(){
        $oSender=$this->getSender();
        $oRecipient=$this->getRecipient();
        $oSenderId=$this->getSenderId();


        $sTargetType=$this->getTargetType();
        $iTargetId = $this->getTargetId();

        switch ($this->getTargetType()){
            case 'document':
                if($oTarget = $this->Document_GetDocumentById($iTargetId)){
                    return array(
                        'target'=>$oTarget,
                        'type'=>$sTargetType
                    );
                }
                break;
        }
    }

    public function getMetaArray()
    {
        $aArr=unserialize($this->getMeta());
        if(is_array($aArr)){
            return $aArr;
        }
        return array();
    }

    public function getMetaValue($sKey)
    {
        $aMeta = $this->getMetaArray();
        if(isset($aMeta[$sKey])){
            return $aMeta[$sKey];
        }
        return false;
    }

    /*
     * При показе в шалоне обновить статус
     */
    public function View()
    {
        $this->setIsNew(0);
        $this->setViewDate(date('Y-m-d H:i:s'));

        $this->Update();
    }

}