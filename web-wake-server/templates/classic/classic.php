<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta name="robots" content="noindex, nofollow">

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <title>Wakeup</title>
</head>
<body>
<form id="wakeupForm" method="post" action="index.php">
    <?php foreach($this->getSleepers() as $sleeperKey => $sleeperName): ?>
        <button class="submit" type="button" name="sleeper" value="<?php echo $sleeperKey; ?>">wakeup <?php echo $sleeperName; ?></button>
    <?php endforeach; ?>
    <input type="hidden" name="csrf" value="<?php echo $this->getCSRF() ?>" />
    <input id="sleeper" type="hidden" name="sleeper" value="" />
    <input type="hidden" name="send" value="1" />
</form>
<script type="text/javascript" src="/web-wake-server/templates/classic/classic.js"></script>
</body>
</html>
