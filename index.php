<?php 
if (!defined( 'WP_PLUGIN_DIR' ) ) {
  if(file_exists('custom.php')){ 
    require_once('custom.php');
    $customClass = new Custom();
    if(method_exists($customClass, 'afterCustomLoads')) { $customClass->afterCustomLoads(); } 
  }
  require_once('system.php');
  if(file_exists('custom.php')){ 
    if(method_exists($customClass, 'afterSystemLoads')) { $customClass->afterSystemLoads($_VF); } 
  }
  require_once('define.php');
  if(file_exists('custom.php')){ 
    if(method_exists($customClass, 'afterDefineLoads')) { $customClass->afterDefineLoads($_VF); } 
  }
  $_VF->startApp();
}