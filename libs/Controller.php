<?php

/**
 * Standardcontroller
 * @author: Marlon Böhland
 * @access: public
 */
class Controller
{
    public $msg = null;

    function __construct()
    {
        $this->view = new View();
        $this->view->binColors = Session::get('binColors');
        $this->model = new Model();

        // Zugriff prüfen
        if (isset($_GET['msg'])) {
            $this->showMessages($_GET['msg']);
        }

//        $uid = Session::get('UID');
        // Prüfen ob user bereits angemeldet
    //    $this->checkValidSession($uid);
    }

    /**
     * Prüfen ob ein user bereits angemeldet ist.
     * @param $uid
     */
    public function checkValidSession($uid){
        $sqlUser = $this->model->db->prepare("SELECT SESSION_ID FROM iUser WHERE UID = :uid");
        $sqlUser->execute(array('uid' => $uid));
        $result = $sqlUser->fetch(PDO::FETCH_ASSOC);

        if($result['UID'] != NULL){
            if(session_id() !== $result['UID']  ){
                session_destroy();
                header('Location: ' . URL . 'login?msg=401');
            }
        }
    }


    /**
     * @param $name
     * @param string $modelPath
     */
    public function loadModel($name, $modelPath)
    {
        $path = $modelPath . $name . '_model.php';

        if (file_exists($path)) {
            require $path;

            $modelName = ucfirst($name) . '_Model';
            $this->model = new $modelName();
        }
    }

    /**
     * Ausgabe von Meldungen
     * @param $msgID
     * @return bool
     */
    static function showMessages($msgID)
    {
        switch ($msgID) {
            case '401':
                View::showAlert('danger', $option = null, "Ihre Zugangsdaten sind nicht korrekt oder Sie verfügen nicht über die Berechtigung für diesen Bereich.");
                break;
            case 'logout':
                View::showAlert('success', $option = null, "Sie wurden erfoglreich abgemeldet.");
                break;

            case 'pixiImpCheck':
                View::showAlert('warning', $option = null, "Diese Pixi Pickliste wurde bereits importiert.");
                break;

            case 'pixiImpSuccess':
                View::showAlert('success', $option = null, "Die Pixi Pickliste wurde erfolgreich importiert.");
                break;

            case 'pixiImpSuccess2':
                View::showAlert('success', $option = null, "Die Pixi Pickliste wurde erfolgreich importiert!!!!.");
                break;

            case 'picklistInternCreated':
                View::showAlert('success', $option = null, "Die neue Pickliste wurde erfolgreich erstellt.");
                break;
            case 'picklistInternCreateFailed':
                View::showAlert('danger', $option = null, "Die neue Pickliste konnte <strong>nicht</strong> erstellt werden.");
                break;
            case 'MercuryTablesCleared':
                View::showAlert('success', $option = null, "Die Mercury Tabellen wurden erfolgreich geleert.");
                break;
            case 'MercuryTablesClearFailed':
                View::showAlert('danger', $option = null, "Die Mercury Tabellen konnten <strong>nicht</strong> geleert werden.");
                break;
        }
        return false;
    }
}