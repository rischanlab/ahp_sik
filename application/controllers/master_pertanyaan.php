<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pertanyaan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('bagian_model');
		$this->load->model('kriteria_model');
		$this->load->model('pertanyaan_model');
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
		$colModel['NAMA_BAGIAN'] = array('Nama Bagian',150,TRUE,'center',1);
		$colModel['NAMA_KRITERIA'] = array('Nama Kriteria',100,TRUE,'center',1);
		$colModel['NAMA_PERTANYAAN'] = array('Nama Pertanyaan',150,TRUE,'center',1);
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
							'title' => 'Master Pertanyaan',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/master_pertanyaan/grid_data_pertanyaan";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/master_pertanyaan/add';    
			}	
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_pertanyaan() 
	{
		$valid_fields = array('PERTANYAAN_ID','NAMA_PERTANYAAN');
		$this->flexigrid->validate_post('PERTANYAAN_ID','asc',$valid_fields);
		$records = $this->pertanyaan_model->get_data_flexigrid();
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->PERTANYAAN_ID,
										$no,										
										$row->NAMA_BAGIAN,
										$row->NAMA_KRITERIA,
										$row->NAMA_PERTANYAAN,
								'<a href='.base_url().'index.php/master_pertanyaan/edit/'.$row->PERTANYAAN_ID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
								'<a href='.base_url().'index.php/master_pertanyaan/delete/'.$row->PERTANYAAN_ID.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('nama_pertanyaan', 'Nama Pertanyaan', 'required');
		$this->form_validation->set_rules('bagian', 'Nama Bagian', 'required');
		$this->form_validation->set_rules('kriteria', 'Nama Kriteria', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	public function add()
	{
		$kriteria = $this->kriteria_model->get_kriteria();
		foreach($kriteria->result() as $row)
		{
			$data_kriteria[$row->KRITERIA_ID] = $row->NAMA_KRITERIA;
		}
		$bagian = $this->bagian_model->get_bagian();
		foreach($bagian->result() as $row)
		{
			$data_bagian[$row->BAGIAN_ID] = $row->NAMA_BAGIAN;
		}
		$data['bagian'] =  $data_bagian;
		$data['kriteria'] =  $data_kriteria;
		$data['content'] = $this->load->view('form_add_master_pertanyaan',$data,true);
		$this->load->view('main',$data);
	}
	
	public function add_process()
	{
		$data = array(
						'nama_pertanyaan' => $this->input->post('nama_pertanyaan'),
						'bagian_id' => $this->input->post('bagian'),
						'kriteria_id' => $this->input->post('kriteria')
					);
		if($this->cek_validasi())
		{
			$this->pertanyaan_model->add($data);
			redirect('master_pertanyaan');
		}
		else
		{
			$this->add();
			//redirect('jabatan/add');
		}
	}
	
	public function edit($pertanyaan_id)
	{
		$data = array(
					'nama_pertanyaan' => $this->input->post('nama_pertanyaan'),
					'bagian_id' => $this->input->post('bagian'),
					'kriteria_id' => $this->input->post('kriteria')
				);
		if($this->cek_validasi())
		{
			$this->pertanyaan_model->update($pertanyaan_id, $data);
			redirect('master_pertanyaan');
		}
		else
		{
			$kriteria = $this->kriteria_model->get_kriteria();
			foreach($kriteria->result() as $row)
			{
				$data_kriteria[$row->KRITERIA_ID] = $row->NAMA_KRITERIA;
			}
			$bagian = $this->bagian_model->get_bagian();
			foreach($bagian->result() as $row)
			{
				$data_bagian[$row->BAGIAN_ID] = $row->NAMA_BAGIAN;
			}
			$data['bagian'] =  $data_bagian;
			$data['kriteria'] =  $data_kriteria;
			$data['kriteria_dipilih'] = $this->pertanyaan_model->get_pertanyaan_by_id($pertanyaan_id)->row()->KRITERIA_ID;
			$data['bagian_dipilih'] = $this->pertanyaan_model->get_pertanyaan_by_id($pertanyaan_id)->row()->BAGIAN_ID;
			$data['nama_pertanyaan'] = $this->pertanyaan_model->get_pertanyaan_by_id($pertanyaan_id)->row()->NAMA_PERTANYAAN;
			$data['content'] = $this->load->view('form_edit_master_pertanyaan',$data,true);
			$this->load->view('main',$data);
		}
	}
	
	public function delete($pertanyaan_id)
	{
		$this->pertanyaan_model->delete($pertanyaan_id);
		redirect('master_pertanyaan');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
