<?php

require_once dirname(__FILE__).'/defaultplugin.php';

class Attribute_Changer_PLugin extends phplistPlugin {

	
	//File location, selected attribute column matches, all entry data, index by id
	// $sessions;
	// $session_increment;


    $Current_Session;


	function adminMenu() {
    	return $this->pageTitles;
	}

    function __construct()
    {
        parent::__construct();

        $this->pageTitles = array( // Entries in the plugin menu of the dashboard
			'pluginpage_first' => 'Begin Data Collection',
		);
		  
		$this->topMenuLinks = array( // Entries in the top menu at the top of each page
			'pluginpage_first' => array('category' => 'subscribers'),
		);

		$this->coderoot = dirname(__FILE__).'/Attribute_Changer_Plugin/';

		$this->name = "Attribute_Changer_Plugin";
                    
		// $this->sessions = array();
		// $this->session_increment = 1;

        $this->Current_Session = null;
    }

    function New_Session() {
        if($this->Current_Session != null) {
            if(isset($this->Current_Session->file_location)) {
                if(is_file($this->Current_Session->file_location)) {
                    unlink($this->Current_Session->file_location);
                }
            }
        }
        $this->Current_Session = new Single_Session();
        return $this->Current_Session;
    }

    function close_session() {
        if($this->Current_Session != null) {
            if(isset($this->Current_Session->file_location)) {
                if(is_file($this->Current_Session->file_location)) {
                    unlink($this->Current_Session->file_location);
                }
            }
            $this->Current_Session = null;
        }
    }

