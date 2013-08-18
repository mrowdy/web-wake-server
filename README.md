web-wake-server
================================

WebWake is a web interface for wake-on-lan based on php.

Usage
-------------------------

Create config.php from config-sample.php, include WebWakeServer.php and create an instance

    require_once 'web-wake-server/WebWakeServer.php';
    $wakeup = new WebWakeServer();


Demo
-------------------------
View demo at: http://webwake.slemgrim.com/
Status at http://webwake.slemgrim.com/action=get-status (not encrypted)
