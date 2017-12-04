<?php

class ModuleDocument extends ModuleORM {

    const DOCUMENT_NEW=0;
    const DOCUMENT_PROCESS=2;
    const DOCUMENT_AGREEMENT=4;
    const DOCUMENT_SUCCESS=8;
    const DOCUMENT_REJECT=10;
	private $oMapper = null;



	public function Init() {
		/*
		 * orm требует этого
		 */
		parent::Init();

		$this->oMapper = Engine::GetMapper(__CLASS__);
	}


}