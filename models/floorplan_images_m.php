<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Floorplan_images_m extends MY_Model 
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'floorplan_images';
        $this->primary_key = 'id';
    }
    
    public function get_images_by_gallery($id, $options = array())
    {
            
            if (isset($options['offset']))
            {
                    $this->db->offset($options['offset']);
            }

            if (isset($options['limit']))
            {
                    $this->db->limit($options['limit']);
            }

            // Grand finale, do what you gotta do!!
            $images = $this->db
                            // Select fields on gallery images table
                            ->select('floorplan_images.*, files.name, files.filename, files.extension, files.description, files.name as title, floorplan.folder_id')
                            // Set my gallery by id
                            ->where('floorplan.floorplan_id', $id)
                            // Filter images from my gallery
                            ->join('floorplan', 'floorplan.floorplan_id = floorplan_images.floorplan_id', 'left')
                            // Filter from my images
                            ->join('files', 'files.id = floorplan_images.file_id', 'left')
                            // Filter files type image
                            ->where('files.type', 'i')
                            // Order by user order
                            ->order_by('`order`', 'asc')
                            // Get all!
                            ->get('floorplan_images')
                            ->result();

            return $images;
    }
    
    public function set_new_image_files($floorplan_id = 0)
    {
            $this->db
                    // Select fields on files table
                    ->select('files.id as file_id, floorplan.floorplan_id as floorplan_id')
                    ->from('files')
                    // Filter from my gallery folder
                    ->join('floorplan', 'floorplan.folder_id = files.folder_id', 'left')
                    // Join with the existing
                    ->join('floorplan_images', 'floorplan_images.file_id = files.id', 'left')
                    // Set my gallery by id
                    ->where(array('files.type' => 'i', 'floorplan.floorplan_id' => $floorplan_id))
                    // This will be one frustrated join. Sorry pal!
                    ->where('floorplan_images.file_id IS NULL', null, FALSE);

            // Already updated, nothing to do here..
            if ( ! $new_images = $this->db->get()->result_array())
            {
                    return FALSE;
            }

            // Get the max order
            $max_order = $this->db
                    ->select_max('`order`')
                    ->get_where('floorplan_images', array('floorplan_id' => $floorplan_id))->row();

            // Insert new images, increasing the order
            $insert_images = array();

            foreach ($new_images as &$new_image)
            {
                    $new_image['order'] = ++$max_order->order;
            }

            unset($new_image);

            $this->db->insert_batch('floorplan_images', $new_images);

            return TRUE;
    }
}

?>
