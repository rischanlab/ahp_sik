<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_subkriteria extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('subkriteria_model');
		$this->load->model('kriteria_model');
		$this->load->model('ahp_subkriteria_model');
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
	
	/*
	public function index($kriteria_id)
	{
		$this->grid($kriteria_id);
	}*/
	
	public function grid($kriteria_id)
	{
		$nama_kriteria = $this->kriteria_model->get_kriteria_by_id($kriteria_id)->row()->NAMA_KRITERIA;
		//$kode_role = $this->session->userdata('kode_role');
		$colModel['no'] = array('No',20,TRUE,'center',0);
		$colModel['nama_subkriteria'] = array('Nama Subkriteria',150,TRUE,'center',1);
		$colModel['nama_kriteria'] = array('Nama Kriteria',150,TRUE,'center',1);
		$colModel['subprioritas_subkriteria'] = array('Subprioritas',100,TRUE,'center',1);
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
							'title' => 'Master Subkriteria ( Kriteria '.$nama_kriteria.' )',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$jumlah_subkriteria = $this->ahp_subkriteria_model->get_jumlah_subkriteria($kriteria_id);
		if($jumlah_subkriteria < 5)
		{
			$buttons[] = array('Tambah','add','spt_js');
			$buttons[] = array('separator');
		}
		$buttons[] = array('Kembali ke master kriteria','kembali','spt_js');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/master_subkriteria/grid_data_subkriteria/".$kriteria_id;
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/master_subkriteria/add/".$kriteria_id."';    
			}else if (com=='Kembali ke master kriteria'){
				location.href= '".base_url()."index.php/master_kriteria';    
			}
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_subkriteria($kriteria_id) 
	{
		$valid_fields = array('SUBKRITERIA_ID','NAMA_SUBKRITERIA','NAMA_KRITERIA','PRIORITAS_SUBKRITERIA');
		$this->flexigrid->validate_post('SUBKRITERIA_ID','asc',$valid_fields);
		$records = $this->subkriteria_model->get_data_flexigrid($kriteria_id);
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->SUBKRITERIA_ID,
										$no,
										$row->NAMA_SUBKRITERIA,
										$row->NAMA_KRITERIA,
										$row->PRIORITAS_SUBKRITERIA,
								'<a href='.base_url().'index.php/master_subkriteria/edit/'.$row->SUBKRITERIA_ID.'/'.$kriteria_id.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
								'<a href='.base_url().'index.php/master_subkriteria/delete/'.$row->SUBKRITERIA_ID.'/'.$kriteria_id.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('subkriteria', 'Nama Subkriteria', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	public function add($kriteria_id)
	{
		$kriteria = $this->kriteria_model->get_kriteria();
		foreach($kriteria->result() as $row)
		{
			$data_kriteria[$row->KRITERIA_ID] = $row->NAMA_KRITERIA;
		}
		$nama_kriteria = $this->kriteria_model->get_kriteria_by_id($kriteria_id)->row()->NAMA_KRITERIA;
		$data['nama_kriteria'] = $nama_kriteria;
		$data['kriteria_id'] =  $kriteria_id;
		$data['kriteria'] =  $data_kriteria;
		$data['content'] = $this->load->view('form_add_master_subkriteria',$data,true);
		$this->load->view('main',$data);
	}
	
	public function add_process($kriteria_id)
	{
		$data = array(
						'nama_subkriteria' => $this->input->post('subkriteria'),
						'kriteria_id' => $this->input->post('kriteria')
					);
		if($this->cek_validasi())
		{
			$this->subkriteria_model->add($data);
			redirect('master_subkriteria/grid/'.$kriteria_id);
		}
		else
		{
			$this->add($kriteria_id);
		}
	}
	
	public function edit($subkriteria_id, $kriteria_id)
	{
		$kriteria = $this->kriteria_model->get_kriteria();
		foreach($kriteria->result() as $row)
		{
			$data_kriteria[$row->KRITERIA_ID] = $row->NAMA_KRITERIA;
		}
		$data['kriteria_id'] =  $kriteria_id;
		$data['subkriteria_id'] =  $subkriteria_id;
		$data['kriteria'] =  $data_kriteria;
		$data['kriteria_dipilih'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->KRITERIA_ID;;
		$data['subkriteria'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->NAMA_SUBKRITERIA;
		//$data['bobot'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->BOBOT;
		$data['content'] = $this->load->view('form_edit_master_subkriteria',$data,true);
		$this->load->view('main',$data);
	}
	
	public function edit_proses($subkriteria_id, $kriteria_id)
	{
		$data = array(
					'nama_subkriteria' => $this->input->post('subkriteria'),
					'kriteria_id' => $this->input->post('kriteria')
				);
		if($this->cek_validasi())
		{
			$this->subkriteria_model->update($subkriteria_id, $data);
			//redirect('master_subkriteria');
			echo '<script type="text/javascript">$(".error_box").remove();</script><div class="valid_box">saved</div>';
		}
		else
		{
			$data['kriteria_dipilih'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->KRITERIA_ID;;
			$data['subkriteria'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->NAMA_SUBKRITERIA;
			//$data['bobot'] = $this->subkriteria_model->get_subkriteria_by_id($subkriteria_id)->row()->BOBOT;
			
			echo validation_errors().'<script type="text/javascript">
				$("#kriteria").val("'.$data['kriteria_dipilih'].'");
				$("#subkriteria").val("'.$data['subkriteria'].'");
				
				$(".valid_box").remove();
			</script>';
		}
	}
	
	public function delete($subkriteria_id, $kriteria_id)
	{
		$this->subkriteria_model->delete($subkriteria_id);
		redirect('master_subkriteria/grid/'.$kriteria_id);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
