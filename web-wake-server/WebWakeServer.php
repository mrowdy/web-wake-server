<?php

class WebWakeServer {

    /**
     * @var bool output errors and logs
     */
    public    $verbose    = false;

    /**
     * @var array list of entities to wake
     */
    protected $sleepers   = array();

    /**
     * @var string location of status file
     */
    protected $statusFile = '';

    /**
     * @var string name of template
     */
    protected $template   = '';

    /**
     * @var string stores CSRF hash
     */
    protected $csrf       = '';

    /**
     * @var array default options
     */
    protected $default    = array(
        'sleepers'    => array(),
        'status-file' => 'status.json',
        'template'    => 'classic',
        'verbose'     => false
    );

    public function __construct($config = null){

        session_start();

        $config = array_merge($this->default, $config);

        $this->parseConfig($config);

        if($this->isStatusRequest()){
            $this->echoStatus();
        }

        if($this->isStatusUpdate()){
            $this->statusUpdate();
        }

        else if($this->isSent()){
            $this->checkCSRF();
            $this->processForm();
        }

        $this->setCSRF();
        $this->showTemplate();
    }



    /**
     * generate CSRF hash
     */
    protected function setCSRF(){
        $csrf = md5($_SERVER['HTTP_USER_AGENT']);
        $_SESSION['csrf'] = $csrf;
        $this->csrf = $csrf;
    }

    /**
     * @return string get previos generated CSRF hash
     */
    public function getCSRF(){
        return $_SESSION['csrf'];
    }

    /**
     * comprare post CSRF hash with session CSRF hash
     */
    protected function checkCSRF(){
        if($_POST['csrf'] != $_SESSION['csrf']){
            $this->error('invalid csrf');
        }
    }

    /**
     * @return array get list of entities to wake
     */
    public function getSleepers(){
        return $this->sleepers;
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

        if(isset($config['sleepers']) && count($config['sleepers']) > 0){
            $this->sleepers = $config['sleepers'];
        } else {
            $this->error('no sleepers specified');
        }

        if(isset($config['status-file'])){
            $this->statusFile = $config['status-file'];
            if(!file_exists($this->statusFile)){
                $this->error('status file missing');
            }
            if(!is_writeable($this->statusFile)){
                $this->error('can\'t write to statusFile');
            }
        } else {
            $this->error('no statusFile specified');
        }

        if(isset($config['template'])){
            $this->template = basename($config['template']);
        } else {
            $this->error('no template specified');
        }
    }

    /**
     * @return bool wakeup form is sent
     */
    protected function isSent(){
        if(isset($_POST['send']) && $_POST['send'] == 1){
            return true;
        }
        return false;
    }



    /**
     * process form and save to status
     */
    protected function processForm(){
        if(isset($_POST['sleeper']) && array_key_exists($_POST['sleeper'], $this->sleepers)){
            $sleeper = $_POST['sleeper'];

            $status = $this->loadStatus();
            if(!$status instanceof stdClass){
                $status = new stdClass();
            }
            $status->$sleeper = '1';
            $this->saveStatus($status);

        } else {
            $this->error('invalid sleeper');
        }
    }

    /**
     * @return stdClass content of status file
     */
    protected function loadStatus(){
        return json_decode(file_get_contents($this->statusFile));
    }

    /**
     * save status to status file
     * @param $string
     */
    protected function saveStatus($string){
        file_put_contents($this->statusFile, json_encode($string));
    }

    /**
     * @return bool status file requested?
     */
    protected function isStatusRequest(){
        if(isset($_GET['get-status']) && $_GET['get-status'] == 1){
            return true;
        }
        return false;
    }

    /**
     * output status file
     */
    protected function echoStatus(){
        header('Content-type: application/json');
        echo file_get_contents($this->statusFile);
        die();
    }

    /**
     * @return bool received new status
     */
    protected function isStatusUpdate(){
        if(isset($_GET['update-status']) && $_GET['update-status'] == 1){
            return true;
        }
        return false;
    }

    /**
     * save new status
     */
    protected function statusUpdate(){
        $newStatus = isset($_POST['new-status'])?$_POST['new-status']:false;
        if($newStatus){
            file_put_contents($this->statusFile, $newStatus);
        }
        $this->error();
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
        $templateFile = sprintf('%s/templates/%s/%s.php', __DIR__, $this->template, $this->template);
        if(file_exists($templateFile)){
            include $templateFile;
        }
    }
}