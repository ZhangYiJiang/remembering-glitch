<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pagination Configuration
|--------------------------------------------------------------------------
|
| See http://codeigniter.com/user_guide/libraries/pagination.html
|
*/

$config['base_url']				= site_url('view');
$config['uri_segment']			= 2;
$config['num_links']			= 5;
$config['use_page_numbers']		= TRUE;
$config['next_link']			= '&gt;';
$config['prev_link']			= '&lt;';
$config['cur_tag_open']			= '<span class="current-page">';
$config['cur_tag_close']		= '</span>';
$config['full_tag_open']		= '<div class="pagination">';
$config['full_tag_close']		= '</div>';

/* End of file pagination.php */
/* Location: ./application/config/pagination.php */