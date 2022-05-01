<?php
if (!defined('PROTECT')) { // DO NOT REMOVE
    exit('NO ACCESS');
}
class controllerApp
{

    /**
     * Load an external controller file<BR>
     * Example: <i>$this->extend_controller("basic", array('ID', 1));</i>
     * @param String $VF_name_controller name of extended controllers file
     * @param Array $VF_data array with data that you want to send to controller
     * @return boolean if file does not found it returns false
     */

    protected function extend_controller($VF_name_controller = "", $VF_data = array())
    {
        if ($VF_name_controller == "") {
            $VF_name_controller = APP_ACTION;
        }
        if (is_array($VF_data) and count($VF_data) > 0) {
            extract($VF_data, EXTR_PREFIX_SAME, "app");
        }
        if (file_exists(APP_CONTROLLER . $VF_name_controller . '.php')) {
            require_once APP_CONTROLLER . $VF_name_controller . '.php';
            return (true);
        } else {
            return (false);
        }
    }
    /**
     * Load a model file<BR>
     * Example: <i>$bot_model = $this->model("basic", array('ID', 1));</i>
     * @param String $VF_name_model name of models file
     * @param Array $VF_data array with data that you want to send to model
     * @return Object if file does not found it returns false else returns model object class
     */
    protected function model($VF_name_model = "", $VF_data = array())
    {
        require_once 'model.php';
        if ($VF_name_model == "") {
            $VF_name_model = APP_ACTION;
        }
        if (is_array($VF_data) and count($VF_data) > 0) {
            extract($VF_data, EXTR_PREFIX_SAME, "app");
        }
        if (file_exists(APP_MODEL . $VF_name_model . '.php')) {
            require_once APP_MODEL . $VF_name_model . '.php';
            $VF_act_app = new $VF_name_model;
            if (is_subclass_of($VF_act_app, 'modelApp')) {
                return ($VF_act_app);
            } else
            if (is_subclass_of($VF_act_app, APP_ACTION)) {
                return ($VF_act_app);
            } else {
                return (false);
            }
        } else {
            return (false);
        }
    }
    /**
     * Load a view file<BR>
     * This method can return a include view or html of itself
     * Example: <i>$this->view("basic", array('ID', 1), false);</i>
     * Example: <i>$bot_html = $this->view("basic", array('ID', 1), true);</i>
     * @param String $VF_name_view name of views file
     * @param Array $VF_data array with data that you want to send to view
     * @param Boolean $VF_mode set if you want to require (false) or html (true) of view`s called
     * @return Object if file does not found it returns false else if param 3 is false it requires the view else if param 3 is true it returns the html of this view
     */
    protected function view($VF_name_view = "", $VF_data = array(), $VF_mode = false)
    {
        if ($VF_name_view == "") {
            $VF_name_view = APP_ACTION;
        }
        if (is_array($VF_data) and count($VF_data) > 0) {
            extract($VF_data, EXTR_PREFIX_SAME, "app");
        }
        if (file_exists(APP_VIEW . $VF_name_view . '.php')) {
            if ($VF_mode == false) {
                require_once APP_VIEW . $VF_name_view . '.php';
            } else {
                ob_start();
                require_once APP_VIEW . $VF_name_view . '.php';
                $VF_content_file = ob_get_clean();
                return ($VF_content_file);
            }
        } else {
            return (false);
        }
    }

    /**
     * Filter INPUT or GET<BR>
     * This method filters INPUT or GET params
     * Example: <i>$bot_value = $this->input("NAME", "POST"); -- Filter as POST</i>
     * Example: <i>$bot_value = $this->input("NAME", "GET"); -- Filter as GET</i>
     * Example: <i>$bot_value = $this->input("NAME", "GET_POST"); -- Try to filter as POST if nothing is found try to filter as GET </i>
     * Example: <i>$bot_value = $this->input("NAME", "POST_GET"); -- Try to filter as GET if nothing is found try to filter as POST </i>
     * @param String $VF_name name Param GET or POST
     * @param String $VF_type type of filter (POST, GET, GET_POST, POST_GET)
     * @param String $VF_filter type of filter (check PHP documentation of 'filter_input')
     * @return String if nothing is found it returns false else it return a value it can be (String, Boolean, Integer...)
     */
    protected function input($VF_name, $VF_type = "POST",$is_array = false, $VF_filter = "default")
    {
        $VF_type_array = explode("_", $VF_type);
        $VF_value = false;
        $VF_filter = "FILTER_" . mb_strtoupper($VF_filter, "UTF-8");
        foreach ($VF_type_array as $VF_type_array2) {
            $VF_type = mb_strtoupper($VF_type_array2, "UTF-8");
            if($is_array){
                $VF_value1 = filter_input(constant("INPUT_" . $VF_type), $VF_name, constant($VF_filter), FILTER_REQUIRE_ARRAY);
            } else {
                $VF_value1 = filter_input(constant("INPUT_" . $VF_type), $VF_name, constant($VF_filter));
            }
            if ($VF_value1 != false and $VF_value1 != null) {
                $VF_value = $VF_value1;
            }
        }
        return ($VF_value);
    }

