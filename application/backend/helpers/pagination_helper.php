<?php

/*
 * SET GEBO STYLE FOR PAGINATION
 */
function setPaginationStyle(&$config){
	//style pagination
	$config['first_link']				= '&lt;&lt; First';
	$config['first_tag_open']		= '<li class="first">';
	$config['first_tag_close']	= '</li>';
	$config['last_link']				= 'Last &gt;&gt;';
	$config['last_tag_open']		= '<li class="last">';
	$config['last_tag_close']		= '</li>';
	$config['cur_tag_open']			= '<li><a href="javascript:void(0)"><strong><font color="black">';
	$config['cur_tag_close']		= '</font></strong></a></li>';
	$config['next_tag_open']		= '<li>';
	$config['next_tag_close']		= '</li>';
	$config['prev_tag_open']		= '<li>';
	$config['prev_tag_close']		= '</li>';
	$config['num_tag_open']			= '<li>';
	$config['num_tag_close']		= '</li>';
	$config['page_query_string'] 			= TRUE;
	$config['query_string_segment'] 	= 'limitstart';
}

//get pagination info
function getPaginationInfoStyle($message, $total){
	$result = '<div class="dataTables_info" id="dt_a_info">'.vsprintf($message, $total).'</div>';
	return $result;
}

//get pagination links
function getPaginationLinksStyle($pagination){
	$result = '<div class="dataTables_paginate paging_bootstrap_alt pagination"><ul>'.$pagination.'</ul></div>';
	return $result;
}

//set vpagination
function setPaginationParam($pagination, $object, $total){
	// generate pagination
	$config['base_url'] 		= BASE_URL .'admin/'.$object;
	$config['total_rows'] 	= $total;
	$config['per_page'] 		= PAGE_LIMIT;
	$config['uri_segment'] 	= '2';
	$config['num_links'] 		= '10';
	
	//set style pagination
	setPaginationStyle($config);
	$pagination->initialize($config);
	return $pagination->create_links();
}
