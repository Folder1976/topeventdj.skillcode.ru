<?php
class ModelDesignBanner extends Model {
	public function getBanner($banner_id) {
		
				// Category banners * * * Start
				$route = '';
				if(isset($this->request->get['_route_']))
					$route = $this->request->get['_route_'];
				
				if($route != ''){
					$sql = "SELECT * FROM " . DB_PREFIX . "banner b
										  LEFT JOIN " . DB_PREFIX . "banner_image bi ON (b.banner_id = bi.banner_id)
										  WHERE b.banner_id = '" . (int)$banner_id . "' AND b.status = '1' AND
										  bi.language_id = '" . (int)$this->config->get('config_language_id') . "'
										  AND bi.url LIKE '%/".trim($route,'/').";%'
										  ORDER BY bi.sort_order ASC";
				
					$query = $this->db->query($sql);
					
					//echo $sql;
					
				}else{
		
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner b
										  LEFT JOIN " . DB_PREFIX . "banner_image bi ON (b.banner_id = bi.banner_id)
										  WHERE b.banner_id = '" . (int)$banner_id . "' AND b.status = '1' AND
										  bi.language_id = '" . (int)$this->config->get('config_language_id') . "'
										  ORDER BY bi.sort_order ASC");
					
				}
				// Category banners * * * End
					  
		return $query->rows;
	}
}
