<?php

class ModuleDocument_EntityDocumentSchemeFieldValue extends EntityORM {

	/*
	 * Правила валидации данных сущности
	 */
	protected $aValidateRules = array(
		array('id', 'number', 'min' => 1, 'allowEmpty' => true, 'integerOnly' => true),
		array('name', 'string', 'min' => 2, 'max' => 128, 'allowEmpty' => false, 'label'=>'Значение'),
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
        return Router::GetPath('admin') . 'documents/edit/' . $this->getId().'/';
    }
    public function getEditUrlFields(){
        return Router::GetPath('admin') . 'documents/edit/' . $this->getId().'/fields/';
    }
}

?>