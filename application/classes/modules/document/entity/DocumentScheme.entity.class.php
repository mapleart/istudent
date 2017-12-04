<?php

class ModuleDocument_EntityDocumentScheme extends EntityORM {


    protected $aRelations = array(
        'fields' => array(EntityORM::RELATION_TYPE_HAS_MANY, 'ModuleDocument_EntityDocumentSchemeField', 'document_scheme_id'),
    );


    /*
     * Правила валидации данных сущности
     */
	protected $aValidateRules = array(
		array('id', 'number', 'min' => 1, 'allowEmpty' => true, 'integerOnly' => true),
		array('name', 'string', 'min' => 2, 'max' => 100, 'allowEmpty' => false, 'label'=>'Название документа'),
		array('description', 'string', 'min' => 2, 'max' => 1000, 'allowEmpty' => false, 'label'=>'Описание документа'),
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

    public function getEditUrlFieldsAdd(){
        return Router::GetPath('admin') . 'documents/edit/' . $this->getId().'/fields/add/';
    }
}

?>