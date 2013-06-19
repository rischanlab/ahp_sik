<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengelolaan_capeg extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('capeg_model');
		$this->load->model('bagian_model');
		$this->load->model('kriteria_model');
		$this->load->model('subkriteria_model');
		$this->cek_session();
	}
	
	function cek_session()
	{	
		$kode_role = $this->session->userdata('kode_role');
		if($kode_role == '' || $kode_role != 1 && $kode_role !=2)
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
		$colModel['NAMA_CAPEG'] = array('Nama Calon Pegawai',150,TRUE,'center',1);
		$colModel['NAMA_BAGIAN'] = array('Nama Bagian',100,TRUE,'center',1);
		$colModel['NILAI_PEGAWAI'] = array('Nilai Pegawai',90,TRUE,'center',1);
		$colModel['perhitungan nilai'] = array('Perhitungan Nilai',80,FALSE,'center',0);
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
							'title' => 'Pengelolaan Calon Pegawai',
							'showTableToggleBtn' => false
							);
							
		//menambah tombol pada flexigrid top toolbar
		$buttons[] = array('Tambah','add','spt_js');
		$buttons[] = array('separator');
		
				
		// mengambil data dari file controler ajax pada method grid_user		
		$url = site_url()."/pengelolaan_capeg/grid_data_bagian";
		$grid_js = build_grid_js('user',$url,$colModel,'ID','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
		$data['added_js'] = 
		"<script type='text/javascript'>
		function spt_js(com,grid){	
			if (com=='Tambah'){
				location.href= '".base_url()."index.php/pengelolaan_capeg/add';    
			}	
		} </script>";
			
		//$data['added_js'] variabel untuk membungkus javascript yang dipakai pada tombol yang ada di toolbar atas
		$data['content'] = $this->load->view('grid',$data,true);
		$this->load->view('main',$data);
	}
	
	function grid_data_bagian() 
	{
		$valid_fields = array('CAPEG_ID','NAMA_CAPEG');
		$this->flexigrid->validate_post('CAPEG_ID','asc',$valid_fields);
		$records = $this->capeg_model->get_data_flexigrid();
		$this->output->set_header($this->config->item('json_header'));
			
		$no = 0;
		foreach ($records['records']->result() as $row){	
				$no = $no+1;
				$record_items[] = array(
										$row->CAPEG_ID,
										$no,
										$row->NAMA_CAPEG,
										$row->NAMA_BAGIAN,
										$row->NILAI_PEGAWAI,
										'<a href='.base_url().'index.php/pengelolaan_capeg/perhitungan/'.$row->CAPEG_ID.'><img border=\'0\' src=\''.base_url().'images/icon/cal.gif\'></a>',
										'<a href='.base_url().'index.php/pengelolaan_capeg/edit/'.$row->CAPEG_ID.'><img border=\'0\' src=\''.base_url().'images/flexigrid/magnifier.png\'></a>',
										'<a href='.base_url().'index.php/pengelolaan_capeg/delete/'.$row->CAPEG_ID.' onclick="return confirm(\'Are you sure you want to delete?\')"><img border=\'0\' src=\''.base_url().'images/flexigrid/2.png\'></a>'
								);
		}
		
		if(isset($record_items))
			$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
		else
			$this->output->set_output('{"page":"1","total":"0","rows":[]}');
	}
	
	function cek_validasi()
	{	
		$this->form_validation->set_rules('nama_capeg', 'Nama Capeg', 'required');
		$this->form_validation->set_rules('bagian', 'Bagian', 'required|callback_cek_dropdown');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	function cek_dropdown($value){
		if($value === '0'){
			$this->form_validation->set_message('cek_dropdown', 'Kolom %s harus dipilih!!');
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	
	function cek_validasi_kriteria($id)
	{	
		$this->form_validation->set_rules($id, 'Nilai Kriteria', 'required|callback_cek_dropdown');
		
		$this->form_validation->set_error_delimiters('<div class="error_box">', '</div>');
		$this->form_validation->set_message('required', 'Kolom %s harus diisi !!');
		return $this->form_validation->run();
	}
	
	public function add()
	{
		$bagian = $this->bagian_model->get_bagian();
		$data_bagian[0] = '-- pilih bagian --';
		foreach($bagian->result() as $row)
		{
			$data_bagian[$row->BAGIAN_ID] = $row->NAMA_BAGIAN;
		}
		$data['bagian'] =  $data_bagian;
		
		$data['content'] = $this->load->view('form_add_calon_pegawai',$data,true);
		$this->load->view('main',$data);
	}
	
	public function add_process()
	{
		$data = array(
						'nama_capeg' => $this->input->post('nama_capeg'),
						'bagian_id' => $this->input->post('bagian')
					);
		if($this->cek_validasi())
		{
			$capeg = $this->capeg_model->add($data);
			//$capeg_id = $capeg->CAPEG_ID;
			//$pertanyaan = $this->pertanyaan_model->get_pertanyaan();
			/*foreach($pertanyaan->result() as $row){
					$data_pertanyaan = array(
						'PERTANYAAN_ID' => $row->PERTANYAAN_ID,
						'CAPEG_ID' => $capeg_id
					);
					
					$this->capeg_model->add_pertanyaan_perpeg($data_pertanyaan);
					
				}*/
			redirect('pengelolaan_capeg');
		}
		else
		{
			$this->add();
			//redirect('jabatan/add');
		}
	}
	
	public function edit($capeg_id)
	{
		$data = array(
					'nama_capeg' => $this->input->post('nama_capeg'),
					'bagian_id' => $this->input->post('bagian')
				);
		if($this->cek_validasi())
		{
			$this->capeg_model->update($capeg_id, $data);
			redirect('pengelolaan_capeg');
		}
		else
		{
			$bagian = $this->bagian_model->get_bagian();
			$data_bagian[0] = '-- pilih bagian --';
			foreach($bagian->result() as $row)
			{
				$data_bagian[$row->BAGIAN_ID] = $row->NAMA_BAGIAN;
			}
			$data['bagian'] =  $data_bagian;
			
			$data['bagian_dipilih'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->BAGIAN_ID;
			$data['nama_capeg'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_CAPEG;
			$data['content'] = $this->load->view('form_edit_calon_pegawai',$data,true);
			$this->load->view('main',$data);
		}
	}
	
	public function delete($capeg_id)
	{
		$this->capeg_model->delete($capeg_id);
		redirect('pengelolaan_capeg');
	}
	
	public function perhitungan($capeg_id){
		$statushit = $this->input->post('hitung');
		if($statushit == 'yes')
		{
			$kriteria = $this->kriteria_model->get_kriteria();
			$lakukan_hitung = 'yes';
			foreach($kriteria->result() as $row){
				if($this->cek_validasi_kriteria($row->KRITERIA_ID) && $lakukan_hitung == 'yes'){
					$lakukan_hitung = 'yes';
					$this->capeg_model->delete_nilai_kriteria_pegawai($capeg_id);
				} else{
					$lakukan_hitung = 'no';
					redirect('pengelolaan_capeg/perhitungan/'.$capeg_id);
				}
			}
			
			foreach($kriteria->result() as $row){
				if($lakukan_hitung == 'yes'){	
					
					$sub_kriteria_id = $this->input->post($row->KRITERIA_ID);
					$nilai_prioritas_subkriteria = $this->subkriteria_model->get_subkriteria_by_id($sub_kriteria_id)->row()->PRIORITAS_SUBKRITERIA;
					$nilai_prioritas_kriteria = $row->PRIORITAS_KRITERIA;
					$total_nilai = $nilai_prioritas_kriteria*$nilai_prioritas_subkriteria;
					
					$data_nilai = array(
						'KRITERIA_ID' => $row->KRITERIA_ID,
						'SUBKRITERIA_ID' => $sub_kriteria_id,
						'CAPEG_ID' => $capeg_id,
						'TOTAL_NILAI' => $total_nilai
					);
					
					$this->capeg_model->insert_nilai($data_nilai);
					}
					else{
					
						$kriteria = $this->kriteria_model->get_kriteria();
						foreach($kriteria->result() as $row)
						{
							$subkriteria = $this->subkriteria_model->get_subkriteria_by_kriteria($row->KRITERIA_ID);
							$data['subkriteria'][$row->KRITERIA_ID][0] = '-- pilih nilai subkriteria --';
							foreach($subkriteria->result() as $row)
							{
								$data['subkriteria'][$row->KRITERIA_ID][$row->SUBKRITERIA_ID] = $row->NAMA_SUBKRITERIA;
							}					
							
							if($this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->num_rows() > 0){
								$subkriteria_dipilih = $this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->row()->SUBKRITERIA_ID;;
							} else {
								$subkriteria_dipilih = '0';
							}
							
							$data['subkriteria_pilihan'][$row->KRITERIA_ID] = $subkriteria_dipilih;
						}
						
						$data['kriteria'] = $this->kriteria_model->get_kriteria();
											
						$data['nama'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_CAPEG;
						
						$data['bagian_dipilih'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_BAGIAN;
						
						$data['nilai_pegawai'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NILAI_PEGAWAI;
						
						$data['hitung'] = 'yes';
						
						$data['content'] = $this->load->view('form_perhitungan_capeg',$data,true);
						$this->load->view('main',$data);
				}
					
			}
								
					$data_nilai_peg = array(
										'NILAI_PEGAWAI' => $this->capeg_model->get_sum_nilai($capeg_id)->row()->TOTAL_NILAI
									);
					
					$this->capeg_model->update($capeg_id, $data_nilai_peg);
					
					$kriteria = $this->kriteria_model->get_kriteria();
					foreach($kriteria->result() as $row)
					{
						$subkriteria = $this->subkriteria_model->get_subkriteria_by_kriteria($row->KRITERIA_ID);
						$data['subkriteria'][$row->KRITERIA_ID][0] = '-- pilih nilai subkriteria --';
						foreach($subkriteria->result() as $row)
						{
							$data['subkriteria'][$row->KRITERIA_ID][$row->SUBKRITERIA_ID] = $row->NAMA_SUBKRITERIA;
						}
						
						if($this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->num_rows() > 0){
							$subkriteria_dipilih = $this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->row()->SUBKRITERIA_ID;;
						} else {
							$subkriteria_dipilih = '0';
						}
						
						$data['subkriteria_pilihan'][$row->KRITERIA_ID] = $subkriteria_dipilih;
					}
					
					$data['kriteria'] = $this->kriteria_model->get_kriteria();
										
					$data['nama'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_CAPEG;
					
					$data['bagian_dipilih'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_BAGIAN;
					
					$data['nilai_pegawai'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NILAI_PEGAWAI;
					
					$data['hitung'] = 'yes';
					
					$data['content'] = $this->load->view('form_perhitungan_capeg',$data,true);
					$this->load->view('main',$data);		
			
		}
		else
		{
					$kriteria = $this->kriteria_model->get_kriteria();
					foreach($kriteria->result() as $row)
					{
						$subkriteria = $this->subkriteria_model->get_subkriteria_by_kriteria($row->KRITERIA_ID);
						$data['subkriteria'][$row->KRITERIA_ID][0] = '-- pilih nilai subkriteria --';
						foreach($subkriteria->result() as $row)
						{
							$data['subkriteria'][$row->KRITERIA_ID][$row->SUBKRITERIA_ID] = $row->NAMA_SUBKRITERIA;
						}
						
						if($this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->num_rows() > 0){
							$subkriteria_dipilih = $this->capeg_model->get_penilaian($capeg_id, $row->KRITERIA_ID)->row()->SUBKRITERIA_ID;;
						} else {
							$subkriteria_dipilih = '0';
						}
						
						$data['subkriteria_pilihan'][$row->KRITERIA_ID] = $subkriteria_dipilih;
					}
					
					$data['kriteria'] = $this->kriteria_model->get_kriteria();
										
					$data['nama'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_CAPEG;
					
					$data['bagian_dipilih'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NAMA_BAGIAN;
					
					$data['nilai_pegawai'] = $this->capeg_model->get_capeg_by_id($capeg_id)->row()->NILAI_PEGAWAI;
					
					$data['hitung'] = 'yes';
					
					$data['content'] = $this->load->view('form_perhitungan_capeg',$data,true);
					$this->load->view('main',$data);
		}
	} 
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
