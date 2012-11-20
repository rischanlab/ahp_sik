<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pengguna extends CI_Controller {
	
	function __construct()
	{
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
		$colModel['hapus'] = array('Hapus',30,FALSE,'center',0);
			
		//setting konfigurasi pada bottom tool bar flexigrid
		$gridParams = array(
							'width' => 'auto',
							'height' => 330,
							'rp' => 15,
							'rpOptions' => '[15,30,50,100]',
							'pagestat' => 'Menampilkan : {from} ke {to} dari {total} data.',
							'blockOpacity' => 0,
							'title' => 'Master User',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/master_pengguna/grid_data_pengguna";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/master_pengguna/add';    
			}	
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_pengguna() 
	{
		$valid_fields = array('USERID','NAMA','USERNAME');
		$this->flexigrid->validate_post('USERID','asc',$valid_fields);
		$records = $this->user_model->grid_user();
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
								'<a href='.base_url().'index.php/master_pengguna/edit/'.$row->USERID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
								'<a href='.base_url().'index.php/master_pengguna/delete/'.$row->USERID.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('nama', 'Nama User', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('konf_password', 'Konfirmasi Password', 'required|matches[password]');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		$this->form_validation->set_message('matches', 'Kolom %s tidak sesuai dengan isian password !!');
		return $this->form_validation->run();
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
	
	public function add()
	{
		$role_pengguna = $this->user_model->get_daftar_role();
		foreach($role_pengguna->result() as $row){
			$data['role'][$row->KODEROLE] = $row->ROLE;
		}
		$data['content'] = $this->load->view('form_add_master_pengguna',$data,true);
		$this->load->view('main',$data);
	}
	
	public function add_process()
	{
		$data = array(
						'nama' => $this->input->post('nama'),
						'koderole' => $this->input->post('role'),
						'username' => $this->input->post('username'),
						'password' => md5($this->input->post('password'))
					);
		if($this->cek_validasi())
		{
			$this->user_model->add($data);
			redirect('master_pengguna');
		}
		else
		{
			$this->add();
			//redirect('jabatan/add');
		}
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
			redirect('master_pengguna');
		}
		else
		{
			$role_pengguna = $this->user_model->get_daftar_role();
			foreach($role_pengguna->result() as $row){
				$data['role'][$row->KODEROLE] = $row->ROLE;
			}
			$data['nama'] = $this->user_model->get_user($userid)->row()->NAMA;
			$data['role_dipilih'] = $this->user_model->get_user($userid)->row()->KODEROLE;
			$data['username'] = $this->user_model->get_user($userid)->row()->USERNAME;
			//$data['password'] = $this->user_model->get_user($userid)->row()->PASSWORD;
			//$data['konf_password'] = $this->user_model->get_user($userid)->row()->PASSWORD;
			$data['content'] = $this->load->view('form_edit_master_pengguna',$data,true);
			$this->load->view('main',$data);
		}
	}
	
	public function delete($userid)
	{
		$this->user_model->delete($userid);
		redirect('master_pengguna');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
