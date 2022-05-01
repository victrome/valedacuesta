<?php

  class VF_PHP {
    public $folder;
    public $app;
    public $action;
    public $is_WP = false;
    public $is_routeFirst = true;
    public $is_routeOnly = false;
    private $checkRoute = false;
    public Array $params = [];
    private Array $routes = [];
    
    public function __construct(){
      if (defined( 'WP_PLUGIN_DIR' ) ) {
        $this->is_WP = true;
      }
    }
    
    public function getSystemInfo(){
      if ($this->is_WP) {
        global $wpdb;
        $vfSystem = ['table_prefix' => $wpdb->prefix."_VF_", 'type' => 'WP - VF-PHP', 'type_id' => 2, 'path' => plugin_dir_path(__FILE__)];
      } else {
        $vfSystem = ['table_prefix' => 'VF_', 'type' => 'VF-PHP', 'type_id' => 1, 'path' => ''];
      }
      return $vfSystem;
    } 
    protected function isSubFolder(){
      $root = $_SERVER['DOCUMENT_ROOT'];
      $filePath = dirname(__FILE__);
      $folders = str_replace($root, "", $filePath);
      if ($root == $filePath) {
          return false; // installed in the root
      } else {
          return substr_count($folders,"/");  // installed in a subfolder or subdomain
      }
    }
    public function getVFParams(){
      $uri = strtok($_SERVER["REQUEST_URI"], '?');
      $uri_arr = explode("/", $uri);
      $isSubFolder = $this->isSubFolder();
      if($isSubFolder){
        for($i = 0; $i <= $isSubFolder; $i++){
          unset($uri_arr[$i]);
        }
      } else {
        unset($uri_arr[0]);
      }
      $uri_arr = array_filter($uri_arr);
      $uri_arr = array_values($uri_arr);
      if($this->is_routeFirst || $this->checkRoute || $this->is_routeOnly){
        $routeApp = $this->getRoute($uri_arr);
        if($routeApp){
          $this->app = $routeApp['App']; 
      		$this->action = $routeApp['Action'];
          $this->folder = strtolower($routeApp['App']);
          
          $urlParams = explode("/", urldecode($routeApp['Route']));
          for($i = 0; $i < count($urlParams); $i++){
            unset($uri_arr[$i]);
          }
      		$uri_arr = array_values($uri_arr);
          $this->params = $uri_arr;
          return;
        }
        if($this->is_routeOnly){
          return;
        }
      } 
      if(isset($uri_arr[0])){
        $this->folder = $uri_arr[0];
        $this->action = isset($uri_arr[1]) ? $uri_arr[1] : "index";
        unset($uri_arr[0]);
        unset($uri_arr[1]);
        $uri_arr = array_values($uri_arr);
        $this->params = $uri_arr;
      } else if(defined('START_APP_NAME') && strlen(START_APP_NAME) > 0){
        $this->folder = strtolower(START_APP_NAME);
        $this->action = defined('START_APP_ACTION') ? START_APP_ACTION : "index";
      } else {
        exit("VF-PHP couldn't find a app to start. please navigate to app's folder or define START_APP_NAME to set up a default app");
      }
      $this->app = ucfirst(strtolower($this->folder));
    }
    public function startApp(){
      $this->getVFParams();
      require_once(PATH_CONFIG."controller.php");

      if(file_exists(PATH_APP .$this->folder. '/app.php')){
        require_once(PATH_APP .$this->folder. '/app.php');
        
        if (class_exists($this->app)) { // check if the class exists
            $VF_appOpen = new $this->app; // class is started
            if (is_subclass_of($VF_appOpen, 'controllerApp')) { // check if the extend exists
                if (method_exists($VF_appOpen, $this->action)) { // check if the method exists
                    define('APP_VIEW', PATH_APP.$this->folder."/view/");
                    define('APP_MODEL', PATH_APP.$this->folder."/model/");
                    define('APP_CONTROLLER', PATH_APP.$this->folder."/controller/");
                    define('APP_ACTION', $this->action);
                    define("APP_RUNNING", $this->app);
                    if($this->is_WP){
                      define('APP_ASSETS', $VF_appOpen->get_page_link(APP_RUNNING, "assets")."?p=");
                    } else {
                      define('APP_ASSETS', $VF_appOpen->get_page_link(APP_RUNNING, "assets")."/");
                    }
                    call_user_func_array(array($VF_appOpen, $this->action), $this->params);
                    
                    if(file_exists(PATH_PLUGIN.'custom.php')){ 
                      GLOBAL $customClass;
                      if(method_exists($customClass, 'afterAppLoads')) { $customClass->afterAppLoads($this); }
                    }
                } else { // else of method exists
                  //http_response_code(404);
                  //vf error //exit("Route {$_VF->action} not found");
                }
            } else { // else of check extents
                //vf error
            }
        } else { // else of class exists
            //vf error
        }
      } else {
        if(!$this->is_routeFirst && !$this->checkRoute && !$this->is_routeOnly){
          $this->checkRoute = true;
          $this->startApp();
        } else {
          //vf error //exit("Could not find app ".$_VF->folder);
        }
        
      }         
    }
    public function startAppParams($app, $action){
      if(!defined('PROTECT')){ exit("ERROR VF00000");}
  		$this->app = $app; 
  		$this->action = $action;
  		$this->folder = strtolower($app);
  		define("APP_RUNNING", $this->app);
  		define("APP_PAGE_RUNNING", $this->action);
  		define("APP_FOLDER_RUNNING", $this->folder);
      if(file_exists(PATH_APP .$this->folder. '/app.php')){
  			$this->startApp();
      }
    }
    public function base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
        else $base_url = 'http://localhost/';

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }
    public function route($app, $action, $route){
      $route = urlencode($route);
      if(!in_array($route ,$this->routes)){
        $this->routes[$route] = ['App' => $app, 'Action' => $action, 'Route' => $route];
      } else {
        // vf error
      }
    }
    public function getRoutes(){
      return $this->routes;
    }
    private function getRoute($url){
      while(count($url) > 0){
        $route = implode("/", $url);
        $route = urlencode($route);
        if(isset($this->routes[$route])){
          return $this->routes[$route];
        }
        array_pop($url);
      }
      return false;
    }
  }
  
  $_VF = new VF_PHP();
?>