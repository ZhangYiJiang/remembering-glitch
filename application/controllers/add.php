<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add extends CI_Controller {
	private $max_length = 107;

	public function __construct() 
	{
		parent::__construct();

		if (!$this->session->userdata('player_id')) {
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->view('add', array(
			'max_length' => $this->max_length
		));
	}

	public function save() 
	{
		$type = $this->input->post('type');
		$text = $this->input->post('post');

		// Die if the type is incorrect
		if ($type !== 'prose' && $type !== 'tweet') {
			show_error('Unknown post type');
		}

		// Die if post is not a string 
		if (!is_string($text)) {
			show_error('Unknown post type');
		}

		if ($type == 'prose') {
			$this->load->helper('markdown');
			$text = markdown($text);
		}

		if ($type == 'tweet' && mb_strlen($text) > $this->max_length) {
			$text = mb_substr($text, 0, $this->max_length);
		}

		$this->load->helper('purify');

		$config = HTMLPurifier_Config::createDefault();

		$config->set('HTML.Allowed', 'a[href|title],em,strong,br,hr,img[alt|src|title],p,abbr');
		$config->set('AutoFormat.Linkify', TRUE);
		$config->set('AutoFormat.RemoveEmpty', TRUE);

		$purifier = new HTMLPurifier($config);
		$clean_post = $purifier->purify($text);

		$this->db->insert('posts', array(
			'player_id' => $this->session->userdata('player_id'), 
			'post' => $clean_post, 
			'type' => $type
		));

		redirect($this->db->insert_id());
	}

}

/* End of file add.php */
/* Location: ./application/controllers/add.php */
