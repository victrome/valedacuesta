<?php

  class Custom {
    public function afterCustomLoads(){
	    define('START_APP_NAME',	'Site');
	    define('START_APP_ACTION',	'index'); 
    }
    public function afterSystemLoads($vf){
      if ($vf->is_WP) {
      	
      } else {
        
      }
      
    }
    public function afterDefineLoads($vf){
      
    }
    public function afterAppLoads($vf){
      
    }
  } 
?>