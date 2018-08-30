<?php

/**
 * Artikelprüfung einer eingescannten EAN/GTIN
 *
 * @author: Marlon Böhland
 * @access: public
 */
class ScanArt_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $ean
     * @return array
     */
    public function checkIfArticleOrderExists($ean)
    {
        return $this->db->select("SELECT count(*) as anz FROM stpPicklistItems WHERE EanUpc = '{$ean}'");
    }
}
