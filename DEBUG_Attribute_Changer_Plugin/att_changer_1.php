<?php


class att1 extends phplistPlugin {

	
	//File location, selected attribute column matches, all entry data, index by id
	// $sessions;
	// $session_increment;

	public $name = "att1";

	public $pageTitles = array( // Entries in the plugin menu of the dashboard
		'atts1' => 'Begin Data Collection',
	);
	  
	public $topMenuLinks = array( // Entries in the top menu at the top of each page
		'atts1' => array('category' => 'subscribers'),
	);

	public $description = 'Begin Data Collection';




    function __construct()
    {
        parent::__construct();

       	$this->coderoot = PLUGIN_ROOTDIR.'/atts/';
                    
		// $this->sessions = array();
		// $this->session_increment = 1;
    }


	function adminMenu() {
    	return $this->pageTitles;
	}
}




?>