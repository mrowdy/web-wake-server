<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="cleartype" content="on">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,900" rel="stylesheet" type="text/css">
    <link rel="stylesheet/less" type="text/css" href="/web-wake-server/templates/classic/less/classic.less" />
    <script src="/web-wake-server/templates/classic/less.js" type="text/javascript"></script>
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title>WebWake - Classic</title>
</head>
<body>
<div id="content-wrap">
    <div id="content">
        <?php if(count($this->getSleepers()) == 0): ?>
            <span>Sorry, no client registered</span>
        <?php else: ?>
            <form id="wakeupForm" method="post" action="index.php">
                <div class="grid-row grid-group">
                    <?php foreach($this->getSleepers() as $sleeperKey => $sleeperName): ?>
                        <div class="grid-col span_1_of_3">
                            <div class="sleeperWrap">
                                <div id="<?php echo $sleeperKey; ?>" class="sleeper">
                                    <div class="sleeperFace faceFront">
                                        <h2><?php echo ucfirst($sleeperKey); ?></h2>
                                        <button class="submit" type="button" name="sleeper" value="<?php echo $sleeperKey; ?>">wakeup</button>
                                    </div>
                                    <div class="sleeperFace faceBottom">
                                        <div class="clock">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="csrf" value="<?php echo $this->csrfSave->getCSRF() ?>" />
                <input type="hidden" name="action" value="send-view" />
            </form>
        <?php endif;?>
    </div>
</div>
<script type="text/javascript" src="/web-wake-server/templates/classic/classic.js"></script>
</body>
</html>
