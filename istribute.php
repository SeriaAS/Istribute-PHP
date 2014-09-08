<?php

function istribute_autoloader($class) {
	$class = explode('\\', $class);
	$class = implode(DIRECTORY_SEPARATOR, $class);
	$filename = __DIR__.'/'.$class.'.php';
	if (file_exists($filename)) {
		include($filename);
	}
}

spl_autoload_register('istribute_autoloader');
