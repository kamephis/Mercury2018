<?php

class SetFehlerStatusBackend_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        //if (isset($_REQUEST['itemFehlerUpdate'])) {
        $this->_setGeprueftStatus($_POST['articleID'], $_POST['iStatus'], $_POST['sUser']);
        //}
    }

    /**
     * Setzten des geprueft Status
     * @param $articleID
     * @param $iStatus
     */
    public function _setGeprueftStatus($articleID, $iStatus, $sUser)
    {
        $aUpdate = array('geprueft' => $iStatus, 'pruefer' => $sUser);
        $this->db->update('stpPicklistItems', $aUpdate, 'EanUpc = ' . $articleID);
    }
}
