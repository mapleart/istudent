<?php

class ModuleNotification extends ModuleORM {
    public function Init() {
        parent::Init();
    }


    public function createNotification($oSender, $oRecipient, $aParams){
        $oSender = $oSender instanceof ModuleUser_EntityUser ? $oSender : $this->User_GetUserById($oSender);
        $oRecipient = $oRecipient instanceof ModuleUser_EntityUser ? $oRecipient : $this->User_GetUserById($oRecipient);

        $sTargetType = $aParams['target_type'];
        $sTargetSubtype = $aParams['target_subtype'];
        $iTargetId = $aParams['target_id'];
        $iTargetSubid = isset($aParams['target_subid']) ? $aParams['target_subid'] : '' ;

        /*
         * Если повторно отправлять не нужно
         * Проверяем наличие в базе и если находим, прерываем функцию
         */

        if(isset($aParams['no_repeat'])) {
            $aFilter = array(
                'sender_id'=>$oSender->getId(),
                'recipient_id'=>$oRecipient->getId(),
                'target_type'=>$sTargetType,
                'target_subtype'=>$sTargetSubtype,
                //'target_id'=>$iTargetId,
                'target_subid'=>$iTargetSubid,

            );

            if($oNotificationOld = $this->GetNotificationByFilter($aFilter)){
                return false;
            };
        }

        $oEnt = Engine::GetEntity('ModuleNotification_EntityNotification');

        $oEnt->setSenderId($oSender->getId());
        $oEnt->setRecipientId($oRecipient->getId());
        $oEnt->setTargetType($sTargetType);
        $oEnt->setTargetSubtype($sTargetSubtype);
        $oEnt->setTargetId($iTargetId);
        $oEnt->setTargetSubid($iTargetSubid);

        if(isset($aParams['is_anonymous'])){
            $oEnt->setIsAnonymous(1);
        }

        if(!isset($aParams['meta'])){
            $aParams['meta']=array();
        }
        $oEnt->setMeta(is_array($aParams['meta']) ? serialize($aParams['meta']) : $aParams['meta']);

        if($oEnt->Save()){

            return $oEnt;
        }
        return false;

    }

    public function GetCountNew($user_id=null)
    {
        if(!$oUser=$this->User_GetUserById($user_id)){
            $oUser = $this->User_GetUserCurrent();
        }
        if(!$oUser) return;
        $aFilter=array(
            'recipient_id'=>$oUser->getId(),
            'is_new'=>1
        );
        $aData = $this->GetCountItemsByFilter($aFilter, 'ModuleNotification_EntityNotification');
        return $aData;
    }

    public function GetCountAll($user_id=null)
    {
        if(!$oUser=$this->User_GetUserById($user_id)){
            $oUser = $this->User_GetUserCurrent();
        }
        if(!$oUser) return;
        $aFilter=array(
            'recipient_id'=>$oUser->getId(),
        );
        $aData = $this->GetCountItemsByFilter($aFilter, 'ModuleNotification_EntityNotification');
        return $aData;
    }
}
