<?php
if (!defined('PROTECT')) { // DO NOT REMOVE
    exit('NO ACCESS');
}
/**
 * APP MODEL Class.
 * Class required class to access function`s model
 * @author Victor Mendes
 */
require_once PATH_CONFIG . "database.php";

class modelApp extends VF_DBconnect
{
    private $VF_table_db = array();
    private $VF_select_db = array();
    private $VF_update_db = "";
    private $VF_delete_db = "";
    private $VF_order_db = "";
    private $VF_group_db = "";
    private $VF_limit_db = "";
    private $VF_having_db = "";
    private $VF_debug_db = "";
    private $VF_where_db = array();
    private $VF_returnsDb;
    private $VF_last_id;
    private $VF_joins = array();
    private $VF_dbConnectionString = "defaultConnection";
    /**
     * <b>Connection</b> Should never be used
     * @return defaultConnection();
     */
    private function VF_conn()
    {
        return (call_user_func(array("modelApp", $this->VF_dbConnectionString)));
    }

    public function setConnection($VF_connection)
    {
        $this->VF_dbConnectionString = $VF_connection;
    }
    /**
     * Select fields from database<BR>
     * Exemple: <i>$this->select("ID");</i>
     * @param String $VF_select_q field name
     */
    protected function select($VF_select_q)
    {
        $this->VF_select_db[] = $VF_select_q;
    }
    /**
     * set table to query <BR>
     * Exemple: <i>$this->from("Table");</i>
     * @param String $VF_table_q table/view name
     */
    protected function from($VF_table_q)
    {
        $this->VF_table_db[] = $VF_table_q;
    }
    /**
     * set a table join (left join)
     * @param  String $VF_table_q table name
     * @param  String $VF_on      ON query
     */
    protected function left_join($VF_table_q, $VF_on)
    {
        $this->VF_joins[] = "LEFT JOIN " . $VF_table_q . " ON " . $VF_on;
    }
    /**
     * set a table join (right join)
     * @param  String $VF_table_q table name
     * @param  String $VF_on      ON query
     */
    protected function right_join($VF_table_q, $VF_on)
    {
        $this->VF_joins[] = "RIGHT JOIN " . $VF_table_q . " ON " . $VF_on;
    }
    /**
     * set a table join (inner join)
     * @param  String $VF_table_q table name
     * @param  String $VF_on      ON query
     */
    protected function inner_join($VF_table_q, $VF_on)
    {
        $this->VF_joins[] = "INNER JOIN " . $VF_table_q . " ON " . $VF_on;
    }
    /**
     * set where clause to query<BR>
     * When you use more than once it takes 'and' to join where clauses<BR>
     * Exemple: <i>$this->where("ID > 1");</i>
     * @param String $VF_where_q where clause
     */
    protected function where($VF_where_q, $VF_where_w = "")
    {
        if (count($this->VF_where_db) == 0) {
            $VF_prefix = "";
        } else {
            $VF_prefix = "and ";
        }
        if (is_string($VF_where_w) && $VF_where_w != "") {
            $VF_where_w = " '" . $VF_where_w . "'";
        }
        $this->VF_where_db[] = $VF_prefix . $VF_where_q . $VF_where_w;
    }
    /**
     * set where clause to query<BR>
     * When you use more than once it takes 'or' to join where clauses<BR>
     * Exemple: <i>$this->where_or("ID > 1");</i>
     * @param String $VF_where_q where clause
     */
    protected function where_or($VF_where_q, $VF_where_w = "")
    {
        if (count($this->VF_where_db) == 0) {
            $VF_prefix = "";
        } else {
            $VF_prefix = "or ";
        }
        if (is_string($VF_where_w) && $VF_where_w != "") {
            $VF_where_w = " '" . $VF_where_w . "'";
        }
        $this->VF_where_db[] = $VF_prefix . $VF_where_q . $VF_where_w;
    }
    /**
     * set order by clause to query<BR>
     * Exemple: <i>$this->orderby("ID asc");</i>
     * @param String $VF_order_q order clause
     */
    protected function orderby($VF_order_q)
    {
        $this->VF_order_db = $VF_order_q;
    }
    /**
     * set limit clause to query<BR>
     * Exemple: <i>$this->limit("10");</i>
     * @param String $VF_limit_q limit clause
     */
    protected function limit($VF_limit_q)
    {
        $this->VF_limit_db = $VF_limit_q;
    }
    /**
     * set group by clause to query<BR>
     * Exemple: <i>$this->groupby("ID");</i>
     * @param String $VF_groupby_q group by clause
     */
    protected function groupby($VF_groupby_q)
    {
        $this->VF_group_db = $VF_groupby_q;
    }
    /**
     * set having clause to query<BR>
     * Exemple: <i>$this->having("ID > 10");</i>
     * @before groupby()
     * @param String $VF_having_q having clause
     */
    protected function having($VF_having_q)
    {
        $this->VF_having_db = $VF_having_q;
    }
    private function set_debug_query($VF_debug)
    {
        $this->VF_debug_db = $VF_debug;
    }
    private function get_select()
    {
        $VF_select = $this->VF_select_db;
        return ($VF_select);
    }
    private function get_from()
    {
        $VF_table = $this->VF_table_db;
        return ($VF_table);
    }
    private function get_where()
    {
        $VF_where = $this->VF_where_db;
        return ($VF_where);
    }
    private function get_orderby()
    {
        $VF_orderby = $this->VF_order_db;
        //$this->VF_order_db = null;
        return ($VF_orderby);
    }
    /**
     * Sent a query to database<BR>
     * Exemple: <i>$this->db_query("select id, name from bot_names");</i>
     * @param String $VF_query SQL Query
     * @param Boolean $VF_successLog insert a log if the query has success (defined as false)
     */
    protected function db_query($VF_query, $VFparams = array(), $VF_successLog = false)
    {
        $VF_resultDb = new ResultModel(null, null, null);
        try {
            $VF_tb = $this->VF_conn()->prepare($VF_query);
            foreach($VFparams as $VFparamKey => $VFparam){
                if(is_array($VFparam)){
                    $VF_tb->bindParam($VFparamKey, $VFparam['value'], $VFparam['PDO']);
                } else {
                    $VF_tb->bindParam($VFparamKey, $VFparam);
                }
            }
            $VF_select = $VF_tb->execute();
            $VF_resultDb = new ResultModel($VF_tb, $this->VF_conn()->lastInsertId(), $VF_query);
            if ($VF_successLog == true) {
                $VF_string = "SUCCESS SQL - " . $VF_query;
                $this->create_dbLog("SQL SUCCESS", $VF_string);
            }
        } catch (PDOException $VF_e) {
            $VF_resultDb = new ResultModel(null, null, $VF_query);
            $VF_string = "SQL SELECT ERROR- " . $VF_e->getMessage();
            $this->create_dbLog("SQL ERROR", $VF_string);
        }
        return ($VF_resultDb);
    }
    /**
     * Sent a select query to database<BR>
     * Exemple: <i>$this->db_select("id");</i>
     * @param Bolean $VF_cleanFields clean defined params (select, from, where, group by...) (defined as true)
     * @param Boolean $VF_successLog insert a log if the query has success (defined as false)
     */
    protected function db_select($VF_cleanFields = true, $VF_successLog = false)
    {
        $VF_array_select = $this->get_select();
        $VF_array_from = $this->get_from();
        $VF_array_where = $this->get_where();
        $VF_resultDb = null;
        if (count($VF_array_select) > 0 and count($VF_array_from) > 0) {
            $VF_selectquery = implode(", ", $VF_array_select);
            $VF_fromquery = implode(", ", $VF_array_from);
            if (count($VF_array_where) > 0) {
                $VF_wherequery = " where " . implode(" ", $VF_array_where);
            } else {
                $VF_wherequery = "";
            }
            if ($this->get_orderby() != '') {
                $VF_order = " order by " . $this->get_orderby();
            } else {
                $VF_order = "";
            }
            if ($this->VF_limit_db != '') {
                $VF_limit = " limit " . $this->VF_limit_db;
            } else {
                $VF_limit = "";
            }
            if ($this->VF_group_db != '') {
                $VF_group = " GROUP BY " . $this->VF_group_db;
                if (isset($this->VF_having_db)) {
                    $VF_having = " HAVING " . $this->VF_having_db;
                } else {
                    $VF_having = "";
                }
            } else {
                $VF_group = "";
                $VF_having = "";
            }
            $VF_joinsString = " " . implode(" ", $this->VF_joins);
            $VF_query = 'select ' . $VF_selectquery . ' from ' . $VF_fromquery . $VF_joinsString . $VF_wherequery . $VF_group . $VF_having . $VF_order . $VF_limit;
            try {
                $VF_tb = $this->VF_conn()->prepare($VF_query);
                $VF_select = $VF_tb->execute();
                $VF_resultDb = new ResultModel($VF_tb, null, $VF_query);
                if ($VF_successLog == true) {
                    $VF_string = "SUCCESS SQL - " . $VF_query;
                    $this->create_dbLog("SQL SUCCESS", $VF_string);
                }
            } catch (PDOException $VF_e) {
                $VF_resultDb = new ResultModel(null, null, $VF_query);
                $VF_string = "SQL SELECT ERROR- " . $VF_e->getMessage();
                $this->create_dbLog("SQL ERROR", $VF_string);
            }
        } else {
            $VF_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $VF_string);
        }
        if ($VF_cleanFields == true) {
            $this->VF_table_db = array();
            $this->VF_select_db = array();
            $this->VF_where_db = array();
            $this->VF_order_db = "";
            $this->VF_group_db = "";
            $this->VF_limit_db = "";
            $this->VF_having_db = "";
            $this->VF_joins = array();
        }
        return ($VF_resultDb);
    }
    /**
     * Sent a insert query to database<BR>
     * Exemple:<BR>
     * <pre>
     *     <i>$bot_data = array('name' => 'Jean', 'robot' => victro);</i>
     *     <i>$this->db_insert("owner", $bot_data);</i>
     * </pre>
     * @param String $VF_table table name
     * @param Array $VF_data array with field name and value (check exemple)
     * @param Boolean $VF_successLog insert a log if the query has success (defined as false)
     */
    protected function db_insert($VF_table, $VF_data, $VF_successLog = false)
    {
        $VF_resultDb = new ResultModel(null, null, null);

        $VF_fields_array = array();
        foreach ($VF_data as $VF_key => $VF_dat) {
            $VF_fields_array[] = "{$VF_key}";
            $VF_values_array[] = ":{$VF_key}";
        }
        $VF_fields = implode(", ", $VF_fields_array);
        $VF_values = implode(", ", $VF_values_array);
        $VF_query = "insert into {$VF_table} ({$VF_fields}) values ({$VF_values})";
        $VF_query2 = "insert into {$VF_table} ({$VF_fields}) values ({$VF_values})";
        try {
            $VF_conn = $this->VF_conn();
            $VF_tb = $VF_conn->prepare($VF_query);
            $VF_valueinsert = array();
            foreach ($VF_data as $VF_key => $VF_dat) {
                if (is_numeric($VF_dat)) {
                    $VF_valueinsert[$VF_key] = $VF_dat;
                    $VF_tb->bindParam(":" . $VF_key, $VF_valueinsert[$VF_key], PDO::PARAM_INT);
                    $VF_query2 = str_replace(':' . $VF_key, $VF_dat, $VF_query2);
                } else {
                    $VF_string = ($VF_dat);
                    $VF_tb->bindParam(":" . $VF_key, $VF_string, PDO::PARAM_STR);
                    $VF_query2 = str_replace(':' . $VF_key, "'{$VF_dat}'", $VF_query2);
                    unset($VF_string);
                }
            }
            unset($VF_valueinsert);
            $VF_select = $VF_tb->execute();
            $VF_resultDb = new ResultModel($VF_tb, $this->VF_conn()->lastInsertId($VF_table), $VF_query2);
            if ($VF_successLog == true) {
                $VF_string = "SUCCESS SQL - " . $VF_query2;
                $this->create_dbLog("SQL SUCCESS", $VF_string);
            }
        } catch (PDOException $VF_e) {
            $VF_resultDb = new ResultModel($VF_tb, null, $VF_query);
            $VF_string = "SQL SELECT ERROR- " . $VF_e->getMessage();
            $this->create_dbLog("SQL ERROR", $VF_string);
        }
        $this->VF_where_db = array();
        return ($VF_resultDb);
    }
    /**
     * Sent a update query to database<BR>
     * To database security to send a update query is necessary to use $this->where("") method
     * Exemple:<BR>
     * <pre>
     *     <i>$this->where("ID = 1")</i>
     *     <i>$bot_data = array('name' => 'Jean', 'robot' => victro);</i>
     *     <i>$this->db_update("owner", $bot_data);</i>
     * </pre>
     * @param String $VF_table table name
     * @param Array $VF_data array with field name and value (check exemple)
     * @param Boolean $VF_successLog insert a log if the query has success (defined as false)
     */
    protected function db_update($VF_table, $VF_data, $VF_successLog = false)
    {
        $VF_array_where = $this->get_where();
        $VF_resultDb = new ResultModel(null, null, null);

        if (count($VF_array_where) > 0) {
            $VF_wherequery = " where " . implode(" ", $VF_array_where);
            $VF_fields_array = array();
            foreach ($VF_data as $VF_key => $VF_dat) {
                $VF_fields_array[] = "{$VF_key} = :{$VF_key}";
            }
            $VF_fields = implode(", ", $VF_fields_array);
            $VF_query = "update {$VF_table} set {$VF_fields} {$VF_wherequery}";
            $VF_query2 = "update {$VF_table} set {$VF_fields} {$VF_wherequery}";
            try {
                $VF_tb = $this->VF_conn()->prepare($VF_query);
                foreach ($VF_data as $VF_key => $VF_dat) {
                    if (is_numeric($VF_dat)) {
                        $VF_tb->bindValue(":" . $VF_key, $VF_dat, PDO::PARAM_INT);
                        $VF_query2 = str_replace(':' . $VF_key, $VF_dat, $VF_query2);
                    } else {
                        $dbString = ($VF_dat);
                        $VF_tb->bindValue(":" . $VF_key, $dbString, PDO::PARAM_STR);
                        $VF_query2 = str_replace(':' . $VF_key, "'{$VF_dat}'", $VF_query2);
                    }
                }
                $VF_select = $VF_tb->execute();
                $VF_resultDb = new ResultModel($VF_tb, null, $VF_query);
                if ($VF_successLog == true) {
                    $VF_string = "SUCCESS SQL - " . $VF_query2;
                    $this->create_dbLog("SQL SUCCESS", $VF_string);
                }
            } catch (PDOException $VF_e) {
                $VF_resultDb = new ResultModel(null, null, $VF_query);
                $VF_string = "SQL SELECT ERROR- " . $VF_e->getMessage();
                $this->create_dbLog("SQL ERROR", $VF_string);
            }
            $this->set_debug_query($VF_query2);
        } else {
            $VF_string = "DENIED SQL: You need to specify at least one where clause";
            $this->create_dbLog("DENIED SQL", $VF_string);
        }
        $this->VF_where_db = array();
        return ($VF_resultDb);
    }
    /**
     * Sent a delete query to database<BR>
     * To database security to send a delete query is necessary to use $this->where("") method
     * Exemple:<BR>
     * <pre>
     *     <i>$this->where("ID = 1")</i>
     *     <i>$this->db_delete("owner");</i>
     * </pre>
     * @param String $VF_table table name
     * @param Boolean $VF_successLog insert a log if the query has success (defined as false)
     */
    protected function db_delete($VF_table, $VF_successLog = false)
    {
        $VF_array_where = $this->get_where();
        $VF_resultDb = new ResultModel(null, null, null);

        if (count($VF_array_where) > 0) {
            $VF_wherequery = " where " . implode(" ", $VF_array_where);
            $VF_query = "delete from {$VF_table} {$VF_wherequery}";
            try {
                $VF_tb = $this->VF_conn()->prepare($VF_query);
                $VF_select = $VF_tb->execute();
                $VF_resultDb = new ResultModel($VF_tb, null, $VF_query);
                if ($VF_successLog == true) {
                    $VF_string = "SUCCESS SQL - " . $VF_query;
                    $this->create_dbLog("SQL SUCCESS", $VF_string);
                }
            } catch (PDOException $VF_e) {
                $VF_resultDb = new ResultModel(null, null, $VF_query);
                $VF_string = "SQL SELECT ERROR- " . $VF_e->getMessage();
                $this->create_dbLog("SQL ERROR", $VF_string);
            }
        } else {
            $VF_string = "DENIED SQL: You need to specify at least one where clause";
            $this->create_dbLog("DENIED SQL", $VF_string);
        }

        $this->VF_where_db = array();
        return ($VF_resultDb);
    }
    private function create_dbLog($VF_type, $VF_string)
    {
        $VF_logTable = "(" . implode(",", $this->VF_table_db) . "";
        $VF_method = debug_backtrace()[1]['function'];
        $VF_paginalink = debug_backtrace()[1]['file'];
        $VF_pagina = explode("\\", $VF_paginalink);
        $VF_log = str_replace("[METHOD]", $VF_method, $VF_string);
        $VF_log = str_replace("[TABLE]", $VF_logTable, $VF_string);
        $VF_log = str_replace("[PAGE]", $VF_pagina[count($VF_pagina) - 1], $VF_log);
        $VF_datalog = date('Y-m-d h:i:s');
        //$VF_tb = $VF_tb->execute();
        $_SESSION['victro_log_session'][] = array('TYPE' => $VF_type, 'DATE' => $VF_datalog, 'MESSAGE' => $VF_log);
    }

    /**
     * Filter INPUT or GET<BR>
     * This method filters INPUT or GET params
     * Exemple: <i>$bot_value = $this->input("NAME", "POST"); -- Filter as POST</i>
     * Exemple: <i>$bot_value = $this->input("NAME", "GET"); -- Filter as GET</i>
     * Exemple: <i>$bot_value = $this->input("NAME", "GET_POST"); -- Try to filter as POST if nothing is found try to filter as GET </i>
     * Exemple: <i>$bot_value = $this->input("NAME", "POST_GET"); -- Try to filter as GET if nothing is found try to filter as POST </i>
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

    protected function get_page_link($VF_appName, $VF_appAction, $VF_array = false){
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
                require_once APP_VIEW . APP_ACTION . '.php';
                $VF_content_file = ob_get_clean();
                return ($VF_content_file);
            }
        } else {
            return (false);
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
}

//Result Class

class ResultModel{
        private $victro_last_id = 0;
        private $victro_returnsDb;
        private $victro_debug_db = "";

        function __construct($victro_returnsDb, $victro_last_id ,$victro_debug_db) {
            $this->victro_last_id = $victro_last_id;
            $this->victro_returnsDb = $victro_returnsDb;
            $this->victro_debug_db = $victro_debug_db;
        }

        /**
        * get result count from query<BR>
        * Exemple: <i>$bot_queryNum = $this->get_count();</i>
        * @return Integer number of query result
        */
        public function get_count(){
            if(is_object($this->victro_returnsDb)){
                $victro_return = $this->victro_returnsDb->rowCount();
                return($victro_return);
            }
            return(false);
        }
        /**
            * get result as object<BR>
            * Exemple: <i>$bot_queryNum = $this->get_fetch();</i>
            * @return Array->Object array as rows, object as row
            */
        public function get_fetch(){
            if(is_object($this->victro_returnsDb)){
                $victro_return = $this->victro_returnsDb->fetchAll(PDO::FETCH_CLASS);
                return($victro_return);
            }
            return(false);
        }
        /**
            * get result as array<BR>
            * Exemple: <i>$bot_queryNum = $this->get_fetch();</i>
            * @return Array->Object array as rows, array as row
            */
        public function get_fetch_array(){
            if(is_object($this->victro_returnsDb)){
                $victro_return = $this->victro_returnsDb->fetchAll(PDO::FETCH_ASSOC);
                return($victro_return);
            }
            return(false);
        }
        /**
            * get the last result as object<BR>
            * Exemple: <i>$bot_queryNum = $this->get_row();</i>
            * @return Object result (only 1 result)
            */
        public function get_row(){
            if(is_object($this->victro_returnsDb)){
                $victro_return = $this->victro_returnsDb->fetch(PDO::FETCH_OBJ);
                return($victro_return);
            }
            return(false);
        }
        /**
            * get the last result as array<BR>
            * Exemple: <i>$bot_queryNum = $this->get_row_array();</i>
            * @return Array result (only 1 result)
            */
        public function get_row_array(){
            if(is_object($this->victro_returnsDb)){
                $victro_return = $this->victro_returnsDb->fetch(PDO::FETCH_ASSOC);
                return($victro_return);
            }
            return(false);
        }
        /**
            * get the last ID inserted<BR>
            * Exemple: <i>$bot_query = $this->get_last_id();</i>
            * @return Integer last ID inserted
            */
        public function get_last_id(){
            return($this->victro_last_id);
        }
        /**
            * get last query sent to database<BR>
            * Exemple: <i>$bot_query = $this->debug_query();</i>
            * @return String last query
            */
        public function debug_query() {
            return $this->victro_debug_db;
        }
        public function super(){
            return $this->victro_returnsDb;
        }
      }
