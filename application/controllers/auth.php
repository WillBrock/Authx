<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('authx');
		$this->load->database();
	}
	
	/**
	 * Do whatever you want with this
	 */
	function index()
	{
			$this->load->view('auth/message');
	}
	
	/**
	 * Main login
	 * 
	 * 
	 */
	function login()
	{
		$this->authx->login();
	} 	
	
	/**
	 * Logout
	 * 
	 * 
	 */
	function logout()
	{
		$this->authx->logout();
	}
	
	/**
	 * Main register
	 * 
	 * 
	 */
	function register()
	{
		$this->authx->register();
	}
	
	/**
	 * Handles the confirm registration process
	 * 
	 * 
	 * If users dont have to register by email then this can be removed.
	 */
	function confirm_registration( $activation_key = '' )
	{
		$this->authx->confirm_registration($activation_key);
	}

	/**
	 * Handles the sending of a email to reset the users password
	 * 
	 * 
	 */
	function forgot_password()
	{
		$this->authx->forgot_password();
	}
	
	/**
	 * Handles the users new password
	 * 
	 * 
	 */
	function reset_password( $user_id, $key )
	{
		$this->authx->reset_password($user_id, $key);
	}

	/**
	 * Handles deleting a users account
	 * 
	 * 
	 */
	function delete_account()
	{
		$this->authx->delete_account();
	}
	
	/**
	 * If registration is not currently allowed you can have the user request to sign up
	 * 
	 * 
	 * This can be removed if it's not needed.
	 */
	function request_signup()
	{
		$this->authx->request_signup();
	}
	
	
}
