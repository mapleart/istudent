<?php
class HookMain extends Hook {
	/**
	 * Регистрируем хуки
	 */
	public function RegisterHook() {
		$this->AddHook('start_action','StartAction');
	}
	/**
	 * Обработка хука старте экшенов. Выполняется один раз в отличии от хука "init_action"
	 */
	public function StartAction() {
		/**
		 * Загружаем в шаблон текущего пользователя
		 */
		$this->Viewer_Assign('oUserCurrent',$this->User_GetUserCurrent());
        $this->Viewer_Assign('iCountNotifications', $this->Notification_GetCountNew());

        /**
		 * Загружаем js и текстовки в шаблон
		 */
		$this->LoadDefaultJsVarAndLang();


        $oUserCurrent=$this->User_GetUserCurrent();
        if (!$oUserCurrent and Router::GetAction()!='auth') {
            Router::Action('auth');
        }


	}

	/**
	 * Загрузка необходимых переменных и текстовок в шаблон
	 */
	public function LoadDefaultJsVarAndLang()
	{
	}
}