<?php

class ModuleMaps_MapperMaps extends Mapper
{
    /**
     * @return int
     */
    public function GetMaxSortingValue() {
        $sSql = 'SELECT MAX(`sorting`)
			FROM
				`' . Config::Get('db.table.maps_map') . '`
		';
        return (int) $this->oDb->selectCell($sSql);
    }
}
