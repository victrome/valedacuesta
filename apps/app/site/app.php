<?php

if (!defined('PROTECT')) { // DO NOT REMOVE
    exit('NO ACCESS');
}

class Site extends controllerApp {
    public function __construct(){
       
    }
    public function index() {
        $app_model = $this->model('defaultModel');
        $app_view = [];
        $this->view('index', $app_view);
    }

    

}

?>
