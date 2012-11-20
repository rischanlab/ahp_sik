<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_bagian extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('bagian_model');
		//$this->cek_session();
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
		$colModel['NAMA_BAGIAN'] = array('Nama Bagian',150,TRUE,'center',1);
		$colModel['NILAI_MINIMUM'] = array('Nilai Minimum',100,TRUE,'center',1);
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
							'title' => 'Master Bagian',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/master_bagian/grid_data_bagian";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/master_bagian/add';    
			}	
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_bagian() 
	{
		$valid_fields = array('BAGIAN_ID','NAMA_BAGIAN');
		$this->flexigrid->validate_post('BAGIAN_ID','asc',$valid_fields);
		$records = $this->bagian_model->get_data_flexigrid();
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->BAGIAN_ID,
										$no,
										$row->NAMA_BAGIAN,
										$row->NILAI_MINIMUM,
								'<a href='.base_url().'index.php/master_bagian/edit/'.$row->BAGIAN_ID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
								'<a href='.base_url().'index.php/master_bagian/delete/'.$row->BAGIAN_ID.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('bagian', 'Nama Bagian', 'required');
		$this->form_validation->set_rules('nilai_minimum', 'Nilai Minimum', 'required|numeric');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	public function add()
	{
		$data['content'] = $this->load->view('form_add_master_bagian',null,true);
		$this->load->view('main',$data);
	}
	
	public function add_process()
	{
		$data = array(
						'nama_bagian' => $this->input->post('bagian'),
						'nilai_minimum' => $this->input->post('nilai_minimum')
					);
		if($this->cek_validasi())
		{
			$this->bagian_model->add($data);
			redirect('master_bagian');
		}
		else
		{
			$this->add();
			//redirect('jabatan/add');
		}
	}
	
	public function edit($bagian_id)
	{
		$data = array(
					'nama_bagian' => $this->input->post('bagian'),
					'nilai_minimum' => $this->input->post('nilai_minimum')
				);
		if($this->cek_validasi())
		{
			$this->bagian_model->update($bagian_id, $data);
			redirect('master_bagian');
		}
		else
		{
			$data['bagian'] = $this->bagian_model->get_bagian_by_id($bagian_id)->row()->NAMA_BAGIAN;
			$data['nilai_minimum'] = $this->bagian_model->get_bagian_by_id($bagian_id)->row()->NILAI_MINIMUM;
			$data['content'] = $this->load->view('form_edit_master_bagian',$data,true);
			$this->load->view('main',$data);
		}
	}
	
	public function delete($bagian_id)
	{
		$this->bagian_model->delete($bagian_id);
		redirect('master_bagian');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
