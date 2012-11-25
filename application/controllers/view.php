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
		$data['glitch_time'] = glitch_time($data['timestamp']);

		// Creating tweet
		if ($data['type'] == 'tweet') {
			$data['tweet'] = "I'll always remember #Glitch for " . trim(strip_tags($data['post']));
		} else {
			$data['tweet'] = character_limiter(strip_tags($data['post']), 135);

			if (strlen($data['post']) > 500) {
				$data['type'] .= '-long';
			}
		}

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
				$row->post = character_limiter(strip_tags($row->post), 120);
			} else {
				$row->post = strip_tags($row->post);
			}
		}

		echo json_encode($posts);
	}

	public function jsonp ()
	{
		$this->load->helper('date');

		$post = $this->db
			->select('name, timestamp, post, id, type')
			->from('players p')
			->join('posts o', 'o.player_id = p.player_id')
			->order_by('RAND()')
			->limit(1)
			->get()
			->row();

		$post->time = glitch_time($post->timestamp);

		echo "process (" . json_encode($post) . ");";
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
			// Don't use pagination on player pages
			$config['total_rows'] = 0;

			$post = $query
				->where('p.player_id', $player_id)
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
		}
		
		$this->load->view('list', array (
			'post' => $post
		));
		
	}

}

/* End of file view.php */
/* Location: ./application/controllers/view.php */
