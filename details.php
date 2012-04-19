<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Floorplans extends Module {
    
        public $version = '0.1';
    
        public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Floorplans',
			),
			'description' => array(
				'en' => 'Module to manage floor plans',
			),
			'frontend' => FALSE,
			'backend'  => TRUE,
			'skip_xss' => TRUE,
			'menu'	  => TRUE,
                        'sections' => array(                            
			    'floorplans' => array(
                                'name' => 'floorplans',
                                'uri' => 'admin/floorplans/list_floorplans',
                                'shortcuts' => array(
                                    array(
                                       'name' => 'new_floorplan_title',
                                       'uri' => 'admin/floorplans/create',
                                       'class' => 'add'
                                    ),
                                    array(
                                       'name' => 'list_floorplans_title',
                                       'uri' => 'admin/floorplans/list_floorplans',
                                       'class' => 'add'
                                    )
                                )
                            ),
                            
                        )
		);

                return $info;
	}
    
        public function install()
	{

            $this->db->query("CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix('floorplan') . "` (
             `floorplan_id` int(11) NOT NULL AUTO_INCREMENT,
             `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
             `lease_price` float(10,2) DEFAULT NULL,
             `purchase_price` float(10,2) DEFAULT NULL,
             `features` text COLLATE utf8_unicode_ci,
             `status` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'enabled',
             PRIMARY KEY (`floorplan_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

            "CREATE TABLE `" . $this->db->dbprefix('floorplan_images') . "` (
             `floorplan_images_id` int(11) NOT NULL AUTO_INCREMENT,
             `floorplan_id` int(10) NOT NULL,
             `image_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
             `order` int(11) DEFAULT NULL,
             PRIMARY KEY (`floorplan_images_id`),
             KEY `fk_default_floorplan_images_1` (`floorplan_id`),
             CONSTRAINT `fk_default_floorplan_images_1` FOREIGN KEY (`floorplan_id`) REFERENCES `" . $this->db->dbprefix('floorplan') . "` (`floorplan_id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";    
                
            return TRUE;

	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return TRUE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.";
	}
    
}

?>
