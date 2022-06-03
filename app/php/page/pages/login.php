<?php
$form = json_decode(file_get_contents('/var/www/html/assets/forms/login.json'), true, 512, JSON_UNESCAPED_UNICODE);
//var_dump($form);
require('form.php');
