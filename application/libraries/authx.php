<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Authx {
	
	
	// $CI is used with get_instance()
	protected $CI;
	protected $current_ip;
	protected $message;
	protected $error;

	function __construct()
	{
		$this->CI =& get_instance();
		
		$this->CI->load->database(); // ?????????
		$this->CI->load->model('auth/authx_model');
		$this->CI->lang->load('authx');
		$this->CI->config->load('auth', TRUE);
		$this->current_ip = $this->CI->input->ip_address();
		
		
		/*
		 * Set all of the configuration options.
		 * Not sure if this is the best way but it makes the library code much cleaner
		 * by being a shorter line of text when calling the option.
		 * All config options except not_allowed_pw_list. 
		 */
		
		// General
		$this->admin_email 						= $this->CI->config->item('admin_email', 'auth');
		$this->use_username						= $this->CI->config->item('use_username', 'auth');
		$this->home_page						= $this->CI->config->item('home_page', 'auth');
		
		// Messages
		$this->message_start_delimiter			= $this->CI->config->item('message_start_delimiter', 'auth');
		$this->message_end_delimiter			= $this->CI->config->item('message_end_delimiter', 'auth');
		$this->error_start_delimiter			= $this->CI->config->item('error_start_delimiter', 'auth');
		$this->error_end_delimiter				= $this->CI->config->item('error_end_delimiter', 'auth');
		
		// Database
		$this->users_table						= $this->CI->config->item('users_table', 'auth');
		$this->groups_table						= $this->CI->config->item('groups_table', 'auth');
		$this->default_group_id					= $this->CI->config->item('default_group_id', 'auth');
		$this->password_field					= $this->CI->config->item('password_field', 'auth');
		$this->username_field					= $this->CI->config->item('username_field', 'auth');
		
		// Register
		$this->allow_registration				= $this->CI->config->item('allow_registration', 'auth');
		$this->number_allowed_members			= $this->CI->config->item('number_allowed_members', 'auth');
		$this->registration_captcha				= $this->CI->config->item('registration_captcha', 'auth');
		$this->email_activation					= $this->CI->config->item('email_activation', 'auth');
		$this->email_admin_register				= $this->CI->config->item('email_admin_register', 'auth');
		$this->registration_token				= $this->CI->config->item('registration_token', 'auth');
		$this->min_username_length				= $this->CI->config->item('min_username_length', 'auth');
		$this->max_username_length				= $this->CI->config->item('max_username_length', 'auth');
		$this->registration_view				= $this->CI->config->item('registration_view', 'auth');
		$this->request_signup					= $this->CI->config->item('request_signup', 'auth');
		$this->request_signup_page				= $this->CI->config->item('request_signup_page', 'auth');
		$this->send_welcome_email				= $this->CI->config->item('send_welcome_email', 'auth');
		
		// Login
		$this->login_allowed					= $this->CI->config->item('login_allowed', 'auth');	
		$this->store_user_logins				= $this->CI->config->item('store_user_logins', 'auth');
		$this->login_captcha					= $this->CI->config->item('login_captcha', 'auth');
		$this->login_attempts_before_captcha	= $this->CI->config->item('login_attempts_before_captcha', 'auth');
		$this->max_login_attempts				= $this->CI->config->item('max_login_attempts', 'auth');
		$this->ban_duration						= $this->CI->config->item('ban_duration', 'auth');
		$this->login_input						= $this->CI->config->item('login_input', 'auth');
		$this->login_by_username				= $this->CI->config->item('login_by_username', 'auth');
		$this->remember_user					= $this->CI->config->item('remember_user', 'auth');
		$this->login_view						= $this->CI->config->item('login_view', 'auth');
		
		// Emailing
		$this->email_directory					= $this->CI->config->item('email_directory', 'auth');
		$this->email_extension					= $this->CI->config->item('email_extension', 'auth');
		$this->mailtype							= $this->CI->config->item('mailtype', 'auth');
		$this->email_field						= $this->CI->config->item('email_field', 'auth');
				
		// Captcha
		$this->captcha_path						= $this->CI->config->item('captcha_path', 'auth');
		$this->captcha_fonts_path				= $this->CI->config->item('captcha_fonts_path', 'auth');
		$this->captcha_width					= $this->CI->config->item('captcha_width', 'auth');
		$this->captcha_height					= $this->CI->config->item('captcha_height', 'auth');
		$this->captcha_font_size				= $this->CI->config->item('captcha_font_size', 'auth');
		$this->captcha_grid						= $this->CI->config->item('captcha_grid', 'auth'); // TODO
		$this->captcha_expire					= $this->CI->config->item('captcha_expire', 'auth');
		$this->captcha_case_sensitive			= $this->CI->config->item('captcha_case_sensitive', 'auth');
		
		// Passwords
		$this->min_password_length				= $this->CI->config->item('min_password_length', 'auth');
		$this->max_password_length				= $this->CI->config->item('max_password_length', 'auth');
		$this->password_include_letter			= $this->CI->config->item('password_include_letter', 'auth'); // TODO
		$this->password_include_digit			= $this->CI->config->item('password_include_digit', 'auth');
		$this->password_include_capital			= $this->CI->config->item('password_include_capital', 'auth');
		$this->password_include_symbol			= $this->CI->config->item('password_include_symbol', 'auth'); // TODO
		$this->not_allowed_passwords			= $this->CI->config->item('not_allowed_passwords', 'auth'); 
		$this->not_allowed_pw_list				= $this->CI->config->item('not_allowed_pw_list', 'auth');
		$this->reset_pass_expire				= $this->CI->config->item('reset_pass_expire', 'auth');
		$this->password_salt					= $this->CI->config->item('password_salt', 'auth');
		
		// CI rules form validation messages
		$this->rules = $this->CI->lang->line('rules');
		$this->CI->form_validation->set_error_delimiters($this->error_start_delimiter, $this->error_end_delimiter);
		foreach( $this->rules as $rule => $message )
		{
			$this->CI->form_validation->set_message($rule, $message);
		}
		
		
		// profiler for debugging
		//$this->CI->output->enable_profiler(TRUE);
	}
	
	
	/**
	* __call
	*
	* Acts as a simple way to call model methods without loads of stupid alias'
	* 
	* From Ion Auth
	*
	**/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->CI->authx_model, $method) )
		{
			throw new Exception('Undefined method Authx::' . $method . '() called');
		}
	
		return call_user_func_array( array($this->CI->authx_model, $method), $arguments);
	}
	
	
	/**
	 * Handle the login
	 * 
	 * @return void
	 */
	function login()
	{
		$data = array();
		$data['message'] = '';

	 	if ( $this->login_allowed != TRUE )
			$this->authx_redirect('auth', $this->set_error('login_allowed'), 'auth'); 
		
		if ( $this->CI->authx_model->logged_in() )
			redirect($this->home_page); 
			
		/*if ( $this->allowed_ips AND ! in_array($this->current_ip, $this->allowed_ips) )
			$this->authx_redirect('auth', $this->set_error('ip_not_allowed'), 'auth');*/
		
		// set login and password form rules
		$this->CI->form_validation->set_rules($this->login_input, 'Login', 'required|trim');
		$this->CI->form_validation->set_rules('password', 'Password', 'required|trim');
	
		/* if ( $this->options['remember_user'] )
			$this->CI->form_validation->set_rules('remember', 'Remember me', 'integer'); */
		
		$login_attempts = $this->CI->authx_model->failed_login_attempts($this->CI->input->post($this->login_input));
		
		// Should the captcha be displayed
		$data['use_captcha'] = FALSE;
		if ( $this->login_captcha OR $this->login_attempts_before_captcha AND $login_attempts >= $this->login_attempts_before_captcha )
		{
			$data['captcha_html'] = $this->_create_captcha();
			$this->CI->form_validation->set_rules('captcha', 'Captcha', 'required|_check_captcha');
			$data['use_captcha'] = TRUE;
		}
	
		
		if ( $this->CI->form_validation->run() == TRUE )
		{
			// Grab the login input and password
			$login = $this->CI->input->post($this->login_input);
			$pw = $this->hashed_password($this->CI->input->post('password'));
			
			$user = $this->CI->authx_model->get_user_details($login);
			
			if ( !empty($user) )
			{
				if ( $user->user_status == 1 )
				{
					if ( $this->is_banned($user->id, $user->banned) == FALSE )
					{						
						if ( $user->{$this->password_field} === $pw ) 
						{			
							$group = $this->CI->authx_model->get_user_group($user->group_id);
							
							// log the user in
							$this->CI->authx_model->login_user($user->id, 'ACTIVE', $user->group_id, $group->name);
							
							// store login info
							if ( $this->store_user_logins )
								$this->CI->authx_model->store_login($user->id, $this->current_ip, 1);
							
							if ( $login_attempts != FALSE )
								$this->CI->authx_model->clear_failed_login($user->id);
							
							redirect($this->home_page);
							
						}
						else
						{
			
							if ( $this->login_attempts_before_captcha )
								$this->CI->authx_model->store_failed_login($user->id, $login_attempts, $this->current_ip);

							$login_attempts++;
							if ( $login_attempts == $this->max_login_attempts )
								$this->CI->authx_model->ban_user($user->id);
				
							// store main login info
							if ( $this->store_user_logins )
								$this->CI->authx_model->store_login($user->id, $this->current_ip, 0);
							
							
							$this->set_error('invalid_username_password');
						} // end password
					
					} else { $this->set_error('banned_user'); }
						
				} else { $this->set_error('registration_not_complete'); }
			} else { $this->set_error('invalid_username_password'); }
			
		}
		
		
		if ( $this->CI->session->flashdata('auth') )
			$data['message'] = $this->CI->session->flashdata('auth');
		elseif ( $this->error )
			$data['message'] = $this->error;
		elseif ( validation_errors() )
			$data['message'] = validation_errors();

		
		$data['allow_registration'] = $this->allow_registration;
		$data['request_signup'] = $this->request_signup;
		
		$this->CI->load->view($this->login_view, $data);
	} 	
		
		
	
	/**
	 * Handle the main registration
	 * 
	 * @return void
	 */
	function register()
	{
		$data = array();
		$data['message'] = '';
		
		if( ! $this->allow_registration OR $this->number_allowed_members !== 0 AND $this->CI->authx_model->get_user_count() >= $this->number_allowed_members )
		{	
			if ( $this->request_signup )
				$this->authx_redirect('auth', $this->set_error('request_signup'), $this->request_signup_page);
			else
				$this->authx_redirect('auth', $this->set_error('registration_closed'), 'auth');
		}
		
		if ( $this->use_username ) 
		{
			$username_field = $this->users_table . '.' . $this->username_field;
			$this->CI->form_validation->set_rules('username', 'Username', 'required|min_length['.$this->min_username_length.']|max_length['.$this->max_username_length.']|is_unique['.$username_field.']');
		}
		
		//TODO need to do something so it has the ability to add form fields for registration

		$email_field = $this->users_table . '.' . $this->email_field;
		$this->CI->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique['.$email_field.']');
		
		// Get password options
		$pw_rules = 'required';
		$pw_rules .= $this->password_options();

		$this->CI->form_validation->set_rules('password', 'Password', $pw_rules);
		$this->CI->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		
		// user must enter a code given to them to register
		if ( $this->registration_token )
			$this->CI->form_validation->set_rules('registration_token', 'Registration Token', 'required|max_length[15]|callback_registration_token['.$this->registration_token.']');
		
		// Should the captcha be displayed?
		$data['use_captcha'] = FALSE;
		if ( $this->registration_captcha )
		{
			$data['captcha_html'] = $this->_create_captcha();
			$this->CI->form_validation->set_rules('captcha', 'Captcha', 'required|_check_captcha');
			$data['use_captcha'] = TRUE;
		}
		
		if ($this->CI->form_validation->run() == TRUE )
		{			
			$email = $this->CI->input->post('email');
			$password = $this->CI->input->post('password');

			$activation_key = FALSE;
			if ( $this->email_activation )
				$activation_key = $this->random_token();
			
			// register the user
			$result = $this->register_user($email, $password, $activation_key, $this->use_username, $this->default_group_id);
				
			// if registering the user succeeded. 
			if ( $result == TRUE )
			{
							
				if ( $this->email_admin_register )
				{
					$admin_email_data = array();
					$admin_email_data['ip'] = $this->current_ip;
					$admin_email_data['email'] = $email;
					$admin_view_path = 'auth/'.$this->email_directory.'/admin_new_user_'.$this->email_extension;
					$admin_email_view = $this->CI->load->view($admin_view_path, $admin_email_data, TRUE);
					$this->send_email($this->admin_email, $this->admin_email, $this->CI->lang->line('admin_email_subject'), $admin_email_view);	
				}
					
				
				if ( $this->email_activation )
				{
					$email_data = array();
					$email_data['email'] = $email;
					$email_data['activation_key'] = $activation_key;
					$view_path = 'auth/'.$this->email_directory.'/activate_'.$this->email_extension;
					$email_view = $this->CI->load->view($view_path, $email_data, TRUE);
					$send_email = $this->send_email($email, $this->admin_email, $this->CI->lang->line('registration_email_subject'), $email_view);
					if ( $send_email == TRUE )
					{
						$this->set_message('registration_email_sent');
						//$this->authx_redirect('auth', $this->set_message('registration_email_sent'), 'auth');
					}
					else 
					{
						$this->set_error('registration_failed_email');
					} 
				}
				else 
				{
					if ( $this->send_welcome_email ) {
						$email_data = array();
						$email_data['email'] = $email;
						$email_view = 'auth/'.$this->email_directory.'/welcome_html';
						$email_view = $this->CI->load->view($email_view, $email_data, TRUE);
						$this->send_email($email_data['email'], $this->admin_email, $this->CI->lang->line('welcome_email_subject'), $email_view);
					}
					
					$this->authx_redirect('auth', $this->set_message('registered_user'), 'auth/login');
				}	

			
			} else { $this->set_error('failed_register_user'); }
			 
		}
		

		if ( $this->CI->session->flashdata('auth') )
			$data['message'] = $this->CI->session->flashdata('auth');
		elseif ( $this->error )
			$data['message'] = $this->error;
		elseif ( $this->message )
			$data['message'] = $this->message;
		elseif ( validation_errors() )
			$data['message'] = validation_errors();
		
		
		
		$data['registration_token']	= $this->registration_token;
		$data['use_username'] = $this->use_username;
		$this->CI->load->view($this->registration_view, $data);			
	}
	
	/**
	 * Allow users to request signup if signup is not allowed
	 * 
	 * @return void
	 */
	function request_signup()
	{
		$data = array();
		$data['message'] = '';
		
		if ( ! $this->request_signup)
			redirect('');
		
		$this->CI->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
		$email_field = $this->users_table . '.' . $this->email_field;
		$this->CI->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique['.$email_field.']');
		
		
		if ( $this->CI->form_validation->run() == TRUE )
		{
			
			$args = array(
				'name'	=> $this->CI->input->post('name'),
				'email' => $this->CI->input->post('email')		
			);
			
			if ( $this->CI->authx_model->insert_request_user($args) )
				$data['message'] = $this->set_message('requested_signup_success');
			else 
				$data['message'] = $this->set_error('requested_signup_fail');
			
		}
		else 
		{
			$data['message'] = validation_errors();
		}
		
		$this->CI->load->view($this->request_signup_page, $data);
	}
	
	
	/**
	 * From Ion Auth
	 * Logout the user
	 * 
	 * @return void
	 */
	function logout()
	{
		// See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
		$this->CI->session->set_userdata(array('user_id' => '',  'status' => ''));
		
		$this->CI->session->sess_destroy();
		
		redirect('auth/login');
	}
	
	/**
	 * Handles everything when the user forgets their password
	 * By Default this is called from the 'forgot_password' method in the auth controller
	 * 
	 * @return void
	 */
	function forgot_password()
	{
		$data = array();
		$data['message'] = '';
		$email = $this->CI->input->post('email');
		
		$this->CI->form_validation->set_rules('email', 'Email', 'required|max_length[40]|valid_email|trim');
		
		if ( $this->CI->form_validation->run() == TRUE )
		{
			if ( $result = $this->CI->authx_model->email_exists($email) )
			{
				$email_data['token'] = $this->random_token();
				$email_data['id'] = $result->id;
				$this->CI->authx_model->insert_key($result->id, $email_data['token'], 'pw_reset');
				$view_path = 'auth/'.$this->email_directory.'/reset_password_'.$this->email_extension;
				$message = $this->CI->load->view($view_path, $email_data, TRUE);
				$this->send_email($email, $this->admin_email, 'Reset Password', $message);
			}
		// Sending a success message no matter what so a hacker cant guess emails
		$data['message'] = $this->set_message('forgot_password_send');
		}
		else 
		{
			$data['message'] = validation_errors();
		}
		
		$this->CI->load->view('auth/forgot_password', $data);
	}
	
	/**
	 * Reset the users password
	 * 
	 * @param int
	 * @param key
	 * @return void
	 */
	function reset_password( $user_id, $key )
	{	
		if ( empty($user_id) OR empty($key) )
			redirect('auth/login');
		
		$data = array();		
		
		$pw_rules = 'required';
		$pw_rules .= $this->password_options();
	
		$this->CI->form_validation->set_rules('pass', 'Password', $pw_rules);
		$this->CI->form_validation->set_rules('confirm_pass', 'Confirm Password', 'required|matches[pass]|trim');
	
		if ( $this->CI->form_validation->run() == TRUE )
		{
			$reset_valid = $this->CI->authx_model->reset_valid($user_id, $key, $this->reset_pass_expire);
			if ( $reset_valid )
			{
				$pass = $this->hashed_password($this->CI->input->post('pass'));
				$result = $this->CI->authx_model->insert_new_password($user_id, $pass);
		
				if ( $result == TRUE )
				{
					$this->CI->authx_model->delete_key_row($key);
					$this->authx_redirect('auth', $this->set_message('password_reset_success'), 'auth/login');
				}
				else
				{
					$this->set_error('password_reset_fail');
					//$this->authx_redirect('register', 'Something went wrong. Please try again.');
				}
				
			}
			else
			{
				$this->CI->authx_model->delete_key_row($key);
				$this->authx_redirect('auth', $this->set_error('invalid_reset_key'), 'auth/forgot_password');
			}	
			
		}
		else
		{
			$data['message'] = validation_errors();
		}

		$this->CI->load->view('auth/reset_password', $data);
	}
	
	
	/**
	 * Verify user registration from email link
	 * 
	 * @param string email activation key
	 * @return void
	 */
	function confirm_registration( $activation_key )
	{
		$email_data = array();
		
		if (empty($activation_key))
			redirect('auth/login');
		
		$user_id = $this->CI->authx_model->activate_user($activation_key);
		if ( $user_id )
		{	
			$this->CI->authx_model->delete_key_row($activation_key);
			
			if ( $this->send_welcome_email ) {
				$email_data['email'] = $this->CI->authx_model->get_user_email($user_id);
				$email_view = 'auth/'.$this->email_directory.'/welcome_html';
				$email_view = $this->CI->load->view($email_view, $email_data, TRUE);
				$this->send_email($email_data['email'], $this->admin_email, $this->CI->lang->line('welcome_email_subject'), $email_view);
			}
			
			//TODO maybe log the user in right here
			$this->authx_redirect('auth', $this->set_message('registered_user'), 'auth/login');
		}
		else 
		{
 			$this->set_error('activate_user_fail');
		}
		
		$data['error'] = $this->error;
		$this->CI->load->view('auth/message', $data);
	}
	
	/**
	 * Permantly delete the users account
	 * 
	 * @return void
	 */
	function delete_account()
	{
		if ( ! $this->authx_model->logged_in() )
		redirect('auth/login');
	
		$this->CI->form_validation->set_rules('password', 'Password', 'required|trim');
	
		if ( $this->CI->form_validation->run() === TRUE )
		{
			$password = $this->CI->input->post('password');
			if ( $this->CI->authx_model->correct_password($this->CI->session->userdata('user_id'), $this->hashed_password($password)) )
			{
				$this->CI->authx_model->delete_account($user_id);
				
				//TODO send delete email
				
				$this->authx_redirect('auth', $this->set_message('account_deleted'), 'auth/register');
			}
	
		}
		else 
		{
			$data['message'] = validation_errors(); 
		}
	
	
		$this->CI->load->view('auth/delete_account', $data);
	}
	
	
	/**
	 * @param	int
	 * @param	int 1 or 0
	 * @return	boolean
	 * TODO get rid of this after testing
	 */
	// should prolly change name to is_banned()
	/* function ban_status( $user_id, $banned )
	{
		$status = FALSE;
	
		if ( $banned != 1 )
		{
			return TRUE;
		}
		else
		{
			if ( $this->CI->authx_model->current_ban_duration($user_id, $this->options['ban_duration']) )
			{
				$this->set_error('You are not allowed to login right now.');
				$status = FALSE;
			}
			else
			{
				$this->CI->authx_model->unban_user($user_id);
				$this->CI->authx_model->clear_failed_login($user_id);
				$status = TRUE;
			}
			return $status;
		}
	} */
	
	/**
	 * 
	 * Check to see if the user is banned
	 * 
	 * @param int user id
	 * @param int 1 or 0
	 * @return bool
	 */
	function is_banned( $user_id, $banned )
	{
	
		if ( $banned == 1 )
		{
			if ( $this->CI->authx_model->current_ban_duration($user_id, $this->ban_duration) )
			{
				return TRUE;
			}
			else
			{
				$this->CI->authx_model->unban_user($user_id);
				$this->CI->authx_model->clear_failed_login($user_id);
				return FALSE;
			}
		}
		
		return FALSE;
	}
	
	
	
	
	
	/**
	 * Register a new user
	 * 
	 * @param string users email
	 * @param string users password
	 * @param string random token
	 * @param boolean 
	 * @param int default group id
	 * @return boolean
	 */
	function register_user( $email, $password, $activation_key, $use_username, $group_id )
	{
			
		// add username to the array if using username is true
		/* if ( $use_username )
			array_unshift($args, array('username' => strtolower($this->CI->input->post('username')))); */

		$this->CI->db->trans_begin();
		
		$result_id = $this->CI->authx_model->insert_new_user($email, $this->hashed_password($password), $activation_key, $group_id, $use_username);
		
		if ( $activation_key != FALSE )
			$this->CI->authx_model->insert_key($result_id, $activation_key, 'register');
		
	
		if ( $this->CI->db->trans_status() === FALSE )
		{
			$this->CI->db->trans_rollback();
			return FALSE;
		}
		else 
		{
			$this->CI->db->trans_commit();
			return TRUE;
		}
	}
	
	/**
	 * Basic redirect method that allows setting a message
	 * 
	 * @param string session identifier
	 * @param string redirect message
	 * @param string redirect page
	 */
	function authx_redirect( $hook, $message, $page )
	{
		$this->CI->session->set_flashdata($hook, $message);
		redirect($page);
	}
	
	/**
	 * Set form messages
	 * 
	 * @param string  key for language file
	 */
	function set_message( $message )
	{
		$this->message = $this->message_start_delimiter;
		$this->message .= $this->CI->lang->line($message);
		$this->message .= $this->message_end_delimiter;	
		return $this->message;
	}
	
	
	/**
	 * Set error messages
	 * 
	 * @param string key for language file
	 */
	function set_error( $error )
	{
		$this->error = $this->error_start_delimiter;
		$this->error .= $this->CI->lang->line($error);
		$this->error .= $this->error_end_delimiter;
		return $this->error;
	}
	
	/**
	 * Create a random token
	 * 
	 * @return string
	 */
	function random_token()
	{
		return sha1(uniqid(rand(), true));
	}
	
	/**
	 * Hash and de-hash the password
	 * 
	 * @param string
	 * @return string
	 */
	function hashed_password( $password )
	{
		$password = $this->password_salt;
		$password .= sha1($password);
		return $password;
	}

	/**
	 * Send a email
	 * 
	 * @param string to email
	 * @param string from email
	 * @param string email subject
	 * @param string email view
	 * @return void
	 */
	function send_email( $to_email, $from_email, $subject, $message )
	{
		//TODO need to change this when I test live site
		$this->CI->load->library('email');
		
		$this->CI->email->clear();
		 
		$config['mailtype'] = $this->mailtype;
		
		$this->CI->email->initialize($config);
		$this->CI->email->set_newline("\r\n");
		$this->CI->email->from($from_email);
		$this->CI->email->to($to_email);
		$this->CI->email->subject($subject);
		$this->CI->email->message($message);
		$this->CI->email->set_alt_message();
		return ($this->CI->email->send() ? TRUE : FALSE);
	}
	
		
	/**
	 * Took this from TankAuth
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->CI->load->helper('captcha');
	
		$cap = create_captcha(array(
				'img_path'		=> './'.$this->captcha_path,
				'img_url'		=> base_url().$this->captcha_path,
				'font_path'		=> './'.$this->captcha_fonts_path,
				'font_size'		=> $this->captcha_font_size,
				'img_width'		=> $this->captcha_width,
				'img_height'	=> $this->captcha_height,
				'show_grid'		=> $this->captcha_grid,
				'expiration'	=> $this->captcha_expire,
		));
	
		// Save captcha params in session
		$this->CI->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));
	
		return $cap['image'];
	}
	
	
	 
	
	/**
	* Sets all the password rules based on the config file for form validation.
	* 
	* @return string password rules
	*/
	function password_options()
	{
		$pw_rules = '';
	
		$pw_rules .= '|min_length['.$this->min_password_length.']';
		$pw_rules .= '|max_length['.$this->max_password_length.']';
	
		// all the pw options for reset and register.
		if ( $this->not_allowed_passwords )
			$pw_rules .= '|_allowed_password';
	
		if ( $this->password_include_digit != FALSE )
			$pw_rules .= '|_password_include_digit['.$this->password_include_digit.']';
		
		if ( $this->password_include_capital )
			$pw_rules .= '|_password_include_capital['.$this->password_include_capital.']';
	
		return $pw_rules;
	}
	
	
	/*function form_validation_messages()
	{
		$this->rules = $this->CI->lang->line('rules');
		$this->CI->form_validation->set_error_delimiters($this->error_start_delimiter, $this->error_end_delimiter);
		foreach( $this->rules as $rule => $message )
		{				
			$this->CI->form_validation->set_message($rule, $message);
		}
	}
	*/
	

}
