<?php

require_once '../Status.php';
require_once '../Crypt.php';

class StatusTest extends PHPUnit_Framework_TestCase {

    private $status;

    public function setUp(){
        parent::setUp();
    }

    public function tearDown(){
        parent::tearDown();
        unset($this->status);
    }

    public function testConstruct_createFile_success(){
        $path = 'temp/writeable/status.json';
        $this->status = new Status($path);
        $this->assertTrue(file_exists($path));
    }

    /**
     * @expectedException Exception
     */
    public function testConstruct_createFileNotWriteable(){
        $path = 'temp/notWriteable/status.json';
        $this->status = new Status($path);
        $this->assertFalse(file_exists($path));
    }

    /**
     * @expectedException Exception
     */
    public function testConstruct_fileNotWriteable(){
        $path = 'temp/notWriteable/status.json';
        $this->status = new Status($path);
        $this->assertFalse(file_exists($path));
    }

    public function testSave_SaveNextWakeup() {
        $time = time();
        $path = 'temp/writeable/status.json';
        $this->status = new Status($path);

        $this->status->nextWakeup = $time;
        $this->status->save();

        $file = file_get_contents($path);
        $fileContainsTime = preg_match('/' .$time . '/', $file) ? true : false;

        $this->assertTrue($fileContainsTime);
    }

    public function testSave_SaveSleepers() {
        $path = 'temp/writeable/status.json';
        $this->status = new Status($path);

        $this->status->sleepers = array(
            'sleeper1' => 'Sleeper One',
            'sleeper2' => 'Sleeper Two',
        );
        $this->status->save();

        $file = file_get_contents($path);
        $fileContainsSleeper1 = preg_match('/sleeper1/', $file) ? true : false;
        $fileContainsSleeper2 = preg_match('/sleeper2/', $file) ? true : false;

        $this->assertTrue($fileContainsSleeper2);
        $this->assertTrue($fileContainsSleeper2);
    }

    public function testSaveAndLoad(){

        $nextWakeup = time();
        $sleepers = array(
            'sleeper1' => 'Sleeper One',
            'sleeper2' => 'Sleeper Two',
        );

        $path = 'temp/writeable/status.json';
        $this->status = new Status($path);

        $this->status->sleepers = $sleepers;
        $this->status->nextWakeup = $nextWakeup;
        $this->status->save();

        $this->status = new Status($path);
        $this->status->load();

        $this->assertEquals($this->status->sleepers, $sleepers);
        $this->assertEquals($this->status->nextWakeup, $nextWakeup);
    }

    public function testAutomaticLoad(){

        $nextWakeup = time();
        $sleepers = array(
            'sleeper1' => 'Sleeper One',
            'sleeper2' => 'Sleeper Two',
        );

        $path = 'temp/writeable/status.json';
        $this->status = new Status($path);
        $this->status->sleepers = $sleepers;
        $this->status->nextWakeup = $nextWakeup;
        $this->status->save();

        $this->status = new Status($path);
        $this->assertEquals($this->status->sleepers, $sleepers);
        $this->assertEquals($this->status->nextWakeup, $nextWakeup);
    }
}