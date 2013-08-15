<?php

class Common {

    public function __construct() {
        $this->ci = & get_instance();

        $this->ci->load->library('session');
    }

    //get input param
    public function setFlashData(&$data) {
        //read flash message
        $message = $this->ci->session->flashdata('message');
        if ($message) {
            $data['message'] = $message;
            $data['message_code'] = $this->ci->session->flashdata('message_code');
        }
    }

    //get input param
    public function get_request_param(&$data, $search_param = array(), &$page = '', &$action = '', &$id = null) {
        if (is_array($data)) {
            $data = array_merge($data, $this->ci->session->userdata);
        } else {
            $data = $this->ci->session->userdata;
        }

        //init
        $data['keyword'] = '';
        $data['id'] = '0';
        $data['limitstart'] = 0;
        //get page, action, id
        $page = $this->ci->uri->segment(2);
        $data['page'] = $page;
        $offset3 = $this->ci->uri->segment(3);
        if ($offset3 !== FALSE) {
            if (is_numeric($offset3)) {
                $id = $this->ci->uri->segment(3);
                $data['id'] = $id;
                $action = $this->ci->uri->segment(4);
            } else {
                $action = $this->ci->uri->segment(3);
            }
        } else {
            $action = '';
        }
        $data['action'] = $action;

        //search 
        if ($page == 'search') {
            $keyword = $this->ci->input->post('keyword');
            if (!$keyword) {
                $keyword = $this->ci->input->get('keyword');
            }
            $data['keyword'] = $keyword;
        }
        // search by 
        if(!empty($search_param)){
            foreach ($search_param as $key => $value ){
                $search_by = 'search_' .$value;
                $search_keyword = 'keyword_' .$value;
                if(!isset($data[$search_keyword])) $data[$search_keyword] = '';

                if ($page == $search_by) {
                $keyword = $this->ci->input->post($search_keyword);
                if (!$keyword) {
                    $keyword = $this->ci->input->get($search_keyword);
                }
                $data[$search_keyword] = $keyword;
            }
        }
        }
        

        //limit
        $limitstart = $this->ci->input->post('limitstart');
        if (!$limitstart) {
            $limitstart = $this->ci->input->get('limitstart');
        }
        $data['limitstart'] = $limitstart;

        //remove
        if ($page == 'remove') {
            $ids = $this->ci->input->post('ids');
            if (!$ids) {
                $ids = $this->ci->input->get('ids');
            }
            $data['ids_remove'] = $ids;
        }
    }

    //set public photo in index page
    public function setFirstPhoto($photoname, $image_gallery, $subfolder = '') {
        if (isset($image_gallery) && ($image_gallery)) {
            $arr_image = explode(';', $image_gallery);
            $photo = $arr_image[0];
            $img_photo = '<a class="cbox_single cboxElement" style="width:60px" href="' . ASSETS_URL . 'images/' . $subfolder . '/' . $photo . '"><img style="height:80px;width:80px" src="' . ASSETS_URL . 'images/' . $subfolder . '/' . $photo . '" alt="' . $photoname . '" /></a>';
        } else {
            $img_photo = '';
        }
        return $img_photo;
    }

    //get first photo 
    public function getFirstPhoto($photoname, $image_gallery, $subfolder = '') {
        if (isset($image_gallery) && ($image_gallery)) {
            $arr_image = explode(';', $image_gallery);
            $photo = $arr_image[0];
        } else {
            $photo = '';
        }
        return $photo;
    }

    //get all input
    public function get_input_param($fields) {
        $result = array();
        for ($i = 0; $i < count($fields); $i++) {
            $field_name = $fields[$i];
            if (isset($fields['action']) && $fields['action'] == 'submit') {
                if (isset($_POST[$field_name])) {
                    $field_value = $this->ci->input->post($field_name);
                    $result[$field_name] = $field_value;
                }
            } else {
                $field_value = $this->ci->input->post($field_name);
                $result[$field_name] = $field_value;
            }
        }
        return $result;
    }

