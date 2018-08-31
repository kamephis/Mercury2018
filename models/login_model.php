<?php

/**
 * Login Model
 * User: Marlon
 * Date: 22.07.2017
 * Time: 23:50
 */
class Login_Model extends Model
{

    private $_sUsername = null;
    private $_sPassword = null;
    private $_sTargetApp = null;
    private $_sRedirectURL = null;

    /**
     * login constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // Login
    public function run()
    {
        $this->checkAuth($_POST['userPasswd']);
    }

    /**
     * Benutzerauthentifizierung
     * @param $sUserAccount
     */
    private function checkAuth($sUserAccount)
    {
        $sUserAccount = explode('-', $sUserAccount);
        $sUserName = strtolower($sUserAccount[0]); // Unterstützung der Anwender, falls Groß/Kleinschreibung verwendet wird
        $password = $sUserAccount[1];

        /**
         * pruefen ob der Benutzer berechtigt ist
         * Voraussetzung fuer den Zugriff auf das Backend ist ein passendes access_level
         */
        $sql = $this->db->prepare("SELECT iUser.UID, iUser.Username, iUser.name, iUser.vorname,iUser.kuerzel, iUser.Passwd, iUser.RegDate, iUser.kuerzel, iUser.access_level, iUser.dept FROM iUser WHERE Username = :sUserName");

        // Pruefen ob der Benutzername existiert
        $sql->execute(array('sUserName' => $sUserName));
        $totalRows = $sql->rowCount();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        // Entschlüsselung des Kennworts. Wenn erfolgreich: Registrieren der Session Variablen
        if ($result['Passwd'] == $this->decryptPassword($password, $result['RegDate']) && $totalRows > 0) {

            session_id($result['SESSION_ID']);
            session_start();
            // Ausloggen aktiver user
            session_destroy();

            // Neue Session starten
            Session::init();

            // Registrierung der Session Werte
            Session::set('UID', $result['UID']);
            Session::set('vorname', $result['vorname']);
            Session::set('name', $result['name']);
            Session::set('kuerzel', $result['kuerzel']);

            // Rechte in die Session schreiben.
            Session::set('access_level', $result['access_level']);

            // Session ID in die DB schreiben - falls ein user sich mehrmals anmeldet
            try{
                $sqlSaveSessionID = "UPDATE iUser SET SESSION_ID = :session_id WHERE UID = :UID";

                $stmSaveSessionID = $this->db->prepare($sqlSaveSessionID);
                $stmSaveSessionID->execute(array('UID' => $result['UID'], 'session_id' => session_id()));
            }catch(PDOException $e){
                echo $e->getMessage();
            }

            // Prüfung erfolgreich -> Weiterleitung an die jeweilige Zielseite
            $this->redirect2TargetLocation($result['dept']);
            echo "<script>location.replace('" . $this->getSRedirectURL() . "');</script>";

        } else {
            // Zugriff verweigert
            header('Location: ' . URL . 'login?msg=401');
            }

    }

    /**
     * Weiterleitung, je nachdem wer sich anmeldet.
     * @param $dept
     */
    private function redirect2TargetLocation($dept)
    {
        // Auslesen der Subdomain (Parameter für Weiterleitung)
        $hostUrl = explode('.', $_SERVER['HTTP_HOST']);
        $sSubdomain = $hostUrl[0];

        $sUrl = "http://" . $sSubdomain . ".stoffpalette.com/";

        // Unterscheidung nach Subdomain.
        // Wichtig, wenn ein User gleichzeitig Picker und Zuschnitt ist.
        switch ($sSubdomain) {
            case 'mercury':
                switch ($dept) {
                    case 'teamleiter':
                        $this->setSRedirectURL($sUrl . 'backend');
                        break;
                    case 'kus':
                        $this->setSRedirectURL($sUrl . 'kundenservice');
                        break;
                }
                break;

            case 'zuschnitt':
            case 'pick':
                if ($dept == 'picker' || $dept == 'teamleiter') {
                    switch ($sSubdomain) {
                        case 'zuschnitt':
                            $this->setSRedirectURL($sUrl . 'scanArt');
                            break;
                        case 'pick':
                            $this->setSRedirectURL($sUrl . 'scanLocation');
                            break;
                    }
                }
                break;
        }
        $_SESSION['redirectUrl'] = $this->getSRedirectURL();
    }

    /**
     * Erzeugen eines verschlüsselten Kennworts
     * @param $password
     * @return string
     */
    protected function encryptPassword($password)
    {
        $myDate = new MyDateTime();
        /**
         * Aktueller Timestamp wird beim Aufruf der Funktion eingelesen und
         * dann als Salt-Value in der Session Variablen regDate gespeichert.
         */
        $timeStamp = $myDate->getTimestamp();
        $sid = session_id();

        if (isset($sid)) {
            $_SESSION['regDate'] = $timeStamp;
        } else {
            echo "Es ist keine Session aktiv!";
        }
        return hash('sha256', $password . $timeStamp);
    }

    /**
     * Entschlüsseln des übergebenen Kennworts zur Prüfung
     * @param $password
     * @param $regDate
     * @return string
     */
    protected function decryptPassword($password, $regDate)
    {
        // Pruefung des eingegebenen Kennworts auf dessen Richtigkeit
        $decrypt = hash('sha256', $password . $regDate);
        return $decrypt;
    }

    /******************************************************
     * Setter / Getter
     *****************************************************/

    /**
     * @return null
     */
    public function getSUsername()
    {
        return $this->_sUsername;
    }

    /**
     * @param null $sUsername
     */
    public function setSUsername($sUsername)
    {
        $this->_sUsername = $sUsername;
    }

    /**
     * @return null
     */
    public function getSPassword()
    {
        return $this->_sPassword;
    }

    /**
     * @param null $sPassword
     */
    public function setSPassword($sPassword)
    {
        $this->_sPassword = $sPassword;
    }

    /**
     * @return null
     */
    public function getSTargetApp()
    {
        return $this->_sTargetApp;
    }

    /**
     * @param null $sTargetApp
     */
    public function setSTargetApp($sTargetApp)
    {
        $this->_sTargetApp = $sTargetApp;
    }

    /**
     * @return null
     */
    public function getSRedirectURL()
    {
        return $this->_sRedirectURL;
    }

    /**
     * @param null $sRedirectURL
     */
    public function setSRedirectURL($sRedirectURL)
    {
        $this->_sRedirectURL = $sRedirectURL;
    }
}

/**
 * Class MyDateTime
 *
 * Datumsfunktionen
 * User: Marlon Böhland
 * Date:
 */
class MyDateTime extends DateTime
{
    public function setTimestamp($timestamp)
    {
        $date = getdate(( int )$timestamp);
        $this->setDate($date['year'], $date['mon'], $date['mday']);
        $this->setTime($date['hours'], $date['minutes'], $date['seconds']);
    }

    /**
     * Getter: Timestamp
     * @return string
     */
    public function getTimestamp()
    {
        return $this->format('U');
    }
}