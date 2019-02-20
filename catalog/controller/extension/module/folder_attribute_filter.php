<?php
class ControllerExtensionModuleFolderAttributeFilter extends Controller {
	public function index() {
        
	
		
        $data = array();
        $data['filter_manufactures'] = array();
        $data['filter_attributes'] = array();
        $data['filter_attribute_groups'] = array();
        $data['ffilter'] = array();
		$data['ofilter'] = array();
		$data['ffilter_manufacturer'] = array();
		$data['filter_options'] = array();
        
        $data['action'] = str_replace('&amp;', '&', $this->url->link('product/category', 'path=' . $this->request->get['path']));
      
        if(isset($this->session->data['prices']))
            $data['prices'] = $this->session->data['prices'];
            
        if(isset($this->session->data['filter_manufactures']))
            $data['filter_manufactures'] = $this->session->data['filter_manufactures'];
            
        if(isset($this->session->data['filter_attributes']))
            $data['filter_attributes'] = $this->session->data['filter_attributes'];
           
		if(isset($this->session->data['filter_options']))
            $data['filter_options'] = $this->session->data['filter_options'];
    	    
        if(isset($this->session->data['filter_attribute_groups']))
            $data['filter_attribute_groups'] = $this->session->data['filter_attribute_groups'];
		
        if(isset($this->request->get['ofilter']))
            $data['ofilter'] = $this->request->get['ofilter'];
	
        if(isset($this->request->get['ffilter']))
            $data['ffilter'] = $this->request->get['ffilter'];
		
		if(isset($this->request->get['manufacturer_id']))
            $data['ffilter_manufacturer'] = $this->request->get['manufacturer_id'];
		
        
        $this->load->language('extension/module/folder_attribute_filter');
        
		
		if (count($data['filter_attributes']) > 0 OR count($data['filter_manufactures']) > 0) {
		
			$data['heading_title'] = $this->language->get('heading_title');

			$data['button_filter'] = $this->language->get('button_filter');
			$data['manufacture_title'] = $this->language->get('manufacture_title');
		
			return $this->load->view('extension/module/folder_attribute_filter', $data);
			
		}
        
        return '';
	}
}

