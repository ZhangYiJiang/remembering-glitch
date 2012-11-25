<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	// API client ID and secret are set from the main config.php file
	// See the README for how to set them properly 

	public function __construct()
    {
		parent::__construct();
    }
	
	/**
	 * Redirects user to the login error page
	 * 
	 * @param $message Message to be displayed on error page
	 */
	
	private function _error ($message)
	{
		show_error($error);
	}
	
	/**
	 * Redirects user to Glitch oauth site to start
	 * the authentication process - Step one
	*/
	
	public function index()
	{
		// What are you doing here if you're already logged in?
		if ( $this->session->userdata('id') ) {
			redirect ("/add");
		}
		
		$args = array (
			'response_type'	=> 'code',
			'client_id'		=> $this->config->item('client_id'),
			'redirect_uri'	=> site_url('login/auth'),
			'scope'			=> 'identity'
		);
		
		redirect (build_get_url('http://api.glitch.com/oauth2/authorize', $args));
		
	}
	
	/**
	 * Users are redirected here after authenticating at Glitch,
	 * step two: obtain token to exchange for player data
	*/
	
	public function auth ()
	{
		
		// Catch any errors from Glitch
		if ( $this->input->get('error') ) 
		{
			$error = $this->input->get('error_description');
			$this->_error("Glitch's authentication 
				endpoint returned an error: " . $error);
			
		}
		
		$this->load->helper('curl');
		
		$code = $this->input->get('code');
		
		$args = array (
			'grant_type'	=> 'authorization_code',
			'code'			=> $code,
			'client_id'		=> $this->config->item('client_id'),
			'client_secret'	=> $this->config->item('client_secret'),
			'redirect_uri'	=> site_url('login/auth')
		);
		
		// Exchange code for token using HTTP POST
		// TODO: Error handling and graceful exit 
		$ret = curl_http_post("http://api.glitch.com/oauth2/token", $args);
		$obj = @json_decode($ret['body'], true);
		
		// Step three: Get player info using token
		$token = $obj['access_token'];
		$info = json_decode( file_get_contents('http://api.glitch.com/simple/players.info?oauth_token=' . $token ), true );
		
		// Error handling
		$status = $ret['status'];
		
		if ($status != 200 && $status != 400)
		{
			$this->_error('Unexpected HTTP status code from Glitch servers: ' . $status);
		} else if ( !is_array($info) || !count($info) )
		{
			$this->_error('Unable to decode JSON from Glitch servers');
		} else if ( isset($info['error']) )
		{
			$this->_error("Glitch's authentication 
				endpoint returned an error: " . $info['error']);
		}
		
		$id = $info['player_tsid'];
		
		// Check if user exist in our database
		$query = $this->db->get_where('players', array(
			'player_id' => $id
		));
		
		// Create user if he doesn't exist
		if ($query->num_rows() == 0) {
			$this->create($id);
		} else {
			// Initiate session with user data
			$data = $query->row_array();
			$this->create_session($data);
		}
		
		// Go back to add page
		redirect("/add");
	}
	
	/* 
	 * Create user 
	 */
	
	private function create ($id)
	{
		$this->load->helper('file');
		
		// Sanity check
		$query = $this->db->get_where('players', array(
			'player_id' => $id
		));
		
		if ($query->num_rows() > 0) {
			die ('Player already in database');
		}
		
		// Get API data
		$json = file_get_contents('http://api.glitch.com/simple/players.fullInfo?player_tsid='.$id);
		$data = json_decode($json, true);

		// Grab the player avatar 
		$avatar_path = "assets/avatar/$id.png";
		write_file($avatar_path, file_get_contents($data['avatar']['172']));
		
		$insert_data = array (
			'player_id'	=> $id,
			'name'		=> $data['player_name'],
			'avatar'	=> site_url($avatar_path)
		);
		
		$this->db->insert('players', $insert_data);
		$this->create_session($insert_data);
	}

	private function create_session($data) 
	{
		$this->session->set_userdata($data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */