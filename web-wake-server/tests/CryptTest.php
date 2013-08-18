<?php

require_once '../Crypt.php';

class CryptTest extends PHPUnit_Framework_TestCase {

    public function testCrypt_TestEncryptDebugOn_NoEncrypt(){
        $message = 'Test';
        Crypt::setDebug(true);
        $result = Crypt::encrypt($message);
        $this->assertEquals($message, $result);
    }

    public function testCrypt_TestEncryptNoKey_NoEncrypt(){
        $message = 'Test';
        Crypt::setDebug(false);
        $result = Crypt::encrypt($message);
        $this->assertEquals($message, $result);
    }

    public function testCrypt_TestEncryptWithKeyAndDebug_NoEncrypt(){
        $message = 'Test';
        Crypt::setDebug(true);
        Crypt::setKey('asdasdf');
        $result = Crypt::encrypt($message);
        $this->assertEquals($message, $result);
    }

    public function testCrypt_TestEncryptWithKey_encryptetMessage(){
        $message = 'Test';
        Crypt::setDebug(false);
        Crypt::setKey('asdasdf');
        $result = Crypt::encrypt($message);
        $this->assertNotEquals($message, $result);
    }

    public function testCrypt_TestEncryptTwoTimes_differentEncryptetMessage(){
        $message = 'Test';
        Crypt::setDebug(false);
        Crypt::setKey('asdasdf');
        $result1 = Crypt::encrypt($message);
        $result2 = Crypt::encrypt($message);
        $this->assertNotEquals($message, $result1);
        $this->assertNotEquals($message, $result2);
        $this->assertNotEquals($result1, $result2);
    }

    public function testCrypt_TestEncryptDecrypt_message(){
        $message = 'Test';
        Crypt::setDebug(false);
        Crypt::setKey('asdasdf');
        $encrypted = Crypt::encrypt($message);
        $decrypted = Crypt::decrypt($encrypted);

        $this->assertEquals($message, $decrypted);
    }

    public function testCrypt_TestEncryptDecryptTwoTimes_sameMessages(){
        $message = 'Test';
        Crypt::setDebug(false);
        Crypt::setKey('asdasdf');
        $encrypted1 = Crypt::encrypt($message);
        $decrypted1 = Crypt::decrypt($encrypted1);

        $encrypted2 = Crypt::encrypt($message);
        $decrypted2 = Crypt::decrypt($encrypted2);

        $this->assertNotEquals($encrypted1, $encrypted2);
        $this->assertEquals($decrypted1, $decrypted2);
    }

}