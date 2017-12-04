<?php

class ModuleMaps_EntityMap extends EntityORM
{



    public function Init()
    {

    }

    protected $aValidateRules = array(
        /*
         * на момент валидации может быть пустым т.к. будет заполнен после сохранения (через AUTO_INCREMENT)
         */
        array('id', 'number', 'min' => 1, 'allowEmpty' => true, 'integerOnly' => true),
        //array('category_id', 'number', 'min' => 0, 'allowEmpty' => false),
        array('title', 'string', 'min' => 2, 'max' => 100, 'allowEmpty' => false),
        array('description', 'string', 'min' => 10, 'max' => 500, 'allowEmpty' => false),

        array('sorting', 'check_sorting'),

        array('phone', 'regexp', 'pattern' => '#^[+\d() -]{5,20}$#', 'allowEmpty' => false),
        array('mail', 'email', 'allowEmpty' => true),

        array('city', 'url', 'allowEmpty' => true),

        array('social_vk', 'url', 'allowEmpty' => true),
        array('social_fb', 'url', 'allowEmpty' => true),
        array('social_ok', 'url', 'allowEmpty' => true),
        array('social_tw', 'url', 'allowEmpty' => true),





        /*
         * tip: seo поля валидировать не нужно т.к. они заполняются когда продукт уже прошел валидацию и там только строки без тегов
         */
    );

    public function ValidateCheckSorting($mValue, $aParams)
    {
        return true;
    }



   protected function beforeSave()
    {
        if ($this->_isNew()) {
            $this->setDateAdd(date('Y-m-d H:i:s'));
         }else {
            $this->setDateEdit(date('Y-m-d H:i:s'));
        }
        return parent::beforeSave();

    }

    protected function beforeDelete() {
        $bSuccess = parent::beforeDelete();
        if ($bSuccess) {
            /*
             * удалить запись из гео привязки
             */
            //$this->PluginSfishing_Maps_DeletePhoto($this);
        }
        return $bSuccess;
    }



    public function getPhoto($iSize=250){
        if ($sPath=$this->getPhotoPath()) {
            return str_replace('_600x600',(($iSize==0)?"":"_{$iSize}x{$iSize}"),$sPath."?".date('His',strtotime($this->getDateEdit())));
        }
        return false;
    }


    public function getEditUrl(){
        return Router::GetPath('admin') . 'maps/edit/' . $this->getId();
    }
    public function getUrl(){
        return Router::GetPath('sprav') . '?marker=' . $this->getId();
    }

}
