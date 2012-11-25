<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twitter extends CI_Controller {

	public function index()
	{
		// How many tweets should we do every time this is ran? 
		$tweets = 8; 

		// t.co wrapping affects link lengths
		$link_length = 20;
		$post_limit = 140 - $link_length - 1;

		// Must be ran from command line 
		if (!$this->input->is_cli_request()) die();

		$this->load->helper('twitter');
		$this->load->helper('text');

		$this->db->trans_start();

		$last_tweeted = (int) $this->db
			->get_where('admin', 
				array('setting' => 'last_tweeted_post'))
			->row()
			->value;

		$query = $this->db
			->get_where('posts', 
				array (
					'id > ' => $last_tweeted, 
					'id <=' => $last_tweeted + $tweets
				));

		// No need to continue if there aren't any new posts
		if ($query->num_rows() === 0) {
			return; 
		}

		// Obtain the last ID in the result to update the last tweeted thingy
		$last_result = $query->last_row()->id;

		$this->db
			->where('setting', 'last_tweeted_post')
			->set('value', $last_result)
			->update('admin');

		$this->db->trans_complete();

		$tmhOAuth = new tmhOAuth (array(
			'consumer_key'		=> $this->config->item('consumer_key'),
			'consumer_secret'	=> $this->config->item('consumer_secret'),
			'user_token'		=> $this->config->item('user_token'),
			'user_secret'		=> $this->config->item('user_secret'),
		));

		foreach ($query->result() as $row) {
			if ($row->type == 'tweet') {
				$row->post = "I'll always remember #Glitch for " . $row->post;
			}

			$post_content = strip_tags($row->post);
			$post_content = html_entity_decode($post_content, NULL, 'UTF-8');

			// mb_ prefixed functions used since there are memories with unicode chars
			if (mb_strlen($post_content) > $post_limit) {
				$post_content = mb_substr($post_content, 0, $post_limit - 1) . '…';
			}

			$post_content .= ' ' . site_url($row->id);
			
			$code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update.json'), array(
				'status' => $post_content
			));

			if ($code != 200) {
				log_message('error', "$post_content - " . $row->id .
					" " . $tmhOAuth->response['response']);
			}
		}

	}

}

/* End of file twitter.php */
/* Location: ./application/controllers/twitter.php */
