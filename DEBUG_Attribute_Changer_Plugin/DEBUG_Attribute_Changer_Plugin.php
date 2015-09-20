<?php


class DEBUG_Attribute_Changer_PLugin3 extends phplistPlugin {

	
	//File location, selected attribute column matches, all entry data, index by id
	// $sessions;
	// $session_increment;

	public $name = "DEBUG_Attribute_Changer_Plugin3";

	public $pageTitles = array( // Entries in the plugin menu of the dashboard
		'pluginpage_first' => 'Begin Data Collection',
	);
	  
	public $topMenuLinks = array( // Entries in the top menu at the top of each page
		'pluginpage_first' => array('category' => 'subscribers'),
	);

	public $description = 'Begin Data Collection';




    function __construct()
    {
        parent::__construct();

       	$this->coderoot = PLUGIN_ROOTDIR.'/DEBUG_Attribute_Changer_Plugin/';
                    
		// $this->sessions = array();
		// $this->session_increment = 1;
    }


	function adminMenu() {
    	return $this->pageTitles;
	}
}




?>