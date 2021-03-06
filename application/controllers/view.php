<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {

	public function single ($id)
	{
		$this->load->helper('text');
		$this->load->helper('date');

		$query = $this->db->select('name, post, type, timestamp, id, p.player_id')
			->from('players p')
			->join('posts o', 'o.player_id = p.player_id')
			->where('id', $id)
			->get();

		if ($query->num_rows() == 0) {
			show_404($id);
		}

		$data = $query->row_array();
		$data = $this->processMemory($data);

		// Get snaps data 
		$data['snaps'] = $this->db->get_where('snaps', array(
			'post_id' => $id
		))->result_array();

		$this->load->view('post', $data + array('twitter' => TRUE));
	}

	public function load ()
	{
		$this->load->helper('text');

		$posts = $this->db
			->limit(30)
			->order_by('RAND()')
			->get('posts')
			->result();

		foreach ($posts as &$row) {
			if ($row->type === 'prose') {
				$row->post = character_limiter(strip_tags($row->post), 100);
			} else {
				$row->post = strip_tags($row->post);
			}
		}

		echo json_encode($posts);
	}

	public function jsonp ()
	{
		$this->load->helper('date');
		$callback = $this->input->get('callback');

		if ($callback === FALSE) {
			$callback = 'callback';
		}

		$post = $this->db
			->select('name, timestamp, post, id, type')
			->from('players p')
			->join('posts o', 'o.player_id = p.player_id')
			->order_by('RAND()')
			->limit(1)
			->get()
			->row();

		$post->time = glitch_time($post->timestamp);

		echo "$callback (" . json_encode($post) . ");";
	}

	public function all ($player_id = FALSE, $page = 1)
	{
		$this->load->library('pagination');
		$this->load->helper('date');
		$this->load->helper('text');

		$per_page = 40;

		$query = $this->db
			->select('name, post, type, timestamp, id, p.player_id')
			->from('players p')
			->join('posts o', 'o.player_id = p.player_id'); 

		if ($player_id) {
			// Allows for multiple players 
			$player_id = explode('+', $player_id);

			// Don't use pagination on player pages
			$config['total_rows'] = 0;

			$post = $query
				->where_in('p.player_id', $player_id)
				->get()
				->result_array();
			
		} else {

			$post = $query
				->order_by('timestamp', 'DESC')
				->limit($per_page, ($page - 1) * $per_page)
				->get()
				->result_array();

			$config['total_rows']	= $this->db->count_all('posts');
			$config['per_page']		= $per_page;
		}

		$this->pagination->initialize($config);

		foreach ($post as &$row) {
			$row = $this->processMemory($row);
		}
		
		$this->load->view('list', array (
			'post' => $post
		));
		
	}

	public function tinyspeck ()
	{
		$tinyspeckers = array(
			'PLI16FSFK2I91',	// stoot barfield
			'PIF5NL12S3D1BH3',	// Jade
			'PIF115TJK2OP0',	// Kukubee
			'PLI10TT96BL3U',	// Danny 
			'PM12AQIK3502KK9',	// Araldia
			'PM1OI84B9S12UMB'	// Rascalmom
		);

		$this->all(implode('+', $tinyspeckers));
	}


	private function processMemory ($row) 
	{
		$row['glitch_time'] = glitch_time($row['timestamp']);

		// Creating tweet
		if ($row['type'] == 'tweet') {
			$row['tweet'] = "I'll always remember #Glitch for " . trim(strip_tags($row['post']));
		} else {
			$row['tweet'] = character_limiter(strip_tags($row['post']), 135);

			if (strlen($row['post']) > 500) {
				$row['type'] .= '-long';
			}
		}

		return $row;
	}

}

/* End of file view.php */
/* Location: ./application/controllers/view.php */
