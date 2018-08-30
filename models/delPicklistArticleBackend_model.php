<?php

class DelPicklistArticleBackend_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_delPicklistItem($_POST['picklistID'], $_POST['itemID']);
    }

    private function _delPicklistItem($picklistID, $itemID)
    {

        $sqlRemoveFromList = "DELETE FROM stpArtikel2Pickliste WHERE PicklistID = '{$picklistID}' AND ArtikelID = '{$itemID}'";
        $this->db->select($sqlRemoveFromList);

        $sqlUpdateItemStatus = "UPDATE stpPicklistItems SET ItemStatus = '0' WHERE ID = '{$itemID}'";
        $this->db->select($sqlUpdateItemStatus);

    }
}
