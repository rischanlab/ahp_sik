<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
    {
        // Call the Controller constructor
        parent::__construct();
		$this->load->model('user_model');
    }
	
	function index(){
		//$data['content'] = $this->load->view('login','',TRUE);
		$this->load->view('login');
	}
	
	function login_ulang()
	{
		echo "<script> alert('Maaf, Anda tidak punya hak untuk mengakses halaman ini. Silakan login terlebih dahulu !!');</script>";	
		$this->index();
	}
	
	function check_login()
	{
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|callback_cek_info_login|callback_validate_login');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('required', 'Field %s harus diisi');
		
		//cek apakah username dan password sudah diisikan dengan benar
		if ($this->form_validation->run()){
					redirect ('home'); //true gak bisa tapi false kok bisa ??/ //			
		}//end validation
		else{
			$this->index();
		}
	}
	
	function cek_info_login($username)
	{
		$user = $this->user_model->cek_username($username);
		
		if ($user->num_rows() < 0) {
			$this->form_validation->set_message('cek_info_login', 'Invalid Login');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	function validate_login()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		if($username && $password)
		{
			$result = $this->user_model->login($username, $password);
			if($result->num_rows() > 0)
			{
				foreach($result->result() as $row){
					$sess_array = array(
									'username' => $row->USERNAME,
									'login' => TRUE,
									'iduser' => $row->USERID,
									'kode_role' => $row->KODEROLE
									);
					$this->session->set_userdata($sess_array);
				}
				return TRUE;     
			}
			$this->form_validation->set_message('validate_login', 'Invalid username or password');
			return FALSE;
		}
		return FALSE;
	}

}
