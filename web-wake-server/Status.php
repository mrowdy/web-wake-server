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

    /**
     * @var string stores encryption key
     */
    protected $encryptionKey = '';

    public function __construct($filePath, $key){
        $this->filePath = $filePath;
        $this->encryptionKey = $key;
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
        $json = $this->encrypt(json_encode($this));
        file_put_contents($this->filePath, $json);
    }

    /**
     * populate this from file
     */
    protected function loadFromFile(){
        $data = json_decode($this->decrypt(file_get_contents($this->filePath)), true);
        $this->loadFromJson($data);
    }

    protected function encrypt($msg){
        if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') )
            return false;

        $key = $this->encryptionKey;
        $msg = serialize($msg);
        $iv = mcrypt_create_iv(32, MCRYPT_RAND);

        if ( mcrypt_generic_init($td, $key, $iv) !== 0 )
            return false;

        $msg = mcrypt_generic($td, $msg);
        $msg = $iv . $msg;
        $mac = $this->pbkdf2($msg, $key, 1000, 32);
        $msg .= $mac;

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $msg;
    }

    public function decrypt( $msg ) {

        if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') )
            return false;

        $key = $this->encryptionKey;
        $iv = substr($msg, 0, 32);
        $mo = strlen($msg) - 32;
        $em = substr($msg, $mo);
        $msg = substr($msg, 32, strlen($msg)-64);
        $mac = $this->pbkdf2($iv . $msg, $key, 1000, 32);

        if ( $em !== $mac )
        return false;

        if ( mcrypt_generic_init($td, $key, $iv) !== 0 )
        return false;

        $msg = mdecrypt_generic($td, $msg);
        $msg = unserialize($msg);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $msg;
    }

    public function pbkdf2( $p, $s, $c, $kl, $a = 'sha256' ) {

        $hl = strlen(hash($a, null, true));
        $kb = ceil($kl / $hl);
        $dk = '';

        for ( $block = 1; $block <= $kb; $block ++ ) {

            $ib = $b = hash_hmac($a, $s . pack('N', $block), $p, true);
            for ( $i = 1; $i < $c; $i ++ )
            $ib ^= ($b = hash_hmac($a, $b, $p, true));
            $dk .= $ib;
        }

        return substr($dk, 0, $kl);
    }
}