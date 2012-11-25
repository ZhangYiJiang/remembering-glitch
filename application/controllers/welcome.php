<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{

		$this->load->view('home', array (
			'player_count' => $this->db->count_all('players'), 
			'post_count' => $this->db->count_all('posts')
		));

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */