<?php

class ActionNotification extends ActionPlugin {
    protected $oUserCurrent = null;

    public function Init() {

        $this->oUserCurrent = $this->User_GetUserCurrent();

        $this->Viewer_AddHtmlTitle('Уведомления');

    }

    /**
     * Регистрируем евенты
     */
    protected function RegisterEvent() {
        $this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i','EventIndex');
        $this->AddEventPreg('/^ajax$/i', '/^delete$/', 'AjaxDelete');
        $this->AddEventPreg('/^ajax$/i', '/^delete_all$/', 'AjaxDeleteAll');


    }

    protected function EventIndex() {

        $aFilter=array(
            'recipient_id'=>$this->oUserCurrent->getId(),
            '#order' =>array('add_date'=>'desc')
        );

        $aNotifications = $this->Notification_GetNotificationItemsByFilter($aFilter);

        $this->Viewer_Assign('aNotifications', $aNotifications);
        $this->Viewer_Assign('iCountNotification', count($aNotifications));
        $this->SetTemplateAction('index');

    }

    protected function AjaxDelete()
    {
        $this->Viewer_SetResponseAjax('json');

        $oNotification = $this->Notification_GetNotificationById(getRequest('id'));
        if(!$oNotification) {
            return parent::EventNotFound();
        }

        if($oNotification->Delete()){
            $this->Viewer_AssignAjax('count', $this->Notification_GetCountAll());
            $this->Message_AddNoticeSingle('Успешно удалено');
        }
    }
    protected function AjaxDeleteAll()
    {
        $this->Viewer_SetResponseAjax('json');

        if(!$oUserCurrent = $this->User_GetUserCurrent()){
            return parent::EventNotFound();
        }
        $aNotifications = $this->Notification_GetNotificationItemsByFilter(array(
            'recipient_id'=>$oUserCurrent->getId(),
        ));

        foreach ($aNotifications as $oNotification){
            if($oNotification->Delete()){

            }
        }

        $this->Message_AddNoticeSingle('Успешно удалено');
    }

}
