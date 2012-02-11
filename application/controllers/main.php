<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
	
	function Main()
	{
		parent::__construct();
		
		$this->load->model('auth/authx_model');
		// profiler
		// for debugging
		//$this->output->enable_profiler(TRUE);
		
		if ( ! $this->authx_model->logged_in() )
		{
			redirect('auth/login');
		} 
		
	}
	
	function index()
	{	
		
		$this->load->view('main/homepage');
	}
}