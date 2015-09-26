<?php


class AttributeChangerPlugin extends phplistPlugin {

	
	//File location, selected attribute column matches, all entry data, index by id
	// $sessions;
	// $session_increment;

	public $name = "AttributeChangerPlugin";

	public $pageTitles = array( // Entries in the plugin menu of the dashboard
		'front_page' => 'Begin Data Collection',
	);
	  
	public $topMenuLinks = array( // Entries in the top menu at the top of each page
		'front_page' => array('category' => 'subscribers'),
	);

	public $description = 'Begin Data Collection';

	public $Current_Session = null;

	public $attribute_changer_tablename;

    function __construct()
    {
        parent::__construct();

       	$this->coderoot = PLUGIN_ROOTDIR.'/AttributeChangerPlugin/';
        require_once(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/Single_Session.php');   

        $this->attribute_changer_tablename = $GLOBALS['table_prefix'].'plugins_attribute_changer_plugin';     
		// $this->sessions = array();
		// $this->session_increment = 1;
    }


	function adminMenu() {
    	return $this->pageTitles;
	}
	function print_something() {
		print('something');
	}

//SAME AS admintoken struct expect missing entered and expires
	public $attribute_changer_table_structure = array(
        "id" => array("integer not null primary key auto_increment","ID"),
        "adminid" => array("integer not null","adminid"),
        "value" => array('longtext',''),
      );
    

    function Serialize_And_Store() {
        if(!isset($this->Current_Session) || $this->Current_Session == null) {
            print("ERR NO SESION");
            die();
        }

        $session_s = base64_encode(serialize($this->Current_Session));

        $truncate_query = sprintf("truncate table %s", $this->attribute_changer_tablename);

        Sql_Query($truncate_query);    
        $serialize_insert_query = sprintf("insert into %s (value) values ('%s')", $this->attribute_changer_tablename, $session_s);
        Sql_Query($serialize_insert_query);

    }

    function Retreive_And_Unserialize() {
        $retrieve_serialized_query = sprintf("select value from %s", $this->attribute_changer_tablename);
        $retrieve_s_return = Sql_Query($retrieve_serialized_query);

        if(!$retrieve_s_return) {
            print("ERROR NO STORED SESSION");
            die();
        }

        $returned_result = Sql_Fetch_Assoc($retrieve_s_return);

        if(!isset($returned_result['value'])) {
            print("ERROR Improperly stored value data");
            die();
        }

        //print_r($returned_result);
        $serialized_session = $returned_result['value'];
        //print($serialized_session);

        $this->Current_Session = unserialize(base64_decode($serialized_session));
    }

    function New_Session() {
    	
    	$test_table_return = Sql_Table_exists($this->attribute_changer_tablename);

    	if(!isset($test_table_return) || $test_table_return < 1 ) {

    		if( (Sql_create_Table($this->attribute_changer_tablename, $this->attribute_changer_table_structure)) === false) {
    			print("ERROR CREATING ATTRIBUTE CHANGER PLUGIN TABLE");
    			die();
    		}

    	}
        if($this->Current_Session != null) {
			
            if(!empty($this->Current_Session->file_location)) {
                if(is_file($this->Current_Session->file_location)) {
                    unlink($this->Current_Session->file_location);
                }
            }
        }

        $this->Current_Session = new Single_Session;
        return $this->Current_Session;
    }

	function Test_Create_Temp_Dir() {
		
        $temp_dir = PLUGIN_ROOTDIR.'/AttributeChangerPlugin/temp_table_uploads/';
        if(!file_exists(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/')) {
            return false;
        }
        else if(!is_dir(PLUGIN_ROOTDIR.'/AttributeChangerPlugin/')) {
            return false;
        }
        else{
            if(!file_exists($temp_dir)) {
                if(!mkdir($temp_dir)){
                	$error = error_get_last();
    				print($error['message']);
                }
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

        foreach ($entry as $attribute_id2 => $value_array2) {

            if( ($return_values = $this->Test_Attribute_Values($attribute_id2, $value_array2)) != false ) {
            	print_r($return_values);
                $changing_attributes[$attribute_id2] = $return_values;
            }
            else{
            	print("SHIT");
            }
        }


        $entry_query = sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'], $email);
        $user_sql_result = Sql_Fetch_Row_Query($entry_query);


        //there is no current user
        if(!$user_sql_result[0]) {

            //print("HUUUURRRRRR<br>HUUUUUUURRRR<br>");
            $this->Add_New_Entry($email, $changing_attributes);

        }
        else{
        	        print("ASADARARAR<br>");
            $this->Add_Modify_Entry($email, $changing_attributes);
        }
    }

    // //automatically checks for compliance with values and return array with ids where needed
    function Test_Attribute_Values($attribute_id, $value_array) {

    	//print_r($value_array);
        if($this->Current_Session == null) {
            return false;
        }

        $Session = $this->Current_Session;

        if(!isset($Session->attribute_list[$attribute_id])) {
        	//print("<br>att id<br>".$attribute_id.'<br>');
        	//print_r($Session->attribute_list);
            return false;
        }

        else{
        	$att = $Session->attribute_list[$attribute_id];

        	if(!is_array($value_array)) {

        		if($att['type'] === "radio" || $att['type'] ==="checkboxgroup" || $att['type'] === "select") {
        			return false;
        		}
        		else{
        			return array($value_array);
        		}
        	}

            else {
                $return_values = array();

                if($att['type'] === "radio" || $att['type'] ==="checkboxgroup" || $att['type'] === "select") {
                    foreach ($value_array as $numkey => $value) {
                        if(($value_id = array_search($value, $att['allowed_value_ids']))) {
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

/////////////////////////////
//HERE
    function Add_Modify_Entry($email_key, $attribute_values) {
        print("<br>mod entr".$email_key.'<br>');
        print_r($attribute_values);
        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;
        if($attribute_values == null || !is_array($attribute_values) || count($attribute_values) == 0 ) {
            //do nothing, already have a user but theres nothing to updateprint


        }
        else{

            if(!isset($Session->Current_Users_Values[$email_key])) {

                if($this->Get_Current_User_Values($email_key) == false) {

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


    function Get_Current_User_Values($email_key) {
        $Session = $this->Current_Session;


        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }



        $current_user_query = sprintf('select id from %s where email = "%s"', $GLOBALS['tables']['user'], $email_key);
        $current_user_sql_result = Sql_Fetch_Row_Query($current_user_query);

        if(!$current_user_sql_result[0]) {
                     
            return false;
        }

        if(!isset($Session->Current_Users_Values[$email_key])) {

            $Session->Current_Users_Values[$email_key] = array();
            print("ARFARF");
            $Session->Current_Users_Values[$email_key]['id'] = $current_user_sql_result[0];
                            
            foreach ($Session->attribute_list as $attribute_id => $attribute_data) {

                $Current_User_Value_Query = sprintf("select value from %s where attributeid = %d and userid = %s", $GLOBALS['tables']['user_attribute'], $attribute_id, $current_user_sql_result[0]);
                $current_attribute_return = Sql_Fetch_Row_Query($Current_User_Value_Query);

                $Session->Current_Users_Values[$email_key][$attribute_id] = array();

                if(!$current_attribute_return[0]) {
                    continue;
                }

                else{
                    if($attribute_data['type'] == 'checkboxgroup') {
                        $exploded_current_values_ids = explode(',', $current_attribute_return[0]);

                        $Session->Current_Users_Values[$email_key][$attribute_id] = $exploded_current_values_ids;

                    }

                    else {
                        array_push($Session->Current_Users_Values[$email_key][$attribute_id], $current_attribute_return[0]);
                        
                    }
                }
            }
        }
        return true;
    }


    //attribute values array([att_id] => values), values have been test all are acceptable
    function Add_New_Entry($email_key, $attribute_values) {


        if($this->Current_Session == null) {
            return "ERROR NO CURRENT SESSION";
        }

        $Session = $this->Current_Session;
        if($attribute_values == null || !is_array($attribute_values) || count($attribute_values) == 0 ) {
            //just set email
            
            if(!isset($Session->New_Entry_List[$email_key])) {
                $Session->New_Entry_List[$email_key] = array();
            }
            //else no need to do anything
            return true;
        }

        else{
print("<br>in hurrr<br>");
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
    
}

	


?>
