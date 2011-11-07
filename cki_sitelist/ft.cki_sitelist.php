<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* @package			ft.cki_sitelist.ee_addon
* @version			1.1
* @author			Christopher Imrie ~ ckimrie <chris@christopherimrie.com>
* @link				http://github.com/ckimrie/ft.cki_sitelist.ee_addon
* @license			http://creativecommons.org/licenses/by-sa/3.0/
*
*/
	class Cki_sitelist_ft extends EE_Fieldtype
	{
		var $info = array(
			'name'		=> 'CKI Site List',
			'version'	=> '1.1'
		);

		public function Cki_sitelist_ft()
		{
			parent::EE_Fieldtype();
		}

		public function display_field($data, $cell = FALSE)
		{
			$text_direction = ($this->settings['field_text_direction'] == 'rtl') ? 'rtl' : 'ltr';
			$site_list = array();

			// matrix support
			$field_name_2 = $cell ? $this->cell_name : $this->field_name;

			//Get the list of sites from the database
			$this->EE->db->select('site_id, site_label, site_name');
			$this->EE->db->from('exp_sites');
			$this->EE->db->order_by('site_label asc');
			$q = $this->EE->db->get();

			//No need to check if there are no sites.  If no results are returned
			//then there are more serious problems at hand than this fieldtype going wrong...

			//Create blank option
			$site_list[''] = "None";

			foreach($q->result_array() as $site)
			{
				$site_list[$site['site_name']] = $site['site_id']." - ".$site['site_label'];
			}

			return form_dropdown($field_name_2, $site_list, $data, 'dir="'.$text_direction.'" id="'.$this->field_id.'"' );
		}


		public function replace_tag($data, $params = FALSE, $tagdata = FALSE)
		{
			//Check paramater has been set
			if ($data != '' && isset($params['display'])) {

				//Grab the site data
				$this->EE->db->select('site_id, site_label, site_name, site_description');
				$this->EE->db->from('exp_sites');
				$this->EE->db->where('site_name', $data);
				$this->EE->db->limit(1);
				$this->EE->db->order_by('site_label asc');
				$q = $this->EE->db->get();

				if($q->num_rows() > 0)
				{
					$qa = $q->result_array();

					switch($params['display'])
					{
						default:
							return $data;
							break;

						case "label":
							return $qa[0]['site_label'];
							break;

						case "id":
							return $qa[0]['site_id'];
							break;

						case "short_name";
							return $data;
							break;

						case "description":
							return $qa[0]['site_description'];
							break;
					}
				}else{
					return $data;
				}
			}else{
				return $data;
			}
		}

		public function validate($data)
		{
			return TRUE;
		}

		public function save_settings($data)
		{
			return array();
		}

		public function display_settings($data)
		{
			//nothing
		}

		function install()
		{
			//nothing
		}

		function unsinstall()
		{
			//nothing
		}

		// matrix support
		function display_cell($data)
		{
			return $this->display_field($data, TRUE);
		}

		// low variables support
		function display_var_field($data)
		{
			return $this->display_field($data);
		}

	}
	//END CLASS

/* End of file ft.cki_sitelist.php */