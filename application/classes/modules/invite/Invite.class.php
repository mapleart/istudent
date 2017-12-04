<?php

class ModuleInvite extends ModuleORM
{

    const INVITE_TYPE_CODE = 2;
    /**
     * Генерирует новый код инвайта
     *
     * @param int $iUserId
     * @param string|null $sCode
     * @param int $iCountAllowUse
     * @param int|string|null $sDateExpired
     * @return bool|ModuleInvite_EntityCode
     */
    public function GenerateInvite($sCode = null, $iCountAllowUse = 1, $sDateExpired = null)
    {

        $sDateExpired = is_int($sDateExpired) ? date('Y-m-d H:i:s', time() + $sDateExpired) : $sDateExpired;
        $oInviteCode = Engine::GetEntity('ModuleInvite_EntityCode');

        $oInviteCode->setCode(is_null($sCode) ? $this->GenerateRandomCode() : $sCode);
        $oInviteCode->setCountAllowUse($iCountAllowUse);
        $oInviteCode->setDateExpired($sDateExpired);
        $oInviteCode->setActive(1);
        $oInviteCode->setLastName(getRequest('last_name'));
        $oInviteCode->setFirstName(getRequest('first_name'));
        $oInviteCode->setParentName(getRequest('parent_name'));
        $oInviteCode->setGroupId(getRequest('group_id'));
        $oInviteCode->setCardNumber(getRequest('card_number'));
        if ($oInviteCode->Add()) {
            return $oInviteCode;
        }
        return false;
    }
    /**
     * Фиксирует факт использования кода инвайта
     *
     * @param string $sCode
     * @param int $iUserId
     * @return bool
     */
    public function UseCode($sCode, $iUserId)
    {
        $iUserId = is_scalar($iUserId) ? (int)$iUserId : $iUserId->getId();

        $oUse = Engine::GetEntity('ModuleInvite_EntityUse');
        $oUse->setType(self::INVITE_TYPE_CODE);
        $oUse->setToUserId($iUserId);

        $oCode = $this->GetCodeByCode($sCode);
        $oCode->setCountUse($oCode->getCountUse() + 1);
        $oCode->Update();
        $oUse->setCodeId($oCode->getId());
        $oUse->setFromUserId($oCode->getUserId());

        return $oUse->Add();
    }
    /**
     * корректность кода инвайта
     */
    public function CheckCode($sCode)
    {
        if ($oCode = $this->GetCodeByCode($sCode)) {
                if ($oCode->getActive()
                    and $oCode->getCountUse() < $oCode->getCountAllowUse()
                    and (!$oCode->getDateExpired() or strtotime($oCode->getDateExpired()) < time())
                ) {
                    return true;
                }
        }

        return false;
    }

    /**
     * Генерирует случайный код
     */
    protected function GenerateRandomCode()
    {
        return func_generator(10);
    }



    /**
     * Отправляет приглашение студенту
     */
    public function SendNotifyInvite($sMailTo, $sRefCode)
    {

        if (!$sRefCode) {
            return false;
        }

        $sRefLink = Router::GetPath('auth/referral') . urlencode($sRefCode) . '/';

        $this->Notify_Send(
            $sMailTo,
            'invite.tpl',
            'Регистрация в личном кабинете Я.Студент',
            array(
                'sMailTo'   => $sMailTo,
                'oUserFrom' => $this->User_GetUserCurrent(),
                'sRefCode'  => $sRefCode,
                'sRefLink'  => $sRefLink,
            )
        );
    }
}