<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add extends CI_Controller {

	private $max_length = 107;

	public function __construct() 
	{
		parent::__construct();

		// If the player isn't logged in, redirect to login page
		if (!$this->session->userdata('player_id')) {
			redirect('login?return_url=' . urlencode(uri_string()));
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

	public function snap ()
	{
		$this->load->helper('dom');
		$this->load->helper('file');
		
		$post = $this->input->post('post');
		$snap_url = $this->input->post('snap');

		// Validate snap URL by checking it against Glitch snaps URL 
		if ( ! preg_match('/^http:\/\/www\.glitch\.com\/snaps\//', $snap_url) ) {
			echo 'Invalid snaps URL';
			return;
		}

		// Extract the snap ID. 					   v---this part--v
		// http://www.glitch.com/snaps/PUVVLIPFM5D2UC5/27232-2309e13422/
		$snap_url_parts = explode('/', $snap_url);
		$snap_id = 	$snap_url_parts[5];
		$image_path = "assets/snap/$snap_id.jpeg";	


		// Check if the player owns the post
		$query = $this->db->get_where('posts', array(
			'player_id' => $this->session->userdata('player_id'), 
			'id' => $post
		));

		if ( ! $query->num_rows() ) {
			echo "You don't have permission to add snaps to this post";
			return;
		}

		// Sanity check for if the post already has the same snap 
		$query = $this->db->get_where('snaps', array (
			'post_id' => $this->session->userdata('player_id'), 
			'snap_id' => $snap_id
		));

		if ( $query->num_rows() ) {
			echo 'Memory already has same snap';
			return;
		}

		$html = file_get_html($snap_url);
		$image_url = $html->find('img.photo-orig-view', 0)->src;
		$caption = $html->find('div.photo-caption', 0)->plaintext;

		// Pull down the snaps image from Glitch's server
		if ( ! file_exists($image_path) ) {
			write_file($image_path, file_get_contents($image_url));
		}

		$this->db->insert('snaps', array (
			'snap_id' => $snap_id, 
			'post_id' => $post, 
			'caption' => $caption
		));

		echo 'Success';
	}

}

/* End of file add.php */
/* Location: ./application/controllers/add.php */
