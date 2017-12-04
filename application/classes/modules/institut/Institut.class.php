<?php

class ModuleInstitut extends ModuleORM {

	private $oMapper = null;



	public function Init() {
		/*
		 * orm требует этого
		 */
		parent::Init();
		$this->oMapper = Engine::GetMapper(__CLASS__);
	}


}