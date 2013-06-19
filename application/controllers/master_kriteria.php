<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_kriteria extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('kriteria_model');
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
		$colModel['nama_kriteria'] = array('Nama Kriteria',150,TRUE,'center',1);
		$colModel['prioritas_kriteria'] = array('Prioritas',100,TRUE,'center',1);
		$colModel['subkriteria'] = array('Subkriteria',60,FALSE,'center',0);
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
							'title' => 'Master Kriteria',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/master_kriteria/grid_data_kriteria";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/master_kriteria/add';    
			}	
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_kriteria() 
	{
		$valid_fields = array('KRITERIA_ID','NAMA_KRITERIA');
		$this->flexigrid->validate_post('KRITERIA_ID','asc',$valid_fields);
		$records = $this->kriteria_model->get_data_flexigrid();
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->KRITERIA_ID,
										$no,
										$row->NAMA_KRITERIA,
										$row->PRIORITAS_KRITERIA,
								'<a href='.base_url().'index.php/master_subkriteria/grid/'.$row->KRITERIA_ID.'><img border=\'0\' src=\''.base_url().'images/icon/doc.png\'></a>',
								'<a href='.base_url().'index.php/master_kriteria/edit/'.$row->KRITERIA_ID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
								'<a href='.base_url().'index.php/master_kriteria/delete/'.$row->KRITERIA_ID.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('kriteria', 'Nama Kriteria', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	public function add()
	{
		$data['content'] = $this->load->view('form_add_master_kriteria',null,true);
		$this->load->view('main',$data);
	}
	
	public function add_process()
	{
		$data = array(
						'nama_kriteria' => $this->input->post('kriteria')
					);
		if($this->cek_validasi())
		{
			$this->kriteria_model->add($data);
			redirect('master_kriteria');
		}
		else
		{
			$this->add();
			//redirect('jabatan/add');
		}
	}
	
	public function edit($kriteria_id)
	{
		$data['kriteria_id'] = $kriteria_id;
		$data['kriteria'] = $this->kriteria_model->get_kriteria_by_id($kriteria_id)->row()->NAMA_KRITERIA;
		$data['content'] = $this->load->view('form_edit_master_kriteria',$data,true);
		$this->load->view('main',$data);
	}
	
	public function edit_proses($kriteria_id)
	{
		$data = array(
					'nama_kriteria' => $this->input->post('kriteria')
				);
		if($this->cek_validasi())
		{
			$this->kriteria_model->update($kriteria_id, $data);
			//redirect('master_kriteria');
			echo '<script type="text/javascript">
			$(".error_box").remove();
			</script>
			<div class="valid_box">saved</div>';
		}
		else
		{
			$data['kriteria'] = $this->kriteria_model->get_kriteria_by_id($kriteria_id)->row()->NAMA_KRITERIA;
			//$this->load->view('form_edit_master_kriteria',$data,true);
			//$this->load->view('main',$data);
			echo validation_errors().'<script type="text/javascript">
			$("#kriteria").val("'.$data['kriteria'].'");
			$(".valid_box").remove();
			</script>';
		}
	}
	
	public function delete($kriteria_id)
	{
		$this->kriteria_model->delete($kriteria_id);
		redirect('master_kriteria');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
