<?php 
if (!defined('PROTECT')) { // DO NOT REMOVE
   exit('NO ACCESS');
}
   class VF_DBconnect {

      public function defaultConnection(){
         $VF_server = "192.168.0.121"; 
         $VF_database = "vf"; 
         $VF_user = "v"; 
         $VF_pass = "12345"; 
         $VF_driver = "mysql"; 
         //$VF_driver_array = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".DB_CHARSET);
         $VF_driver_array = array();
         try{ 
            $VF_conn = new PDO("{$VF_driver}:host={$VF_server};dbname={$VF_database}","{$VF_user}","{$VF_pass}", $VF_driver_array); 
            $VF_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         return($VF_conn); 
         } catch(PDOException $VF_e){ 
            echo '<center><h1>DATABASE ERROR</h1></center>';
            echo $VF_e->getMessage(); 
            exit(); 
         } 
         return(false);
      }


      public function wordpressConnection(){
         $VF_server = DB_HOST; 
         $VF_database = DB_NAME; 
         $VF_user = DB_USER; 
         $VF_pass = DB_PASSWORD; 
         $VF_driver = "mysql"; 
         $VF_driver_array = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".DB_CHARSET);
         try{ 
            $VF_conn = new PDO("{$VF_driver}:host={$VF_server};dbname={$VF_database}","{$VF_user}","{$VF_pass}", $VF_driver_array); 
            $VF_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         return($VF_conn); 
         } catch(PDOException $VF_e){ 
            echo '<center><h1>DATABASE ERROR</h1></center>'; //$VF_e->getMessage(); 
            exit(); 
         } 
         return(false);
      }
       
   } 
?>