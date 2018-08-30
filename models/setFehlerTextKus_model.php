<?php

class SetFehlerTextKus_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_setKusNachricht($_POST['articleID'], $_POST['EscComment'], $_POST['ItemFehlerUser']);
    }

    public function _setKusNachricht($articleID, $escComment, $sUser)
    {
        $aUpdate = array('EscComment' => $escComment, 'ItemFehlerUser' => $sUser);
        $this->db->update('stpPicklistItems', $aUpdate, 'ID = ' . $articleID);
    }
}
