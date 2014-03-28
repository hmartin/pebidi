<?php

exec('git pull');
sleep(1);
header("Location: http://pebidi.com/app_dev.php");