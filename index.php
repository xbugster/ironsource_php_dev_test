<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */
define('ROOT_DIR', __DIR__);
require_once('vendor/autoload.php');

ob_start();

$app = new Core\App(ROOT_DIR);
$app->run();
#var_dump('end of app run.');

ob_end_flush();