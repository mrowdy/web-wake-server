<?php

require_once 'Status.php';
require_once 'Crypt.php';
require_once 'CsrfSave.php';

class WebWakeServer {

    /**
     * @var bool output errors and logs
     */
    public $verbose = true;

    /**
     * @var string stores CSRF hash
     */
    protected $csrf = '';

    /**
     * @var array default options
     */
    protected $default = array(
        'sleepers'    => array(),
        'status-file' => 'status.json',
        'template'    => 'classic',
        'verbose'     => false,
        'crypt-key'   => '',
    );

    protected $configFile = 'config.php';

    protected $status;
    protected $csrfSave;
    protected $config = array();


    public function __construct(){

        session_start();
        $config = $this->getConfig();
        $this->config = array_merge($this->default, $config);
        $this->parseConfig($this->config);
        $this->status = new Status($this->config['status-file'], $this->config['crypt-key']);
        $this->csrfSave = new CsrfSave();
        $this->checkActions();

        $this->csrfSave->setCSRF();
        $this->showTemplate();
    }

    protected function getConfig(){
        if(file_exists($this->configFile )){
            $config = array();
            require_once $this->configFile;
            return $config;
        } else {
            $this->error('config file missing. rename config-sample.php to config.php');
        }
    }

    /**
     * Parse config array
     */
    protected function parseConfig($config){
        if(!is_array($config)){
            $this->error('config array needed');
        }

        if(isset($config['verbose'])){
            $this->verbose = $config['verbose']?true:false;
        }

        if(isset($config['crypt-key'])){
            Crypt::setKey($config['crypt-key']);
        }
    }

    protected function checkActions(){
        if(isset($_GET['action'])){
            if($_GET['action'] == 'update-status'){
                $this->updateStatus();
            }
            if($_GET['action'] == 'get-status'){
                $this->getStatus();
            }
        } elseif(isset($_POST['action'])){
            if($_POST['action'] == 'send-view'){
                $this->processView();
            }
        }
    }

    protected function updateStatus(){
        if(isset($_POST['status'])){
            $newStatus = json_decode($_POST['status'], true);
            $this->status->loadFromJson($newStatus);
            $this->status->save();
        }
        die();
    }

    /**
     * echos status-file as json
     */
    protected function getStatus(){
        header('Content-type: application/json');
        echo file_get_contents($this->config['status-file']);
        die();
    }


    /**
     * Process sent view form
     */
    protected function processView(){
        if(isset($_POST['sleeper']) && array_key_exists($_POST['sleeper'], $this->status->sleepers)){
            $this->status->sleepers[$_POST['sleeper']] = 1;
        }
        $this->status->save();
    }

    /**
     * @return array get list of entities to wake
     */
    protected function getSleepers(){
        return $this->status->sleepers;
    }

    /**
     * @param $message store new error
     */
    protected function error($message){
        if($this->verbose){
            die($message);
        }
        die();
    }

    /**
     * output template
     */
    protected function showTemplate(){
        $templateFile = sprintf('%s/templates/%s/%s.php', __DIR__, $this->config['template'], $this->config['template']);
        if(file_exists($templateFile)){
            include $templateFile;
        }
    }


}