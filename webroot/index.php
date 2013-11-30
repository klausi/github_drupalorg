<?php

require '../settings.php';

file_put_contents($logfile, print_r(json_decode($_POST['payload']), TRUE) . "\n\n", FILE_APPEND);
