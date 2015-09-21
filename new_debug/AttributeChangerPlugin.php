<?php


class AttributeChangerPlugin extends phplistPlugin {

	
	//File location, selected attribute column matches, all entry data, index by id
	// $sessions;
	// $session_increment;

	public $name = "AttributeChangerPlugin";

	public $pageTitles = array( // Entries in the plugin menu of the dashboard
		'firstpage' => 'Begin Data Collection',
	);
	  
	public $topMenuLinks = array( // Entries in the top menu at the top of each page
		'firstpage' => array('category' => 'subscribers'),
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


    
}

	


?>
