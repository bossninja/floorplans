<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

    private $_folders   = array();
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
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'required'
         ),    
            array(
			'field' => 'lease_price',
			'label' => 'Lease Price',
			'rules' => 'decimal'
		 ),
            array(
			'field' => 'purchase_price',
			'label' => 'Purchase Price',
			'rules' => 'decimal'
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
    		$this->lang->load(array('floorplans', 'files'));

            $this->load->model(array('floorplan_m', 'floorplan_features_m','files/file_folders_m', 'files/file_m'));
            $this->load->library(array('form_validation'));
            $this->load->helper(array('html', 'form'));
            $this->config->load('files');

            $this->_folders = $this->file_folders_m->get_folders();
    	}
        
        //--------------------------------------------------------------------------    

        function index()
        {
            $this->list_floorplans();            
        }
        
        //--------------------------------------------------------------------------    

        function create()
        {
            $this->form_validation->set_rules($this->validation_rules);
            
            if($this->form_validation->run())
            {
                $floorplan_id = $this->floorplan_m->insert(array(
                    'title'          => $this->input->post('title'),
                    'slug'           => $this->input->post('slug'),
                    'lease_price'    => number_format($this->input->post('lease_price'), 2, '.', ''),
                    'purchase_price' => number_format($this->input->post('purchase_price'), 2, '.', ''),
                ));
                
                if($floorplan_id)
                {
                    // save features
                    if(is_array($this->input->post('newfeatures'))) 
                    {
                        foreach($this->input->post('newfeatures') as $key => $feature)
                        {
                            if(!$feature['delete'] && $feature['name']!='')
                            {
                                $feature['floorplan_id'] = $floorplan_id;
                                unset($feature['delete']);
                                $this->floorplan_features_m->insert($feature);
                            }
                        }
                    }    
                    // create a folder with the title
                    $folder_id = $this->file_folders_m->insert(array(
                        'name'          => $this->input->post('title'),
                        'slug'          => url_title($this->input->post('title')),
                        'parent_id'     => 0,
                        'date_added'    => now()
                    ));
                    // update the floorplan register with the folder_id
                    $this->floorplan_m->update($floorplan_id, array('folder_id' => $folder_id));
                    
                    $this->session->set_flashdata('success', $this->lang->line('floorplan_add_success'));
                    redirect('admin/' . $this->module . '/edit/' . $floorplan_id);
                }
            }
            
            $this->template
                    ->append_metadata( css('admin.css', 'floorplans') )
                    ->append_metadata( css('jquery.fileupload-ui.css', 'floorplans') )                    
                    ->append_metadata( css('files.css', 'files') )
                    ->append_metadata( js('jquery/jquery.cooki.js') )
                    ->append_metadata( js('jquery.fileupload.js', 'files') )
                    ->append_metadata( js('jquery.fileupload-ui.js', 'files') )
                    ->append_metadata( js('jquery.ba-hashchange.min.js', 'files') )
                    ->append_metadata( js('functions.js', 'floorplans') )
                    ->append_metadata( js('manage.js', 'floorplans') )
                    ->build('admin/form');
        }
        
        //--------------------------------------------------------------------------    

        function edit($id=0)
        {
            if($id != 0)
            {                
                $this->form_validation->set_rules($this->validation_rules);
            
                if($this->form_validation->run())
                {
                    $floorplan_id = $this->floorplan_m->update($id, array(
                        'title'          => $this->input->post('title'),
                        'slug'           => $this->input->post('slug'),
                        'lease_price'    => number_format($this->input->post('lease_price'), 2, '.', ''),
                        'purchase_price' => number_format($this->input->post('purchase_price'), 2, '.', ''),                        
                    ));

                    if($floorplan_id)
                    {
                        // save new features
                        if(is_array($this->input->post('newfeatures'))) 
                        {
                            foreach($this->input->post('newfeatures') as $key => $feature)
                            {
                                if(!$feature['delete'] && $feature['name']!='')
                                {
                                    $feature['floorplan_id'] = $id;
                                    unset($feature['delete']);
                                    $this->floorplan_features_m->insert($feature);
                                }
                            }
                        }
                        // update features
                        if(is_array($this->input->post('features'))) 
                        {
                            foreach($this->input->post('features') as $key_update => $feature_update)
                            {
                                if($feature_update['delete'] == '1' || $feature_update['name'] == '')
                                {
                                    $this->floorplan_features_m->delete($key_update);                                    
                                }
                                else
                                {                                    
                                    unset($feature_update['delete']);
                                    $this->floorplan_features_m->update($key_update, array('name' => $feature_update['name']));
                                }
                            }
                        }

                        $this->session->set_flashdata('success', $this->lang->line('floorplan_updated_success'));
                        redirect('admin/' . $this->module);
                    }
                }
                
                $plan_data = $this->floorplan_m->get($id);

                if($plan_data)
                {
                    $this->template
                            ->append_metadata( css('admin.css', 'floorplans') )
                            ->append_metadata( css('jquery.fileupload-ui.css', 'floorplans') )
                            ->append_metadata( css('files.css', 'files') )                            
                            ->append_metadata( js('jquery/jquery.cooki.js') )
                            ->append_metadata( js('jquery.fileupload.js', 'files') )
                            ->append_metadata( js('jquery.fileupload-ui.js', 'files') )
                            ->append_metadata( js('jquery.ba-hashchange.min.js', 'files') )
                            ->append_metadata( js('functions.js', 'floorplans') )
                            ->append_metadata( js('manage.js', 'floorplans') )
                            ->set('plan', $plan_data)
                            ->set('plan_features', $this->floorplan_features_m->get_many_by('floorplan_id', $id))
                            ->set('folder_data', $this->file_folders_m->get($plan_data->folder_id))
                            ->build('admin/form');
                }
                else
                {
                    redirect('admin/' . $this->module);
                }
            }            
        }

        //--------------------------------------------------------------------------    

        function list_floorplans()
        {            
            $this->template
                    ->set('floorplan_list', $this->floorplan_m->get_all());
            
            $this->template->build('admin/index');
        }

        //--------------------------------------------------------------------------    

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
        
    //--------------------------------------------------------------------------        

    /**
     * Delete a file
     *
     * @params  int The file id
     */
    public function delete_file($id = 0)
    {
        $this->load->model('files/file_m');
        $ids = $id
            ? is_array($id)
                ? $id
                : array($id)
            : (array) $this->input->post('action_to');

        $total      = sizeof($ids);
        $deleted    = array();

        // Try do deletion
        foreach ($ids as $id)
        {
            // Get the row to use a value.. as title, name
            if ($file = $this->file_m->get($id))
            {
                // Make deletion retrieving an status and store an value to display in the messages
                $deleted[($this->file_m->delete($id) ? 'success': 'error')][] = $file->filename;

                $folder = $this->_folders[$file->folder_id];
            }
        }

        // Set status messages
        foreach ($deleted as $status => $values)
        {
            // Mass deletion
            if (($status_total = sizeof($values)) > 1)
            {
                $last_value     = array_pop($values);
                $first_values   = implode(', ', $values);

                // Success / Error message
                $this->session->set_flashdata($status, sprintf(lang('files.mass_delete_' . $status), $status_total, $total, $first_values, $last_value));
            }

            // Single deletion
            else
            {
                // Success / Error messages
                $this->session->set_flashdata($status, sprintf(lang('files.delete_' . $status), $values[0]));
            }
        }

        // He arrived here but it was not done nothing, certainly valid ids not were selected
        if ( ! $deleted)
        {
            $this->session->set_flashdata('error', lang('files.no_select_error'));
        }

        $data = array();
        $data['messages']['success'] = 'deleted with success';
        $message = $this->load->view('admin/partials/notices', $data, TRUE);

        return $this->template->build_json(array(
            'status'    => 'success',
            'message'   => $message,
        ));
        
    }

    //--------------------------------------------------------------------------    

    /**
	 * Sort images in an existing gallery
	 *
	 * @access public
	 */
	public function ajax_update_order()
	{
		$ids = explode(',', $this->input->post('order'));

		$i = 1;
		foreach ($ids as $id)
		{
			$this->file_m->update($id, array(
				'sort' => $i
			));                        
			++$i;
		}
	}

}