    public function form_multi_checkbox($field_name, $arrArea, $list_id) {
        $result = '<div id="multi_checkbox_' . $field_name . '">';
        $result .= '<input type="text" value="" name="search_' . $field_name . '" id="' . $field_name . '" onkeyup="' . $field_name . '_filter(this)" class="control-group" placeholder="search..." />';
        $result .= '<div id="grp_' . $field_name . '" class="prettyprint" style="height:200px;overflow-y:scroll">';
        $list_id = ',' . $list_id . ',';
        for ($i = 0; $i < count($arrArea); $i++) {
            if (strpos(',' . $list_id . ',', ',' . $arrArea[$i]['id'] . ',') !== false) {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            $result .= '<div style="padding-top:2px;padding-bottom:2px;"><input name="' . $field_name . '[]" type="checkbox" ' . $checked . ' value="' . $arrArea[$i]['id'] . '" />' . $arrArea[$i]['name'] . '</div>';
        }
        $result .= "</div>";
        $result .= "
				<script type=\"text/javascript\">
					jQuery.expr[':'].Contains = function(a,i,m){
					    return (a.textContent || a.innerText || \"\").toUpperCase().indexOf(m[3].toUpperCase())>=0;
					};
			
					 function " . $field_name . "_filter(e){
						 var keyword = jQuery(e).val();
					 		if (keyword != ''){
						 		jQuery('#grp_" . $field_name . " div').hide();
						 		jQuery('#grp_" . $field_name . "').find(\"div:Contains('\" + keyword + \"')\").show();
						 	} else {
						 			jQuery('#grp_" . $field_name . " div').show();
						 	}
					 }
					</script>
				";
        $result .= "</div>";
        return $result;
    }
    /*
     * List checkbox sort theo parent 
     */
    public function form_multi_checkbox2($field_name, $arrArea, $list_id) {
        $result = '<div id="multi_checkbox_' . $field_name . '">';
        $result .= '<input type="text" value="" name="search_' . $field_name . '" id="' . $field_name . '" onkeyup="' . $field_name . '_filter(this)" class="control-group" placeholder="search..." />';
        $result .= '<div id="grp_' . $field_name . '" class="prettyprint" style="height:300px;overflow-y:scroll">';
        $list_id = ',' . $list_id . ',';
        
        for ($i = 0; $i < count($arrArea[0]); $i++) {
            if(isset($arrArea[0][$i])){
                
                if (strpos(',' . $list_id . ',', ',' . $arrArea[0][$i]['id'] . ',') !== false) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $result .= '<div style="padding-top:2px;padding-bottom:2px;">
                    <input name="' . $field_name . '[]" type="checkbox" ' . $checked . ' value="' . $arrArea[0][$i]['id'] . '" />' 
                    .'<b>'. $arrArea[0][$i]['name'] .'</b>'. 
                    '</div>';
                
                if(isset($arrArea[$i])){
                    $array_district_tmp = $arrArea[$i];
                    foreach ($array_district_tmp as $key => $value){
                        if (strpos(',' . $list_id . ',', ',' . $value['id'] . ',') !== false) {
                                $checked = 'checked';
                        } else {
                            $checked = '';
                        }
                        $result .= '<div style="padding-top:2px;padding-bottom:2px;">
                            &nbsp &nbsp &nbsp
                            <input name="' . $field_name . '[]" type="checkbox" ' . $checked . ' value="' . $value['id'] . '" />' 
                                . $value['name'] . 
                                '</div>';
                        if(isset($arrArea[$key])){
                            $array_road_tmp = $arrArea[$key];
                            foreach ($array_road_tmp as $road_key => $road){
                                if (strpos(',' . $list_id . ',', ',' . $road['id'] . ',') !== false) {
                                        $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                                $result .= '<div style="padding-top:2px;padding-bottom:2px;">
                                    &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                    <input name="' . $field_name . '[]" type="checkbox" ' . $checked . ' value="' . $road['id'] . '" />' 
                                        . $road['name'] . 
                                        '</div>';
                            }
                        }   
                    }
                }
            }
        }
        $result .= "</div>";
        $result .= "
                <script type=\"text/javascript\">
                        jQuery.expr[':'].Contains = function(a,i,m){
                            return (a.textContent || a.innerText || \"\").toUpperCase().indexOf(m[3].toUpperCase())>=0;
                        };

                         function " . $field_name . "_filter(e){
                                 var keyword = jQuery(e).val();
                                        if (keyword != ''){
                                                jQuery('#grp_" . $field_name . " div').hide();
                                                jQuery('#grp_" . $field_name . "').find(\"div:Contains('\" + keyword + \"')\").show();
                                        } else {
                                                        jQuery('#grp_" . $field_name . " div').show();
                                        }
                         }
                        </script>
                ";
        $result .= "</div>";
        return $result;
    }

}