<?php

class Status {

    /**
     * @var array List of entities to wake
     */
    public $sleepers = array();

    /**
     * @var int timestamp of next wakeup (set by client)
     */
    public $nextWakeup = 0;

    /**
     * @var string path to status file
     */
    protected $filePath = '';

    public function __construct($filePath){
        $this->filePath = $filePath;
        $this->checkFile();
        $this->loadFromFile();
    }

    /**
     * save object to file
     */
    public function save(){
        $this->saveToFile();
    }

    /**
     * populate this from file
     */
    public function load(){
        $this->loadFromFile();
    }

    /**
     * @param $data populate from json
     */
    public function loadFromJson($data){
        if(isset($data['sleepers'])){
            $this->sleepers =$data['sleepers'];
        }

        if(isset($data['nextWakeup'])){
            $this->nextWakeup = $data['nextWakeup'];
        }
    }

    /**
     * check if file exist or creates it
     */
    protected function checkFile(){
        if(!$this->fileExists()){
            $this->createFile();
        }
        $this->isWriteable();
    }

    /**
     * @return bool file exists
     */
    protected function fileExists(){
        if(file_exists($this->filePath)){
            return true;
        }
        return false;
    }

    /**
     * @throws Exception create file
     */
    protected function createFile(){
        $fp = @fopen($this->filePath, "w");
        if($fp){
            fwrite($fp, '');
            fclose($fp);
        } else {
            throw new Exception('can\'t create file');
        }
    }

    /**
     * @return bool file is writeable
     * @throws Exception
     */
    protected function isWriteable(){
        if(is_writeable($this->filePath)){
            return true;
        } else {
            throw new Exception('can\'t write to file');
        }
    }

    /**
     * save to file
     */
    protected function saveToFile(){
        $json = Crypt::encrypt(json_encode($this));
        file_put_contents($this->filePath, $json);
    }

    /**
     * populate this from file
     */
    protected function loadFromFile(){
        $data = json_decode(Crypt::decrypt(file_get_contents($this->filePath)), true);
        $this->loadFromJson($data);
    }

}