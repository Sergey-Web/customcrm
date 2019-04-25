<?php

define("BASEPATH", dirname(__DIR__));
$app = App\System\App::getInstance(BASEPATH);

return $app;