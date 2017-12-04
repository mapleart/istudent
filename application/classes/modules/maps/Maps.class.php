<?php

class ModuleMaps extends ModuleORM  {

    const TYPE_GYM = 1;
    const TYPE_CORPUS = 2;
    const TYPE_DANCE = 3;
    const TYPE_VOCAL = 4;
    const TYPE_SOCIAL = 5;
    const TYPE_MEDICAL = 6;
    const GEO_TARGET_TYPE = 'map';
    private $oMapper = null;

    public function Init()
    {
        /*
         * orm требует этого
         */
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }



    public function DeleteFile($oMap) {
        if($oMap->getFileName()) {
            $this->Image_RemoveFile($this->Image_GetServerPath($oMap->getFilePath()));
        }
    }


}

