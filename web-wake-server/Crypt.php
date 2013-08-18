<?php

class Crypt {

    /**
     * @var bool stops encrypting
     */
    protected static $debug = true;

    /**
     * @var string cryptkey
     */
    protected static $key = '';

    /**
     * @param $key set key
     */
    public static function setKey($key){
        self::$key = $key;
    }

    /**
     * @param $bool set debug
     */
    public static function setDebug($bool){
        self::$debug = $bool?true:false;
    }

    /**
     * @param $message string to encrypt
     * @return bool|string
     */
    public static function encrypt($message){
        if(!self::$debug && !empty(self::$key)){
            if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') )
                return false;

            $message = serialize($message);
            $iv = mcrypt_create_iv(32, MCRYPT_RAND);

            if ( mcrypt_generic_init($td, self::$key, $iv) !== 0 )
                return false;

            $message = mcrypt_generic($td, $message);
            $message = $iv . $message;
            $mac = self::pbkdf2($message, self::$key, 1000, 32);
            $message .= $mac;

            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
        }
        return $message;
    }

    /**
     * @param $message string to decrypt
     * @return bool|mixed
     */
    public static function decrypt( $message ) {
        if(!self::$debug && !empty(self::$key)){
            if ( ! $td = mcrypt_module_open('rijndael-256', '', 'ctr', '') )
                return false;

            $iv = substr($message, 0, 32);
            $mo = strlen($message) - 32;
            $em = substr($message, $mo);
            $message = substr($message, 32, strlen($message)-64);
            $mac = self::pbkdf2($iv . $message, self::$key, 1000, 32);

            if ( $em !== $mac )
                return false;

            if ( mcrypt_generic_init($td, self::$key, $iv) !== 0 )
                return false;

            $message = mdecrypt_generic($td, $message);
            $message = unserialize($message);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
        }
        return $message;
    }

    /**
     * Password-Based Key Derivation Function 2
     * @param $p
     * @param $s
     * @param $c
     * @param $kl
     * @param string $a
     * @return string
     */
    protected static function pbkdf2( $p, $s, $c, $kl, $a = 'sha256' ) {

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