<?php

class CsrfSave {

    /**
     * generate CSRF hash
     */
    public function setCSRF(){
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
    public function checkCSRF(){
        if($_POST['csrf'] != $_SESSION['csrf']){
            $this->error('invalid csrf');
        }
    }
}