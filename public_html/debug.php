<?php
require $_SERVER['APP_ROOT'] . 'bootstrap.php';

setcookie('debug', $_debugModeCode, time()+60*60*24*30*3); # 3 months
