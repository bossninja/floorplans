<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'floorplans';
	
	/**
	 * Constructor method
	 *
	 * @return void
	 */
	function __construct()
	{	
		// Call the parent's constructor method
		parent::__construct();		
		
		// load Language file
		$this->lang->load('floorplans');						
	}
        
        function index()
        {
            //exit('floorplans');
        }
}