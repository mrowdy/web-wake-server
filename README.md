web-wake-server
================================

WebWake is a web interface for wake-on-lan based on php.

Usage
-------------------------

Create config.php from config-sample.php, include WebWakeServer.php and create an instance

    <?php

    require_once 'web-wake-server/WebWakeServer.php';
    new WebWakeServer();

Config Example
-------------------------

in config.php

    <?php

    $config = array(
        'verbose'     => true,
        'crypt-key'   => '"§%"§$&"§1234',
        'debug'       => false,
    );

Options:

<table>
  <tr>
    <th>Key</th><th>Description</th><th>Default</th>
  </tr>
  <tr>
    <td>status-file</td><td>location and name of status file (has to be writable)</td><td>status.json</td>
  </tr>
  <tr>
    <td>template</td><td>currently only 'classic'</td><td>classic</td>
  </tr>
  <tr>
    <td>verbose</td><td>show messages</td><td>false</td>
  </tr>
  <tr>
    <td>crypt-key</td><td>key for encrypt/decrypt (has to be the same on client)</td><td></td>
  </tr>
  <tr>
    <td>debug</td><td>debug mode disables encryption</td><td>false</td>
  </tr>


</table>

Demo
-------------------------
View demo at: http://webwake.slemgrim.com/

Status at http://webwake.slemgrim.com?action=get-status (not encrypted)
