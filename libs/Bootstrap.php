<?php

/**
 * Initialisierung der Anwwendung
 * und laden aller erforderlichen Komponenten
 *
 * @author: Marlon Böhland
 * @access: public
 */
class Bootstrap
{
    private $_url = null;
    private $_controller = null;

    private $_controllerPath = 'controllers/';
    private $_modelPath = 'models/';
    private $_errorFile = 'error.php';
    private $_defaultFile = 'index.php';

    /**
     * Bootstrap initialisieren
     * @return bool
     */
    public function init()
    {
        $this->_getUrl();

        // Laden des default Controllers
        if (empty($this->_url[0])) {
            $this->_loadDefaultController();
            return false;
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();
    }

    /**
     * (Optional) Eigener Controller Pfad
     * @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Eigner Model Pfad
     * @param string $path
     */
    public function setModelPath($path)
    {
        $this->_modelPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Custom Pfad für das Error File
     * @param string $path
     */
    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }

    /**
     * (Optional) Standardpfad setzen
     * @param string $path
     */
    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }

    /**
     * 'url' aus $_GET auslesen
     */
    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
    }

    /**
     * Wenn kein Controller via GET übergeben wird.
     */
    private function _loadDefaultController()
    {
        // Standardmäßig wird die index.php geladen
        require $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new Index();
        $this->_controller->index();
    }

    /**
     * Wenn ein Controller via GET übergeben wird und dieser
     * existiert, wird er geladen.
     *
     * @return boolean|string
     */
    private function _loadExistingController()
    {
        $file = $this->_controllerPath . $this->_url[0] . '.php';

        if (file_exists($file)) {
            require $file;

            $cName = ucfirst($this->_url[0]);
            $this->_controller = new $cName;
            $this->_controller->loadModel($this->_url[0], $this->_modelPath);
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * Falls eine Methode via URL übergeben wurde...
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);

        // Prüfen ob die Methode existiert
        if ($length > 1) {
            if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
            }
        }

        // Je nach Anzahl der Parameter werden unterschiedliche Signaturen erzeugt.
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;

            case 3:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;

            case 2:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}();
                break;

            default:
                $this->_controller->index();
                break;
        }
    }

    /**
     * Anzeigen der Fehlerseite falls nichts existiert
     * @return boolean
     */
    private function _error()
    {
        require $this->_controllerPath . $this->_errorFile;
        $this->_controller = new Error();
        $this->_controller->index();
        exit;
    }
}