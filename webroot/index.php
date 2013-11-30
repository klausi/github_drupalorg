<?php

file_put_contents('/home/klausi/temp.txt', print_r($_POST, TRUE) . "\n\n", FILE_APPEND);
