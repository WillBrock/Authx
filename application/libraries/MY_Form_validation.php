<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Form_validation extends CI_Form_validation {

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		
		$this->CI->config->load('auth', TRUE);
		$this->CI->lang->load('authx');
		$this->not_allowed_pw_list = $this->CI->config->item('not_allowed_pw_list', 'auth');
		$this->captcha_expire = $this->CI->config->item('captcha_expire', 'auth');
		$this->captcha_case_sensitive = $this->CI->config->item('captcha_case_sensitive', 'auth');
	}
	
	function _allowed_password( $str )
	{
		$this->CI->form_validation->set_message('_allowed_password', $this->CI->lang->line('allowed_password'));
		return (in_array($str, $this->not_allowed_pw_list) ? FALSE : TRUE);
	
	}
	
	function _password_include_digit( $str, $field )
	{
		$pattern = '/\d{'.$field.',}/';
		$match = preg_match($pattern, $str);
	
		$this->CI->form_validation->set_message('_password_include_digit', $this->CI->lang->line('password_include_digit'));
		return ($match ? true : false);
	}


	function _password_include_capital( $str, $field )
	{
		$pattern = '/[A-Z]{'.$field.',}/';
		$match = preg_match($pattern, $str);
	
		$this->CI->form_validation->set_message('_password_include_capital', $this->CI->lang->line('password_include_capital'));
	
		return ($match ? true : false);
	}
	
	
	function _registration_token( $str, $token )
	{
		return ($str == $token ? true : false);
	}
	
	 
	
	/**
	* Callback function. Check if CAPTCHA test is passed.
	*
	* @param	string
	* @return	bool
	*/
	function _check_captcha( $code )
	{
		$time = $this->CI->session->flashdata('captcha_time');
		$word = $this->CI->session->flashdata('captcha_word');
	
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);
	
		if ( $now - $time > $this->captcha_expire ) {
			$this->CI->form_validation->set_message('check_captcha', $this->CI->lang->line('_check_captcha'));
			return FALSE;
	
		} elseif ( ($this->captcha_case_sensitive AND
		$code != $word) OR
		strtolower($code) != strtolower($word)) {
			$this->CI->form_validation->set_message('check_captcha', $this->CI->lang->line('_check_captcha'));
			return FALSE;
		}
		return TRUE;
	}
	
	
	
} 
