<?php
/**
 * Сущность восстановления пароля
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntityReminder extends EntityORM
{

    protected $aRelations = array(
        'user' => array(self::RELATION_TYPE_BELONGS_TO, 'ModuleUser_EntityUser', 'user_id'),
    );

    protected function beforeSave()
    {
        if ($this->_isNew()) {
            $this->setDateCreate(date("Y-m-d H:i:s"));
        }
        return true;
    }
}