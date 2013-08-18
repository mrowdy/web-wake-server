<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet/less" type="text/css" href="/web-wake-server/templates/classic/less/classic.less" />
    <script src="/web-wake-server/templates/classic/less.js" type="text/javascript"></script>

    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <title>WebWake - Classic</title>
</head>
<body>
<div id="header">
    <header  class="wrap">
        <h1>WebWake</h1>
    </header>
</div>
<div id="content" class="wrap">
    <?php if(count($this->getSleepers()) == 0): ?>
        <span>Sorry, no client registered</span>
    <?php else: ?>
        <form id="wakeupForm" method="post" action="index.php">
            <?php foreach($this->getSleepers() as $sleeperKey => $sleeperName): ?>
                <button class="submit sleeper" type="button" name="sleeper" value="<?php echo $sleeperKey; ?>"><?php echo ucfirst($sleeperKey); ?></button>
            <?php endforeach; ?>
            <input type="hidden" name="csrf" value="<?php echo $this->csrfSave->getCSRF() ?>" />
            <input id="sleeper" type="hidden" name="sleeper" value="" />
            <input type="hidden" name="action" value="send-view" />
        </form>
    <?php endif;?>

</div>

<script type="text/javascript" src="/web-wake-server/templates/classic/classic.js"></script>
</body>
</html>
