<?php
	$vfSystem = $_VF->getSystemInfo();

	define("PATH_PLUGIN",		    $vfSystem['path']);
	define('TABLE_PREFIX', 	    	$vfSystem['table_prefix']);
	define('VF_TYPE',           	$vfSystem['type']);
	define('PATH_CONFIG',		    PATH_PLUGIN."system/");
	define('PATH_APPS',			    PATH_PLUGIN."apps/");
	define('PATH_HELPER', 	    	PATH_APPS."helper/");
	define('PATH_APP', 	        	PATH_APPS."app/");
	define('PATH_WP',           	PATH_PLUGIN."wp/");
	define('PATH_WP_CONTROLLER',	PATH_WP."controller/");
	define('PATH_WP_SCRIPT',    	PATH_WP."script/");
	define('PATH_WP_CSS',       	PATH_WP."wp/css/");
	define('VERSION', 	        	"1.1");
	define('PROTECT',			    true);

?>