    /**
     * Set Session<BR>
     * Set a session in security mode
     * @param String $VF_name Session name
     * @param Object $VF_value session data (integer, array, string...)
     * @param Boolean $VF_empty clean session before set the new value
     * @param Boolean $VF_unique session with unique values (check array_unique in PHP documentation)
     */
    protected function set_session($VF_name, $VF_value, $VF_empty = false, $VF_unique = false)
    {
        if ($VF_unique) {
            $_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)] = array_unique($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)]);
        }
        if ($VF_empty) {
            unset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)]);
        }
        if (is_array($VF_value)) {
            if (isset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)]) and !is_array($_SESSION[base64_encode(date('m') . $VF_id_bot . $VF_name)])) {
                $_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)] = array();
            }
            foreach ($VF_value as $VF_key => $VF_dat) {
                $_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)][$VF_key] = $VF_dat;
            }
        } else {
            $_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)] = $VF_value;
        }
    }
    /**
     * Get Session<BR>
     * Get a session in security mode
     * @param String $VF_name Session name
     * @return Object If session is not set returns false else return Session's value
     */
    protected function get_session($VF_name)
    {
        if (isset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)])) {
            return ($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)]);
        } else {
            return (0);
        }
    }
    /**
     * Delete Session<BR>
     * Delete a session in security mode
     * @param String $VF_name Session name
     */
    protected function unset_session($VF_name)
    {
        if (isset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)])) {
            unset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)]);
        }
    }
    /**
     * Unset value of a Session<BR>
     * If session is an array it will search a value then unset it.
     * @param String $VF_name Session name
     * @param String $VF_search value search
     */
    protected function unset_value_session($VF_name, $VF_search)
    {
        if (isset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)])) {
            if (($VF_key = array_search($VF_search, $_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)])) !== false) {
                unset($_SESSION[base64_encode(date('m') . APP_CLASS . $VF_name)][$VF_key]);
                return (true);
            } else {
                return (false);
            }
        }
    }

    public function get_page_link($VF_appName, $VF_appAction, $VF_array = false){
        GLOBAL $_VF;
        $VF_urlReturn = [];
        if ($_VF->is_WP) {
          $VF_args = array(
              'post_type' => 'vfpages',
              'meta_query' => array(
                  array(
                      'key' => 'VF_App',
                      'value' => $VF_appName,
                  ),
                  array(
                      'key' => 'VF_Action',
                      'value' => $VF_appAction,
                  ),
              ),
          );
          $my_query = new WP_Query($VF_args);
          if ($my_query->have_posts()) {
              $VF_pages = $my_query->posts;
              foreach ($VF_pages as $VF_page) {
                  $VF_urlReturn[] = esc_url(get_permalink($VF_page->ID)); //wordpress function
              }
          }
        } else {
          $routes = $_VF->getRoutes();
          if($routes){
            if($_VF->is_routeFirst || $_VF->is_routeOnly){
              foreach($routes as $route){
                if($route['App'] == $VF_appName && $route['Action'] == $VF_appAction){
                  $VF_urlReturn[] = $_VF->base_url().urldecode($route['Route']);
                }
              }
              if(!$_VF->is_routeOnly){
                $VF_urlReturn[] = $_VF->base_url().strtolower($VF_appName)."/".$VF_appAction;
              }
            } else {
              $VF_urlReturn[] = $_VF->base_url().strtolower($VF_appName)."/".$VF_appAction; 
              foreach($routes as $route){
                if($route['App'] == $VF_appName && $route['Action'] == $VF_appAction){
                  $VF_urlReturn[] = $_VF->base_url().urldecode($route['Route']);
                }
              }
            } 
          } else {
            $VF_urlReturn[] = $_VF->base_url().strtolower($VF_appName)."/".$VF_appAction;
          }
        }
        if($VF_array && count($VF_urlReturn) > 0){
          return $VF_urlReturn;
        } else if(count($VF_urlReturn) > 0 && !$VF_array){
          return $VF_urlReturn[0];
        } else {
          return false;
        }
      }
    /**
     * Gets a Helper<BR>
     *
     * @param String $VF_name Helper folder
     */
    protected function helper($VF_name)
    {
        if (file_exists(PATH_HELPER . $VF_name . '/functions.php')) {
            define('HELPER_ASSETS', $this->get_page_link(APP_RUNNING, "assets_helper")."?h=".$VF_name."&f=");
            require_once PATH_HELPER . $VF_name . '/functions.php';
            if(class_exists($VF_name)){
              $VF_helper = new $VF_name;
              return $VF_helper;
            } else {
              return false;
            }
        }
        return false;
      }
    public function assets_helper()
    {
        if (isset($_GET['f']) && isset($_GET['h'])) {
            $VF_file = $_GET['f'];
            $VF_helper = $_GET['h'];
            $VF_final_file = PATH_HELPER . $VF_helper . "/assets/" . $VF_file;
            $VF_file_info = pathinfo($VF_final_file);
            $VF_images = array("png", "PNG", "jpg", "JPG", "gif", "GIF", "JPEG", "jpeg");
            $VF_fonts = array("woff2", "woff", "ttf", "eot");
            $VF_showFile = false;
            if ($VF_file_info['extension'] == 'css') {
                header("Content-type: text/css");
                $VF_showFile = true;
            } else if ($VF_file_info['extension'] == 'js') {
                header("x§: application/javascript");
                $VF_showFile = true;
            } else if (in_array($VF_file_info['extension'], $VF_images)) {
                header('Content-type:image/' . $VF_file_info['extension']);
                $VF_showFile = true;
            } else if (in_array($VF_file_info['extension'], $VF_fonts)) {
                header('Content-type:font/' . $VF_file_info['extension']);
                $VF_showFile = true;
            } else {
                echo $VF_file_info['extension'];
            }
            if (file_exists($VF_final_file) && $VF_showFile) {
                echo file_get_contents($VF_final_file);
            }
        }
    }
    public function assets()
    {
      GLOBAL $_VF;
      $file = implode("/", $_VF->params);
        if (strlen($file) > 0 && strpos($file, '.') !== false) {
            $VF_file = $file;
            $VF_final_file = APP_VIEW . "/assets/" . $VF_file;
            $VF_file_info = pathinfo($VF_final_file);
            $VF_mime = mime_content_type($VF_final_file);
            $VF_images = array("png", "PNG", "jpg", "JPG", "gif", "GIF", "JPEG", "jpeg");
            $VF_fonts = array("woff2", "woff", "ttf", "eot");
            $VF_showFile = false;
            if ($VF_file_info['extension'] == 'css') {
                header("Content-type: text/css");
                $VF_showFile = true;
            } else if ($VF_file_info['extension'] == 'js') {
                header("Content-type: application/javascript");
                $VF_showFile = true;
            } else if ($VF_file_info['extension'] == 'svg') {
                header("Content-type: image/svg+xml");
                $VF_showFile = true;
            } else if (in_array($VF_file_info['extension'], $VF_images)) {
                header('Content-type:image/' . $VF_file_info['extension']);
                $VF_showFile = true;
            } else if (in_array($VF_file_info['extension'], $VF_fonts)) {
                header('Content-type:font/' . $VF_file_info['extension']);
                $VF_showFile = true;
            } else {
                echo $VF_file_info['extension'];
            }
            if (file_exists($VF_final_file) && $VF_showFile) {
                echo file_get_contents($VF_final_file);
            }
        }
    }
    private function getFileMimeType($file) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $type = finfo_file($finfo, $file);
      finfo_close($finfo);
          
  
      if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
          $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
          if ($returnCode === 0 && $secondOpinion) {
              $type = $secondOpinion;
          }
      }
  
      if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
          require_once 'upgradephp/ext/mime.php';
          $exifImageType = exif_imagetype($file);
          if ($exifImageType !== false) {
              $type = image_type_to_mime_type($exifImageType);
          }
      }
  
      return $type;
    }
}
