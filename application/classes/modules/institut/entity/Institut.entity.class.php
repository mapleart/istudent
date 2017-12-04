<?php

class ModuleInstitut_EntityInstitut extends EntityORM {

	/*
	 * Правила валидации данных сущности
	 */
	protected $aValidateRules = array(
		array('id', 'number', 'min' => 1, 'allowEmpty' => true, 'integerOnly' => true),
		array('name', 'string', 'min' => 2, 'max' => 100, 'allowEmpty' => false),
		array('description', 'string', 'min' => 0, 'max' => 500, 'allowEmpty' => true),
	);



	/**
	 * Вызывается перед сохранением категории
	 *
	 * @return bool|void
	 */
	protected function beforeSave() {

		return parent::beforeSave();
	}


	/**
	 * Вызывается после сохранения категории
	 */
	protected function afterSave() {
		/*
		 * если это было редактирование категории
		 */
		if (!$this->_isNew()) {

		}
	}


	/**
	 * Вызывается перед удалением категории
	 *
	 * @return bool
	 */
	protected function beforeDelete() {

		return true;
	}

    public function getEditUrl(){
        return Router::GetPath('admin') . 'instituts/edit/' . $this->getId();
    }
}

?>