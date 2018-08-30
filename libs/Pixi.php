<?php

/**
 * Gemeinsam nutzbare Pixi Funktionen
 * Zugriff auf Pixidaten via NuSoap
 *
 * @author: Marlon Böhland
 * @access: public
 * @date: 14.10.2016
 */
class Pixi
{
    private $oProxy;

    /**
     * Pixi constructor.
     */
    public function __construct()
    {
        require_once('nusoap.php');
        $oSoapClient = new nusoap_client(PIXI_WSDL_PATH, true);
        $oSoapClient->soap_defencoding = 'UTF-8';
        $oSoapClient->decode_utf8 = false;
        $oSoapClient->setCredentials(PIXI_USERNAME, PIXI_PASSWORD);

        // pixi* API Objekt erzeugen
        $this->oProxy = $oSoapClient->getProxy();
    }

    /**
     * Neuen Lagerbestand setzen
     * @param $ean
     * @param $bin
     * @param $newStock
     * @param $user
     */
    public function setStock($ean, $bin, $newStock, $user)
    {
        $this->oProxy->pixiSetStockMultiple(array('XML' => '
        <ITEMSTOCK>
	<ITEM>
		<EANUPC>' . $ean . '</EANUPC>		
		<INVBINS>
			<INVBIN>				
				<BINNAME>' . $bin . '</BINNAME>				
				<STOCKADD>' . $newStock . '</STOCKADD>				
			</INVBIN>
		</INVBINS>
	</ITEM>
</ITEMSTOCK>
        ', 'Username' => $user));

        //$this->oProxy->pixiSetStock(array('EanUpc' => trim($ean), 'BinName' => trim($bin), 'NewStockQty' => $newStock, 'Username' => $user,'LocId' => '', 'SMORef' => '', 'SMTref' => ''));

        /*if (is_soap_fault($this->oProxy->pixiSetStock(array('EanUpc' => $ean, 'BinName' => $bin, 'NewStockQty' => $newStock, 'Username' => $user)))) {
            echo "fehler";
        } else {
            $this->oProxy->pixiSetStock(array('EanUpc' => $ean, 'BinName' => $bin, 'NewStockQty' => $newStock, 'Username' => $user));
        }*/
    }

    /**
     * Lieferanten eines Artikels
     * @param $ItemNrInt
     * @return mixed
     */
    public function getItemSuppliers($ItemNrInt)
    {
        $aItemSuppl = $this->oProxy->pixiGetItemSuppliers(array('ItemNrInt' => $ItemNrInt, 'OnlyActiveSuppliers' => 0));
        $aItemSuppl = $aItemSuppl['pixiGetItemSuppliersResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
        if (is_array($aItemSuppl[0])) {
            return $aItemSuppl[0];
        } else {
            return $aItemSuppl;
        }
    }

    /**
     * Lieferanteninfos
     * @param $supplNr
     * @return mixed
     */
    public function getSuppliers($supplNr)
    {
        $aSuppl = $this->oProxy->pixiGetSuppliers(array('SupplNr' => $supplNr));
        $aSuppl = $aSuppl['pixiGetSuppliersResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];

        return $aSuppl;
    }

    /**
     * Ausgabe aller Lagerplätze eines Artikels
     * @param $eanUpc
     * @return mixed
     *
     * Mit Fehlerprüfung
     *
     * ACHTUNG: Kann Probleme mit Konfi-Cronjobs hervorrufen.
     */
    public function getAllBins($eanUpc)
    {
        try {
            $aBins = $this->oProxy->pixiGetItemStockBins(array('EAN' => $eanUpc));
            $aBins = $aBins['pixiGetItemStockBinsResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
            return $aBins;

        } catch (Exception $e) {
            die($e->getMessage() . "<br>" . $e->getLine());
        }
    }

    /**
     * Abrufen aller Pixi Picklisten
     * @return mixed
     */
    public function getAllPicklists()
    {
        try {
            $aPicklists = $this->oProxy->pixiShippingGetPicklistHeaders(array('LocID' => '001'));
            $aPicklists = $aPicklists['pixiShippingGetPicklistHeadersResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
            return $aPicklists;
        } catch (Exception $e) {
            die($e->getMessage() . "<br>" . $e->getLine());
        }
    }

    /**
     * Artikellagerbstand abrufen
     * @param $artEAN
     * @return bool
     */
    function getItemStock($artEAN)
    {
        $itemStock = $this->oProxy->pixiGetItemStock(array('EAN' => $artEAN));
        if (isset($itemStock['pixiGetItemStockResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'])) {
            $itemStock = $itemStock['pixiGetItemStockResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
            return $itemStock;
        } else {
            return false;
        }
    }

    /**
     * @param $orderNr
     * @return mixed
     */
    function getOrderHeader($orderNr)
    {
        $aOrderHeader = $this->oProxy->pixiGetOrderHeader(array('OrderNrExternal' => $orderNr));
        $aOrderHeader = $aOrderHeader['pixiGetOrderHeaderResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];

        return $aOrderHeader;
    }

    /**
     * @param $orderLineKey
     * @return bool
     */
    function getOrderLine($orderLineKey)
    {
        $aOrderline = $this->oProxy->pixiGetOrderline(array('OrderlineKey' => $orderLineKey));
        if (isset($aOrderline['pixiGetOrderlineResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'])) {
            $aOrderline = $aOrderline['pixiGetOrderlineResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
            return $aOrderline;
        } else {
            return false;
        }
    }

    /**
     * @param $picklistID
     * @return mixed
     */
    function getPicklistDetails($picklistID)
    {
        $stock = $this->oProxy->pixiShippingGetPicklistDetails(array('PicklistKey' => $picklistID, 'FilterBinGroup' => '060 Standard', 'FilterLocation' => '001'));
        $stock = $stock['pixiShippingGetPicklistDetailsResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
        return $stock;
    }

    /**
     * @param $ean
     * @param $ItemNrSuppl
     * @return array|string
     */
    function getItemInfo($ean, $ItemNrSuppl = null)
    {
        $stock = $this->oProxy->pixiGetItemInfo(array('EANUPC' => $ean, 'ItemNrSuppl' => $ItemNrSuppl));

        if (isset($stock['pixiGetItemInfoResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'])) {
            $stock = $stock['pixiGetItemInfoResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];
            $itemInfo = $this->oProxy->pixiGetItemStockBins(array('EAN' => $ean, 'ItemNrSuppl' => $ItemNrSuppl));
            $itemInfo = $itemInfo['pixiGetItemStockBinsResult']['SqlRowSet']['diffgram']['SqlRowSet1']['row'];

            $aItemInfo = array('ItemInfo' => $itemInfo);

            return array_merge($stock, $aItemInfo);
        } else {
            return "Keine Einträge gefunden.";
        }
    }
}