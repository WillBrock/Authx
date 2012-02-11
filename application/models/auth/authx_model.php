<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authx_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();

		$this->config->load('auth', TRUE);
		$this->users_table 		= $this->config->item('users_table', 'auth');
		$this->groups_table 	= $this->config->item('groups_table', 'auth');
		$this->email_field 		= $this->config->item('email_field', 'auth');
		$this->username_field 	= $this->config->item('username_field', 'auth');
		$this->password_field 	= $this->config->item('password_field', 'auth');
		
	}
	
	
	/**
	 * Check if the user is logged in
	 * 
	 * @return bool
	 */
	function logged_in()
	{
		if ( $this->session->userdata('user_id') AND $this->session->userdata('status') == 'ACTIVE')
			return true;
		return false;
	}
	
	
	/**
	 * Main user stuff
	 */
	
	
	/**
	 * Get the users details
	 * 
	 * @param string username or email
	 * @return bool or object
	 */
	function get_user_details( $login )
	{
		$this->db->select('id, '.$this->email_field.', '.$this->password_field.', user_status, group_id, banned');
		$this->db->where($this->email_field, $login);
		$this->db->or_where($this->username_field, $login);
		$query = $this->db->get($this->users_table, 1);
		return ($query->num_rows() > 0 ? $query->row() : false);
	
	}
	
	/**
	 * Count the number of registered users
	 * 
	 * @return int
	 */
	function get_user_count()
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->users_table.' WHERE "user_status" = 1';
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	
	/**TODO
	 * @param	int
	 * @return 	bool
	 */
	function ban_user( $user_id )
	{
		$this->db->where('id', $user_id);
		return $this->db->update($this->users_table, array('banned' => 1));
	}
	
	/**
	 * Unban the user
	 * 
	 * @param int
	 * @return void
	 */
	function unban_user( $user_id )
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->users_table, array('banned' => 0));
	}
	
	
	
	/**
	 * Insert the new user in the database
	 * 
	 * @param string
	 * @param string
	 * @param string
	 * @param int
	 * @return int
	 */
	function insert_new_user( $email, $password, $activation_key, $group_id, $use_username )
	{
		$args = array(
					$this->email_field		=> $email,
					$this->password_field	=> $password,
					'user_status'			=> (!$activation_key ? 1 : 0),
					'group_id'				=> $group_id,
					$this->username_field	=> ($use_username ? strtolower($this->input->post('username')) : '')
		);
		$this->db->insert($this->users_table, $args);
		return $this->db->insert_id();
	}
	
	/**
	 * Insert user that requests to be signed up
	 * 
	 * @param array
	 * @return bool
	 */
	function insert_request_user( $args )
	{
		$this->db->insert('requests', $args);
		return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	}
	
	/**
	 * Update the users password and activate them
	 * 
	 * @param int
	 * @param string
	 * @return boolean
	 */
	function insert_new_password( $user_id, $pass )
	{
		$this->db->where('id', $user_id);
		$result = $this->db->update( $this->users_table, array(
				$this->password_field	=> $pass,
				'user_status'			=> 1 // This is just in case the activation email failed and just automaticall activate the user.
		) );
		return ($result > 0 ? TRUE : FALSE);
	}
	
	/**
	 * Activate the user
	 *
	 * @param string $activation_key
	 * @return int or bool
	 */
	function activate_user( $activation_key )
	{
		$this->db->select('user_id');
		$this->db->where('key', trim($activation_key));
		$result = $this->db->get('keys', 1);
		
		if ( $result->num_rows() != 1 )
			return FALSE;
		
		$user_id = $result->row();
		
		$this->db->where('id', $user_id->user_id);
		$result = $this->db->update( $this->users_table, array(
					'user_status' => 1,
		));
		return ($this->db->affected_rows() == 1 ? $user_id->user_id : FALSE);
	}
	
	/**
	 * Permentally delete a users account
	 * 
	 * @param int
	 * @return void
	 */
	function delete_account( $user_id )
	{
		$this->db->delete($this->users_table, array('id' => $user_id));
	}
	
	/**
	 * Did the user supply a correct password
	 * 
	 * @param int
	 * @param string
	 * @return bool
	 */
	function correct_password( $user_id, $password )
	{
		$this->db->select('id');
		$this->db->where('id', $user_id);
		$this->db->where($this->password_field, $password);
		$query = $this->db->get($this->users_table, 1);
		return ($query->num_rows() > 0 ? TRUE : FALSE);
	}
	
	/**
	 * Delete the key row
	 * 
	 * @param string
	 * @return void
	 */
	function delete_key_row( $key )
	{
		$this->db->delete('keys', array('key' => $key));
	}
	
	/**
	 * Check if the provided email exists already
	 * 
	 * @param string
	 * @return int or bool
	 */
	function email_exists( $email )
	{
		$this->db->select('id');
		$this->db->where($this->email_field, $email);
		$query = $this->db->get($this->users_table, 1);
	
		return ($query->num_rows() > 0 ? $query->row() : FALSE);
	}
	
	/**
	 * Get the users email
	 * 
	 * @param int
	 * @return string users email
	 */
	function get_user_email( $id )
	{
		$this->db->select($this->email_field);
		$this->db->where('id', $id);
		$query = $this->db->get($this->users_table, 1);
		$email = $query->row();
		return $email->{$this->email_field};
	}
	
	
	
	/**
	 * Logins
	 */
	
	
	/**
	 * Login the the user by setting the sessions
	 * 
	 * @param int
	 * @param string
	 * @param int
	 * @param string
	 * @return void
	 */
	function login_user( $id, $status, $group_id, $group )
	{ 
		$session_data = array(
					'user_id' 			=> $id,
					'status'			=> $status, // this is for future versions for 2 step authentication
					'group_id'			=> $group_id,
					'group'				=> $group
		);
		$this->session->set_userdata($session_data);
	}
	
	
	
	/**
	 * Store the users login
	 * 
	 * @param int
	 * @param string
	 * @param int 
	 * @return void
	 */
	function store_login( $user_id, $ip, $result )
	{
		return $this->db->insert('logins', array(
			'user_id' 		=> $user_id,
			'ip'		 	=> ip2long($ip),
			'result' 		=> $result
		));			
	}
	
	
	/**
	 * Failed login stuff
	 */
	

	/**
	 * Store Failed login
	 * 
	 * @param int
	 * @param int number of failed logins
	 * @param string
	 * @return void
	 */
	function store_failed_login( $user_id, $login_attempts, $ip )
	{
		if ( $login_attempts != '' )
		{
			$login_attempts = $login_attempts + 1;
			$data = array(
				'ip'		=> ip2long($ip),
				'count'		=> $login_attempts
			);
			
			$this->db->where('user_id', $user_id);
			$this->db->update('failed_logins', $data);
		}
		else
		{
			$data = array(
				'user_id'	=> $user_id,
				'ip'		=> ip2long($ip),
				'count'		=> 0
			);
			
			$this->db->insert('failed_logins', $data);
		}
	
	}
	
	/**
	 * Count the number of failed login attempts
	 * 
	 * @param string username or email
	 * @return int or bool
	 */
	function failed_login_attempts( $login )
	{
		$sql = "SELECT ".$this->users_table.".id, ".$this->users_table.".".$this->email_field.", failed_logins.count AS count
				FROM ".$this->users_table."
				INNER JOIN failed_logins ON
				failed_logins.user_id = ".$this->users_table.".id
				WHERE ".$this->users_table.".".$this->email_field." = ?
				LIMIT 1";
		
		$query = $this->db->query($sql, array($login));
		$row = $query->row();
		return ($query->num_rows() > 0 ? $row->count : FALSE);
	}
	
	
	/**
	 * Clear the users failed logins
	 * 
	 * @param int
	 * @return void
	 */
	function clear_failed_login( $user_id )
	{
		return $this->db->delete('failed_logins', array('user_id' => $user_id));
	}
	
	/**
	 * How long has the user been banned for
	 * 
	 * @param int
	 * @param int seconds
	 * @return bool
	 */
	function current_ban_duration( $user_id, $banned_length )
	{
		$this->_get_time_banned($user_id);
	
		$this->db->select('time');
		$this->db->where('user_id', $user_id);
		$this->db->where('UNIX_TIMESTAMP(time) >', time() - $banned_length);
		$query = $this->db->get('failed_logins');
			
		return ($query->num_rows() > 0 ? TRUE : FALSE);
	}
	
	
	/**
	 * Check to make sure the reset action didn't expire
	 * 
	 * @param int
	 * @param string
	 * @param int seconds
	 * @return bool
	 */
	function reset_valid( $user_id, $key, $length_valid )
	{
		$this->db->select('time');
		$this->db->where('user_id', $user_id);
		$this->db->where('key', $key);
		$this->db->where('UNIX_TIMESTAMP(time) >', time() - $length_valid);
		$query = $this->db->get('keys');
			
		return ($query->num_rows() > 0 ? TRUE : FALSE);
	}
	
	
	//TODO I dont think im using this for anything
	function _get_time_banned( $user_id )
	{
		$this->db->select('time');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('failed_logins');
		$row = $query->row();
		return ($query->num_rows() > 0 ? $row->time : FALSE);
	}
	
	
	/**
	 * Insert a key for reset password or activation
	 * 
	 * @param int user id
	 * @param string
	 * @param string action for the key
	 * @param void
	 */
	function insert_key( $id, $key, $action )
	{
		$this->db->insert('keys', array(
			'user_id' 	=> $id,
			'key'		=> $key,
			'action'	=> $action
		));
	}
		
	
	
	/**
	* is_group
	*
	* @param Array or String user group name(s)
	* @return bool
	* @author Phil Sturgeon
	**/
	public function is_group($check_group)
	{
		$user_group = $this->ci->session->userdata('group');
	
		if(is_array($check_group))
		{
			return in_array($user_group, $check_group);
		}
	
		return $user_group == $check_group;
	}


	/**
	 * Get a user group based on the group id
	 * 
	 * @param int group id
	 * @author Phil Sturgeon
	 */
	public function get_user_group( $group_id )
	{
		$this->db->select('name');
		$this->db->where('id', $group_id);
		$query = $this->db->get($this->groups_table, 1);
		return $query->row();
	}




	/*
	 * Users meta
	 */
	
	
	
	
	
	
	
	
	
	/*
	 * 
	 * Mainly Admin things for future versions
	 * 
	 */


}