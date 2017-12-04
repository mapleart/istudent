<?php

class ModuleDocument_EntityDocumentValue extends EntityORM {


    protected $aRelations = array(
        //'fields' => array(EntityORM::RELATION_TYPE_HAS_MANY, 'ModuleDocument_EntityDocumentSchemeField', 'document_scheme_id'),
        //'field' => array(EntityORM::RELATION_TYPE_BELONGS_TO, 'ModuleDocument_EntityDocumentSchemeField', 'document_scheme_field_id'),

    );


    /*
     * Правила валидации данных сущности
     */
	protected $aValidateRules = array(
		array('id', 'number', 'min' => 1, 'allowEmpty' => true, 'integerOnly' => true),
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

    /**
     * @return bool
     */
    public function getField()
    {
        return $this->Document_GetDocumentSchemeFieldById($this->getDocumentSchemeFieldId());
    }

}

?>