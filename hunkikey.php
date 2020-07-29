<?php

//decode by QQ:270656184 http://www.yunlu99.com/
define('DS', DIRECTORY_SEPARATOR);
defined('hunki_PATH') or define('hunki_PATH', __DIR__ . DS);
define('ROOT_PATH', dirname(hunki_PATH) . DS);
define('HUNKI_PATH', hunki_PATH . 'hunkihtml' . DS);
define('SCRIPT_PATH', hunki_PATH . 'script' . DS);
define('LIBS_PATH', hunki_PATH . 'libs' . DS);
define('hunki_FILE', hunki_PATH . 'hunki.php');
Session_start();
function gethunkiJson()
{
	if (isset($_SESSION['hunki_json'])) {
		return $_SESSION['hunki_json'];
	} else {
		$file = hunki_PATH . 'hunkidtkkey.data';
		if (file_exists($file)) {
			$str_data = file_get_contents($file);
			$_SESSION['hunki_json'] = json_decode($str_data, true);
			return $_SESSION['hunki_json'];
		}
		return '';
	}
}