<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'floorplans';
	
        /**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
        protected $validation_rules = array(
            array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required'
		 ),
            array(
			'field' => 'features',
			'label' => 'Features',
			'rules' => 'required'
		 ),
            array(
			'field' => 'lease_price',
			'label' => 'Lease Price',
			'rules' => ''
		 ),
            array(
			'field' => 'purchase_price',
			'label' => 'Purchase Price',
			'rules' => ''
		 ),
            array(
                    'field' => 'folder_id',
                    'label' => 'Folder',
                    'rules' => 'trim|numeric|required' //|callback__check_folder
            )
            
        );
        
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
                $this->load->model(array('floorplan_m', 'floorplan_images_m', 'files/file_folders_m'));
                $this->load->library('form_validation');
                $this->load->helper('html');
	}
        
        function index()
        {
            $this->list_floorplans();
            //$this->template->build('admin/index');
        }
        
        function create()
        {
            $file_folders = $this->file_folders_m->get_folders();
            $folders_tree = array();
            foreach ($file_folders as $folder)
            {
                    $indent = repeater('&raquo; ', $folder->depth);
                    $folders_tree[$folder->id] = $indent.$folder->name;
            }
            
            $this->form_validation->set_rules($this->validation_rules);
            
            if($this->form_validation->run())
            {
                $floorplan_id = $this->floorplan_m->insert(array(
                    'title'          => $this->input->post('title'),
                    'lease_price'    => $this->input->post('lease_price'),
                    'purchase_price' => $this->input->post('purchase_price'),
                    'features'       => $this->input->post('features'),
                    'folder_id'      => $this->input->post('folder_id'),
                ));
                
                if($floorplan_id)
                {
                    $this->floorplan_images_m->set_new_image_files($floorplan_id);
                    $this->session->set_flashdata('success', $this->lang->line('floorplan_add_success'));
                    redirect('admin/' . $this->module);
                }
            }
            
            $this->template 
                    ->append_metadata(css('galleries.css', 'floorplans'))
                    ->append_metadata(js('manage.js', 'floorplans'))
                    ->set('folders_tree', $folders_tree)                    
                    ->build('admin/form');
        }
        
        function edit($id=0)
        {
            if($id != 0)
            {            
                
                $file_folders = $this->file_folders_m->get_folders();
                $folders_tree = array();
                foreach ($file_folders as $folder)
                {
                        $indent = repeater('&raquo; ', $folder->depth);
                        $folders_tree[$folder->id] = $indent.$folder->name;
                }
                
                $this->form_validation->set_rules($this->validation_rules);
            
                if($this->form_validation->run())
                {
                    $floorplan_id = $this->floorplan_m->update($id, array(
                        'title'          => $this->input->post('title'),
                        'lease_price'    => $this->input->post('lease_price'),
                        'purchase_price' => $this->input->post('purchase_price'),
                        'features'       => $this->input->post('features'),
                        'folder_id'      => $this->input->post('folder_id'),
                    ));

                    if($floorplan_id)
                    {
                        $this->session->set_flashdata('success', $this->lang->line('floorplan_updated_success'));
                        redirect('admin/' . $this->module);
                    }
                }
                
                $plan_data = $this->floorplan_m->get($id);
                
                if($plan_data)
                {
                    $this->template
                            ->append_metadata(css('galleries.css', 'floorplans'))
                            ->append_metadata(js('manage.js', 'floorplans'))
                            ->set('plan', $plan_data)
                            ->set('gallery_images', $this->floorplan_images_m->get_images_by_gallery($id))
                            ->set('folders_tree', $folders_tree)                                                          
                            ->build('admin/form');
                }
                else
                {
                    redirect('admin/' . $this->module);
                }
            }            
        }

        function list_floorplans()
        {            
            $this->template
                    ->set('floorplan_list', $this->floorplan_m->get_all());
            
            $this->template->build('admin/index');
        }

        function delete($id=0)
        {
            if($id != 0)
            {
                if($this->floorplan_m->delete($id))
                {
                    $this->session->set_flashdata('success', $this->lang->line('floorplan_delete_success'));
                    redirect('admin/' . $this->module);
                }
            }
            else
            {
                redirect('admin/' . $this->module);
            }
        }
        
        /**
	 * Callback method that checks the file folder of the gallery
	 * @access public
	 * @param int id The id to check if file folder exists or prep to create new folder
	 * @return bool
	 */
        /*
	public function _check_folder($id = 0)
	{
		// Is not creating or folder exist.. Nothing to do.
		if ($this->method !== 'create')
		{
			return $id;
		}
		elseif ($this->file_folders_m->exists($id))
		{
			if ($this->gallery_m->count_by('folder_id', $id) > 0)
			{
				$this->form_validation->set_message('_check_folder', lang('galleries.folder_duplicated_error'));

				return FALSE;
			}

			return $id;
		}

		$folder_name = $this->input->post('title');
		$folder_slug = url_title(strtolower($folder_name));

		// Check if folder already exist, rename if necessary.
		$i = 0;
		$counter = '';
		while ( ((int) $this->file_folders_m->count_by('slug', $folder_slug.$counter) > 0))
		{
			$counter = '-'.++$i;
		}

		// Return data to create a new folder to this gallery.
		return array(
			'name' => $folder_name.($i > 0 ? ' ('.$i.')' : ''),
			'slug' => $folder_slug.$counter
		);
	}*/

}