    function Test_Create_Temp_Dir() {
        $temp_dir = PLUGIN_ROOTDIR.'Attribute_Changer_Plugin/temp_table_uploads/';
        if(!file_exists(PLUGIN_ROOTDIR.'Attribute_Changer_PLugin/')) {
            return false;
        }
        else if(!is_dir(PLUGIN_ROOTDIR.'Attribute_Changer_PLugin/')) {
            return false;
        }
        else{
            if(!file_exists($temp_dir)) {
                mkdir($temp_dir);
                return true;
            }
            else{
                if(is_dir($temp_dir)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
    }


    function Get_Current_User_Values($email_key) {
        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;

        $current_user_query = sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'], $email_key);
        $current_user_sql_result = Sql_Fetch_Array_Query($current_user_query);

        if(!isset($current_user_sql_result[0])) {
            return false;
        }
        if(!isset($Session->$Current_Users_Values[$email_key])) {
            $Session->$Current_Users_Values[$email_key] = array();
            $Session->$Current_Users_Values[$email_key]['id'] = $current_user_sql_result[0];
        
            foreach ($Session->attribute_list as $attribute_id => $attribute_data) {

                $Current_User_Value_Query = sprintf("select value from %s where attributeid = %d and userid = %s", $GLOBALS['tables']['user_attribute'], $attribute_id, $current_user_sql_result[0]);
                $current_attribute_return = Sql_Fetch_Row_Query($Current_User_Value_Query);

                $Session->$Current_Users_Values[$email_key][$attribute_id] = array();

                if(!$current_attribute_return[0]) {
                    continue;
                }

                else{
                    if($attribute_data['type'] == 'checkboxgroup') {
                        $exploded_current_values_ids = explode(',', $current_attribute_return[0]);

                        $Session->$Current_Users_Values[$email_key][$attribute_id] = $exploded_current_values_ids;

                    }

                    else {
                        array_push($Session->$Current_Users_Values[$email_key][$attribute_id], $current_attribute_return[0]);
                        
                    }
                }
            }
        }
        return true;
    }

    function Add_Modify_Entry($email_key, $attribute_values) {
        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;
        if($attribute_values == null || !is_array($attribute_values || count($attribute_values) == 0 )) {
            //do nothing, already have a user but theres nothing to update
        }
        else{
            if(!isset($Session->Current_Users_Values[$email_key])) {
                if(Get_Current_User_Values($email_key) == false) {
                    return false;
                } 
            }
            $new_entries = array();
            $is_new_value = false;

            foreach ($attribute_values as $attribute_id => $attribute_value_array) {

                $new_entries[$attribute_id] = array();

                
                foreach ($attribute_value_array as $numkey => $value) {
                    if(!in_array($value, $Session->Current_Users_Values[$email_key][$attribute_id])) {
                        array_push($new_entries[$attribute_id], $value);
                        $is_new_value = true;
                    }
                    
                }
            }
            if($is_new_value == true) {
                if(!isset($Session->Modify_Entry_List[$email_key])) {
                    $Session->Modify_Entry_List[$email_key] = array();
                }
                foreach ($new_entries as $attribute_id => $value_array) {
                    if(!isset($Session->Modify_Entry_List[$email_key][$attribute_id])) {
                        $Session->Modify_Entry_List[$email_key][$attribute_id] = array();
                    }
                    foreach ($value_array as $nkey => $value) {
                        if(!in_array($value, $Session->Modify_Entry_List[$email_key][$attribute_id])) {
                            array_push($Session->Modify_Entry_List[$email_key][$attribute_id], $value);
                        }
                    }
                }
            }  
        }
    }

    //attribute values array([att_id] => values), values have been test all are acceptable
    function Add_New_Entry($email_key, $attribute_values) {

        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;

        if($attribute_values == null || !is_array($attribute_values || count($attribute_values) == 0 )) {
            //just set email
            if(!isset($Session->New_Entry_List[$email_key])) {
                $Session->New_Entry_List[$email_key] = array();
            }
            //else no need to do anything
            return true;
        }

        else{
            foreach ($attribute_values as $attribute_id => $single_attribute_values) {
                if(!is_array($single_attribute_values)) {
                    //shouldnt happen
                }

                else{
                    if(!isset($Session->New_Entry_List[$email_key][$attribute_id])) {
                        $Session->New_Entry_List[$email_key][$attribute_id] = array();
                    }

                    foreach ($single_attribute_values as $numberic_key => $single_value) {

                        if(!in_array($single_value, $Session->New_Entry_List[$email_key][$attribute_id])) {
                            array_push($Session->New_Entry_List[$email_key][$attribute_id], $single_value);
                        }
                    }
                }
            }
        }

    }

//automatically checks for compliance with values and return array with ids where needed
    function Test_Attribute_Values($attribute_id, $value_array) {

        if($this->Current_Session == null) {
            return false;
        }

        $Session = $this->Current_Session;

        if(!isset($Session->$attribute_list[$attribute_id])) {
            return false;
        }

        else{

            if(is_array($value_array)) {
                $return_values = array();

                if($Session->$attribute_list[$attribute_id]['type'] === "radio"|"checkboxgroup"|"select"|"checkbox") {
                    foreach ($value_array as $numkey => $value) {
                        if(($value_id = array_search($value, $Session->$attribute_list[$attribute_id]['allowed_value_ids']))) {
                            if(!in_array($value_id, $return_values)) {
                                array_push($return_values, $value_id);
                            }
                        }
                    } 
                }
                else {
                    foreach ($value_array as $numkey => $value) {
                        if(!in_array($value, $return_values)) {
                            array_push($return_values, $value);
                        }
                    }
                    
                }
                return $return_values;
            }
            return false;
        }
    }

    //now must use new format for entries --- >   pass with [id]=>[value]
    function Test_Entry($entry) {

        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;


        //entry is [email]=>array (attribute, value)
        $changing_attributes = array();

        if(!array_key_exists("email", $entry)) {
            return false;
        }
        $email = $entry['email'];
        unset($entry['email']);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL) ){
            return false;
        }

        foreach ($entry as $attribute_id => $value_array) {
            if( ($return_values = Test_Attribute_Values($attribute_id, $value_array))) {
                $changing_attributes[$attribute_id] = $return_values;
            }
        }

        $entry_query = sprintf('select * from %s where email = "%s"', $GLOBALS['tables']['user'], $email);
        $user_sql_result = Sql_Query($entry_query);

        //there is no current user
        if(!$user_sql_result) {

            Add_New_Entry($email, $changing_attributes);

        }
        else{
            Add_Modify_Entry($email, $changing_attributes);
        }
    }










    //     //0 if there are no attributes, is only existence
    //     if(count($entry) == 0) {
    //         //if there is a user then already done
    //         if($user_sql_result){
    //             return true;
    //         }
    //         else{
    //             //will need to create a new user if not already
    //             if(isset($Session->New_Entry_List[$email])) {
    //                 return true;
    //             }
    //             else{
    //                 $Session->New_Entry_List[$email] = array();
    //                 return true;
    //             }
    //         }
    //     }
    //     if($user_sql_result && !isset($Session->Current_Users_Values[$email])) {
    //         Get_Current_User_Attribute_Values($Session->Current_Users_Values, $email, $Session->attribute_list);
    //     }
         
    //     //if there are attributes, must check each value to look for update
    //     foreach ($entry as $attribute => $new_attribute_value) {
    //         //these are single choice values
    //         if($Session->attribute_list[$attribute]['type'] === "radio"|"select"|'checkbox') {
    //             //must check if the possible new value is an allowed value
    //             if(in_array($new_attribute_value, $Session->attribute_list[$attribute]['allowed_values'])) {
    //                 //this is if the returned user has an id, will always have an id if exists in the database
    //                 if(isset($Session->Current_Users_Values[$email])) {
    //                     //the return query for the user,attrubute does not match the new possible attribute value
    //                     if(isset($Session->Current_Users_Values[$email][$attribute]) && $Session->Current_Users_Values[$email][$attribute] === $new_attribute_value) {    
                             
    //                     }
    //                     else{
    //                         Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $attribute, $Session->Modify_Entry_List);
    //                     }
    //                 }
    //                 else{
    //                     //no user info, add info to list
    //                     Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $attribute, $Session->New_Entry_List);
    //                 }
    //             }
    //             else{
    //                 //not an allowed value so skip
    //             }
    //         }
    //         //these are multiple choice types, the new attribute value must match
    //         else if($Session->attribute_list[$attribute]['type'] == 'checkboxgroup') {

    //             $exploded_attribute_values_array = explode(',', $new_attribute_value);

    //             foreach ($exploded_attribute_values_array as $key => $exploded_attribute_value) {

    //                 if(in_array($exploded_attribute_value, $Session->attribute_list[$attribute]['allowed_values'])) {

    //                     if(isset($Session->Current_Users_Values[$email])) {
    //                         if(isset($Session->Current_Users_Values[$email][$attribute]) && in_array($exploded_attribute_value, $Session->Current_Users_Values[$email][$attribute]) {

    //                         }
    //                         else{
    //                             Add_Multi_Entry_To_Modify_Or_New_Entry_List($email, $exploded_attribute_value, $attribute, $Session->Modify_Entry_List);
    //                         }
    //                     }
    //                     else{
    //                         //no current attributes, can definately add to list, user exists
    //                         Add_Multi_Entry_To_Modify_Or_New_Entry_List($email, $exploded_attribute_value, $attribute, $Session->New_Entry_List);                             
    //                     }
    //                 }
    //             }

    //         }
             
    //         else if ($Session->attribute_list[$attribute]['type'] == "date") {
    //             $exploded_date =explode('/', $new_attribute_value);
    //             if(count($exploded_date) != 3) {
    //                 //cannot use
    //             }
    //             else{
    //                 $day = intval($exploded_date[0]);
    //                 $month = intval($exploded_date[1]);
    //                 $year = intval($exploded_date[2]);
    //                 if(!checkdate($month, $day, $year)) {

    //                 }

    //                 else{
    //                     if($day < 10){
    //                         $day_string = '0'.strval($day);
    //                     }
    //                     else{
    //                         $day_string = strval($day);
    //                     }
    //                     if($month < 10){
    //                         $month_string = '0'.strval($month);
    //                     }
    //                     else{
    //                         $month_string = strval($month);
    //                     }
    //                     $year_string = strval($year);
    //                     $new_date_value = $day_string.'/'.$month_string.'/'.$year_string;
                    

    //                     if(isset($Session->Current_Users_Values[$email][$attribute])) {
    //                         if(isset($Session->Current_Users_Values[$email][$attribute]) && $Session->Current_Users_Values[$email][$attribute] != $new_date_value) {

    //                             Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_date_value, $attribute, $Session->Modify_Entry_List);
    //                         }
    //                     }
    //                     else{
    //                         Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_date_value, $attribute, $Session->New_Entry_List);
    //                     }
    //                 }
    //             }
    //         }
    //         else if ($Session->attribute_list[$attribute]['type'] == "textarea"|"textline") {
    //             //this is if the returned user has an id, will always have an id if exists in the database
    //             if(isset($Session->Current_Users_Values[$email])) {

    //                 if(isset($Session->Current_Users_Values[$email][$attribute]) && $new_attribute_value === $Session->Current_Users_Values[$email][$attribute]) {

    //                 }
    //                 else{
    //                     Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $attribute, $Session->Modify_Entry_List);
    //                 }
    //             }
    //             else{
    //                 Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $Session->New_Entry_List);
    //             }
    //         }
    //     }
    // }

    // function Add_Single_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $attribute, &$Modify_list) {
    //     if(!isset($Modify_list[$email])) {
    //         $Modify_list[$email] = array();
    //     }
    //     if(!isset($Modify_list[$email][$attribute])){
    //         $Modify_list[$email][$attribute] = array($new_attribute_value);
    //         return;
    //     }
    //     if(in_array($new_attribute_value, $Modify_list[$email][$attribute])) {
    //         return;
    //     }
    //     else{
    //         array_push($Modify_list[$email][$attribute], $new_attribute_value);
            
    //     }
    // }
    // function Add_Multi_Entry_To_Modify_Or_New_Entry_List($email, $new_attribute_value, $attribute, &$Modify_list) {
    //     if(!isset($Modify_list[$email])) {
    //         $Modify_list[$email] = array();
    //     }
    //     if(!isset($Modify_list[$email][$attribute])){
    //         $Modify_list[$email][$attribute] = array($new_attribute_value);
    //         return;
    //     }
    //     if(in_array($new_attribute_value, $Modify_list[$email][$attribute])) {
    //         return;
    //     }
    //     else{
    //         array_push($Modify_list[$email][$attribute], $new_attribute_value);
    //     }
    // }

    // function Get_Current_User_Attribute_Values(&$Current_Value_List, $email_key, &$set_attribute_list) {
    //     $current_user_query = sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'], $email_key);
    //     $current_user_sql_result = Sql_Fetch_Array_Query($current_user_query);
    //     if(!isset($current_user_sql_result[0])) {
    //         return false;
    //     }
    //     if(!isset($Current_Value_List[$email_key])) {
    //         $Current_Value_List[$email_key] = array();
    //     }
    //     foreach ($set_attribute_list as $attribute_name => $attribute_array) {
             
    //         $Current_User_Attribute_Values_Query = sprintf("select value from %s where attributeid = %d and userid = %d", $GLOBALS['tables']['user_attribute'], $attribute_array['id'], $current_user_sql_result[0]);
    //         $current_attribute_return = Sql_Fetch_Row_Query($Current_User_Attribute_Values_Query);

    //         if($current_attribute_return[0]){
    //             if($attribute_array['type'] == 'checkboxgroup') {
    //                 $exploded_current_values_ids = explode(',', $current_attribute_return[0]);
    //                 $Current_Value_List[$email_key][$attribute_name] = array();

    //                 foreach ($exploded_current_values_ids as $key => $attribute_value_id) {
    //                     $attribute_value_from_id_query = sprintf("select name from %s where id = %d", $attribute_array['tablename'], $attribute_value_id);
    //                     $attribute_value_return = Sql_Fetch_Row_Query($attribute_value_from_id_query);
    //                     array_push($Current_Value_List[$email_key][$attribute_name], $attribute_value_return[0]);
                     
    //                 }
    //             }
    //             else if($attribute_array['type'] == 'checkbox'|'select'|'radio') {
    //                 $attribute_value_from_id_query = sprintf("select name from %s where id = %d", $attribute_array['tablename'], $current_attribute_return[0]);
    //                 $attribute_value_return = Sql_Fetch_Row_Query($attribute_value_from_id_query);
    //                 $Current_Value_List[$email_key][$attribute_name] = $attribute_value_return[0];
    //             }
    //             else if($attribute_array['type'] == 'textarea'|'textline') {
    //                 $Current_Value_List[$email_key][$attribute_name] = $current_attribute_return[0];
    //             }
    //             else if($attribute_array['type'] == 'Date') {
    //                 $Current_Value_List[$email_key][$attribute_name] = $current_attribute_return[0];
    //             }
    //         }
    //     }
    //     return true;
    // }



    class Single_Session {

        $attribute_list;

        $attribute_value_ids;
        $file_location;

        $attribute_column_match;

        $New_Entry_List;
        $modify_entry_table;

        $Current_Users_Values;


        $Current_Modify_Entries_Display_Amount;
        $Modify_Enties_Total_Amount;
        $Modify_Entires_Number_Of_Blocks;
        $Current_Modify_Entry_Block_Number;

        $Committed_Modify_Entries;



        //either 10, 100, 1000, 10000, all
        //default 100
        $Current_New_Entries_Display_Amount;
        $New_Entries_Total_Amount;
        $New_Entires_Number_Of_Blocks;
        $Current_New_Entry_Block_Number;

        $Committed_New_Entries;


        //attribute value arrays are array => ([id] => name)
            //id is auto increment, no duplicates

        //null return here is pretty much error
        function Get_Attribute_Value_Id_List($attribute_id) {

            $query = sprintf('select type, tablename from %s where id = %d', $GLOBALS['tables']['attribute'], $attribute_id);
            $type_table_return = Sql_Query($query);
            if(!$type_table_return) {
            } 
            else {
                $row = Sql_Fetch_Row($type_table_return);
                if(!$row[0] || !$row[1]) { 
                }
                else{
                    $type = $row[0];
                    $table = $row[1];
                    if($type === "radio"|"checkboxgroup"|"select"|"checkbox") {

                        $attribute_value_id_array = array();

                        $tablename = $table_prefix."listattr_".$table;
                        $value_query = sprintf('select id, name from %s', $tablename);

                        $value_query_return = Sql_Query($value_query);
                        if(!$value_query_ret) {

                        }
                        else {
                            while(($value_row = Sql_Fetch_Row($value_query_return))) {
                                $attribute_value_id_array[$value_row[0]] = $value_row[1];
                            }
                        }
                        return $attribute_value_id_array;
                    }
                }
            }
            return null;
        }

        //now need to index by attribute [id]=> ([id]=>.., [name]=>.., ..)
        function Get_Attribute_List() {

            $query = sprintf('select * from %s', $GLOBALS['tables']['attribute']);
            $attribute_data_return = Sql_Query($query); 

            if($attribute_data_return) {

                $new_attribute_list = array();

                while(($attribute_data = Sql_Fetch_Array($attribute_data_return))) {
                    if(!isset( ($attribute_data['id']) | ($attribute_data['name']) | ($attribute_data['type']) )) {
                        //not known format, cannot use
                    }
                    else{

                        if(isset($new_attribute_list[$attribute_data['id']])) {
                            //cannot have duplicates, but really wont
                            continue;
                        }
                        //use the attribute list to get type and value information
                        $new_attribute_list[$attribute_data['id']] = $attribute_data;

                        //must check tables for values
                        if($attribute_data['type'] === ("radio"|"checkboxgroup"|"select"|"checkbox")) {

                            if(!isset($attribute_data['tablename'])) {
                                //this wouldnt make sense
                                unset($new_attribute_list[$attribute_data['id']]);
                            }

                            else {

                                $new_attribute_list[$attribute_data['id']]['allowed_value_ids'] = Get_Attribute_Value_Id_List($attribute_id);

                                if($new_attribute_list[$attribute_data['id']]['allowed_value_ids'] === null) {
                                    //was an error, something missing, no values is just empty array, must still match, so unset
                                    unset($new_attribute_list[$attribute_data['id']]);
                                }
                                
                            }
                        }
                        else{
                            //is other input type, do not set values array
                        }
                    }
                }
                return $new_attribute_list;
            }
            else{
                //no rows :S

                //PRINT AN ERROR I GUESS LOL

                return null;
            }
        }



        function __construct() {

            if(($this->attribute_list = Get_Attribute_List()) == null ) {
                //....ummmm
            }

            $this->New_Entry_List = array();
            $this->Modify_Entry_List = array();

            $this->Current_Users_Values = array();

            $this->Committed_Modify_Entries = array();

            $this->Committed_New_Entries = array();
        }

        function Set_File_Location ($file_name) {

            $this->file_location = $file_name;
        }

        function Get_File_Location() {
            return $this->file_location;
        }

        function Commit_Modify_Entiry($entry, $email_key) {

        }

        function Commit_New_Entries($entry, $email_key) {

        }
    }


    // class Attribute_Changer_Session {

    // 	$id;
    // 	$attribute_list;

    // 	$attribute_value_ids;
    // 	$user;
    // 	$file_location;

    // 	$attribute_column_match;

    // 	$new_entry_table;
    // 	$modify_entry_table;

    // 	$Current_user_values;


    // 	$Committed_Modify_Entries;

    // 	$Committed_New_Entries;


//     	function __construct() {

// //get all attributes and their info
//             $query = sprintf('select * from %s', $GLOBALS['tables']['attribute']);
//             $attribute_data_return = Sql_Query($query); 
//             if($attribute_data_return) {
//                 $this->attribute_list = array();

//                 $this->attribute_value_ids = array();

//                 while(($attribute_data = Sql_fetch_array($attribute_data_return))) {
//                     if(!isset( ($attribute_data['id']) | ($attribute_data['name']) | ($attribute_data['type']) )) {
//                         //not known format, cannot use
//                     }
//                     else{
//                         if(isset($this->attribute_list[$attribute_data['name']])) {
//                             //cannot have duplicates
//                             continue;
//                         }
//                         //use the attribute list to get type and value information
//                         $this->attribute_list[$attribute_data['name']] = $attribute_data;

//                         //must check tables for values
//                         if($attribute_data['type'] === ("radio"|"checkboxgroup"|"select"|"checkbox")) {

//                             if(!isset($attribute_data['tablename'])) {
//                                 unset($this->attribute_list[$attribute_data['name']]);
//                             }

//                             else {

//                                 if(isset($this->attribute_value_ids[$attribute_data['name']])) {
//                                     continue;
//                                 }

//                                 $this->attribute_value_ids[$attribute_data['name'] = array();

//                                 //must query to get the allowed values
//                                 $value_table_name = $table_prefix."listattr_".$attribute_data["tablename"];
//                                 $value_query = sprintf("select name from %s", $value_table_name);
//                                 $allowed_values_res = Sql_Query($value_query);

//                                 if($allowed_value_res) {
//                                     while(($row = Sql_Fetch_Row_Query($allowed_values_res))) {

//                                         $value_id_query = sprintf("select id from %s where name = %s", $attribute_data["tablename"], $row[0]);
//                                         $value_id = Sql_Fetch_Row_Query($value_id_query);

//                                         if($value_id[0]) {
//                                             $this->attribute_value_ids[$attribute_data['name']][$row[0]] = $value_id[0];
//                                             array_push($this->attribute_list[$attribute_data['name']]['allowed_values'], $row[0]);
//                                         }
//                                     }
//                                 }
//                                 else{
//                                     unset($this->attribute_list[$attribute_data['name']]['allowed_values']);
//                                     unset($this->attribute_value_ids[$attribute_data['name']]);
//                                 } 
//                             }
//                         }
//                         else{
//                             //is other input type
//                         }
//                     }
//                 }
//             }
//             else{
//                 //no rows :S

//                 //PRINT AN ERROR I GUESS LOL
//             }
//             $this->New_Entry_List = array();
//             $this->Modify_Entry_List = array();

//             $this->Current_Users_Values = array();
//         }

//         function Set_Id($new_id) {
//         	if(isset($this->id) && $this->id > 0) {
//         		return;
//         	}
//         	$this->id = $new_id; 
//         }

//         function Set_File_Location () {


//         }

//         function Get_File_Location() {

//         }

//         function Test_Entry() {

//         }

//         function Commit_Modify_Entiry($entry, $email_key) {

//         }

//         function Commit_New_Entries($entry, $email_key) {

//         }

    //}


    // function View_All_Sessions() {
    //  if(count($this->sessions) == 0) {
    //      return false;
    //  }
    //  return $this->sessions;
    // }

    // function Load_Session($id) {

    //  if(!isset(this->sessions[$id])) {
    //      return false;
    //  }

    //  if(isset(this->sessions[$id]['active']) && this->sessions[$id]['active'] == 1) {
    //      return false;
    //  }

    //  $this->sessions[$id]['active'] = 1;
    //  return $this->sessions[$id];
    // }


    // function Close_Session($id) {
    //  if(!isset($this->sessions[$id])) {
    //      return false;
    //  }

    //  $this->sessions[$id]['active'] = 1;
    //  unset($this->sessions[$id]);
    //  return true;
    // }

    // function Close_All_Sessions() {
    //  if(count($this->sessions) == 0) {
    //      return false;
    //  }
    //  foreach ($this->sessions as $id_key => $session_data) {
    //      unset($this->sessions[$id_key]);
    //  }
    //  return true;
    // }



    // //have method to determine if there have been changes 

    // function Start_New_Session() {
    //  while(isset($this->sessions[$this->session_increment])) {
    //      $this->session_increment++;
    //  }
    //  $this->sessions[$this->session_increment] = array();
    //  $temp_increment = $this->session_increment;

    //  $this->session_increment++;

    //  $this->sessions[$temp_increment]['id'] = $temp_increment;


    // }


    


}




?>