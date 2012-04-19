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
		
		// Get the user ID, if it exists
		$user = $this->ion_auth->get_user();
		
		if(!empty($user))
		{
			$this->user_id = $user->id;	
			$this->config->set_item('user_id', $this->user_id );
		}else{
			$this->template->build('admin/access_failed');
		}
		
	}
        
}