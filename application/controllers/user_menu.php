<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_menu extends CI_Controller {

	function __construct()
    {
        // Call the Controller constructor
        parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('user_model');
		$this->cek_session();
    }
	
	function cek_session()
	{	
		$kode_role = $this->session->userdata('kode_role');
		if($kode_role == '' || $kode_role != 1)
		{
			redirect('login/login_ulang');
		}
	}
	
	public function index()
	{
		$this->grid();
	}
	
	public function grid()
	{
		//$kode_role = $this->session->userdata('kode_role');
		$colModel['no'] = array('No',20,TRUE,'center',0);
		$colModel['NAMA'] = array('Nama User',150,TRUE,'center',1);
		$colModel['USERNAME'] = array('Username',100,TRUE,'center',1);
		$colModel['ROLE'] = array('Role Pengguna',100,TRUE,'center',1);
		$colModel['ubah'] = array('Ubah',30,FALSE,'center',0);
			
		//setting konfigurasi pada bottom tool bar flexigrid
		$gridParams = array(
							'width' => 'auto',
							'height' => 330,
							'rp' => 15,
							'rpOptions' => '[15,30,50,100]',
							'pagestat' => 'Menampilkan : {from} ke {to} dari {total} data.',
							'blockOpacity' => 0,
							'title' => 'Data User',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		//$buttons[] = array('Tambah','add','spt_js');
		//$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/user_menu/grid_data_pengguna";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams);
		$data['js_grid'] = $grid_js;
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_pengguna() 
	{
		$iduser = $this->session->userdata('iduser');
		$kode_role = $this->session->userdata('kode_role');
		$valid_fields = array('USERID','NAMA','USERNAME');
		$this->flexigrid->validate_post('USERID','asc',$valid_fields);
		$records = $this->user_model->grid_user_by_id($iduser);
		
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->USERID,
										$no,
										$row->NAMA,
										$row->USERNAME,
										$row->ROLE,
										'<a href='.base_url().'index.php/user_menu/edit/'.$row->USERID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi_edit()
	{	
		$this->form_validation->set_rules('nama', 'Nama User', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('konf_password', 'Konfirmasi Password', 'matches[password]');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		$this->form_validation->set_message('required', 'Kolom %s tidak sesuai dengan isian password !!');
		return $this->form_validation->run();
	}
	
	public function edit($userid)
	{
		$password = $this->user_model->get_user($userid)->row()->PASSWORD;
		if($this->input->post('password')!='') $password = md5($this->input->post('password'));
		$data = array(
					'nama' => $this->input->post('nama'),
					'koderole' => $this->input->post('role'),
					'username' => $this->input->post('username'),
					'password' => $password
				);
		if($this->cek_validasi_edit())
		{
			$this->user_model->update($userid, $data);
			redirect('user_menu');
		}
		else
		{
			$role_pengguna = $this->user_model->get_daftar_role();
			foreach($role_pengguna->result() as $row){
				$data['role'][$row->KODEROLE] = $row->ROLE;
			}
			$data['nama'] = $this->user_model->get_user($userid)->row()->NAMA;
			$data['role_dipilih'] = $this->user_model->get_user($userid)->row()->KODEROLE;
			$data['nama_role'] = $this->user_model->get_user($userid)->row()->ROLE;
			$data['username'] = $this->user_model->get_user($userid)->row()->USERNAME;
			//$data['password'] = $this->user_model->get_user($userid)->row()->PASSWORD;
			//$data['konf_password'] = $this->user_model->get_user($userid)->row()->PASSWORD;
			$data['content'] = $this->load->view('form_edit_master_pengguna',$data,true);
			$this->load->view('main',$data);
		}
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
		//$this->load->view('login');
	}

}