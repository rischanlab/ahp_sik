<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ahp_subkriteria extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('flexigrid');	
		$this->load->helper('flexigrid');
		$this->load->model('subkriteria_model');
		$this->load->model('ahp_subkriteria_model');
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
		$kriteria = $this->kriteria_model->get_kriteria();
		$data_kriteria[0] = '-- pilih kriteria --';
		foreach($kriteria->result() as $row)
		{
			$data_kriteria[$row->KRITERIA_ID] = $row->NAMA_KRITERIA;
		}
		$data['kriteria'] =  $data_kriteria;
		$data['content'] = $this->load->view('form_perhitungan_subkriteria',$data,true);
		$this->load->view('main',$data);
	}
	
	public function process0()
	{
		if($this->cek_validasi())
		{
			$kriteria_id = $this->input->post('kriteria');
			$nama_kriteria = $this->kriteria_model->get_kriteria_by_id($kriteria_id)->row()->NAMA_KRITERIA;
			$data['jumlah_subkriteria'] = $this->ahp_subkriteria_model->get_jumlah_subkriteria($kriteria_id);
			$data['result_subkriteria'] = $this->ahp_subkriteria_model->get_subkriteria($kriteria_id)->result();
			$data['bobot'] = array(
								0 => 'bobot',
								1 => '1',
								2 => '2',
								3 => '3',
								4 => '4',
								5 => '5'
								);
								//echo $data['jumlah_subkriteria']; 
			$data['nama_kriteria'] = $nama_kriteria; 
			$data['kriteria_id'] = $kriteria_id; 
			$data['content'] = $this->load->view('tabel_perbandingan_subkriteria',$data,true);
			$this->load->view('main',$data);
		}
		else
		{
			$this->index();
		}
	}
	
	function cek_validasi()
	{
		for($i=0;$i<$this->input->post('max_bobot')-1;$i++)
		{
			$this->form_validation->set_rules('bobot'.$i, 'Bobot '.$i, 'callback_cek_dropdown');
		}
		$this->form_validation->set_rules('kriteria', 'Kriteria', 'callback_cek_dropdown');
		
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
	
	function process1($kriteria_id)
	{
		if($this->cek_validasi())
		{
			//mendapatkan jumlah kriteria pada tabel subkriteria
			$jumlah_subkriteria = $this->ahp_subkriteria_model->get_jumlah_subkriteria($this->input->post('kriteria_id'));
			$arrray1 = array();
			$k = 0;
			$l = 0;
			//membuat matriks perbandingan berpasangan 
			for($i=0;$i<$jumlah_subkriteria;$i++)
			{
				for($j=$k;$j<$jumlah_subkriteria;$j++)
				{
					if($i==$j)
					{
						$array1[$i][$j] = 1;
					}
					else
					{
						$array1[$i][$j] = $this->input->post('bobot'.$l);
						$array1[$j][$i] = round(1/$array1[$i][$j],2);
						$l++;				
					}
				}
				$k++;
			}
			//menampilkan semua elemen array
			for($p=0;$p<$jumlah_subkriteria;$p++)
			{
				for($q=0;$q<$jumlah_subkriteria;$q++)
				{
					//echo '['.$p.']['.$q.'] = '.$array1[$p][$q];
					//echo '<br />';
				}
			}
			//mencari jumlah setiap baris matriks perbandingan berpasangan
			$jumlah_per_baris = array();
			$jumlah_per_cell = 0;
			for($y=0;$y<$jumlah_subkriteria;$y++)
			{
				for($z=0;$z<$jumlah_subkriteria;$z++)
				{
					$jumlah_per_cell = $jumlah_per_cell + $array1[$y][$z];
				}
				$jumlah_per_baris[$y] = $jumlah_per_cell;
				$jumlah_per_cell = 0;
				//echo 'jumlah baris ['.$y.'] = '.$jumlah_per_baris[$y];
				//echo '<br />';
			}
			//matriks nilai kriteria
			$array2 = array();
			for($m=0;$m<$jumlah_subkriteria;$m++)
			{
				for($n=0;$n<$jumlah_subkriteria;$n++)
				{				
					$array2[$m][$n] = round($array1[$m][$n]/$jumlah_per_baris[$m],2);
					//echo '['.$m.']['.$n.'] = '.$array2[$m][$n];
					//echo '<br />';
				}
			}
			//print jumlah per baris matriks nilai subkriteria
			$jumlah_per_baris2 = array();
			$jumlah_per_cell2 = 0;
			$prioritas = array();
			for($o=0;$o<$jumlah_subkriteria;$o++)
			{
				for($p=0;$p<$jumlah_subkriteria;$p++)
				{				
					$jumlah_per_cell2 = $jumlah_per_cell2 + $array2[$p][$o];
				}
				$jumlah_per_baris2[$o] = $jumlah_per_cell2;
				$prioritas[$o] = round($jumlah_per_cell2/$jumlah_subkriteria,2);
				//menyimpan nilai prioritas ke database tabel kriteria
				//$data = array('PRIORITAS_SUBKRITERIA' => $prioritas[$o]);
				//$this->subkriteria_model->update($this->input->post($o), $data);
				
				$jumlah_per_cell2 = 0;
				//echo 'jumlah baris 2 ['.$o.'] = '.$jumlah_per_baris2[$o];
				//echo '<br />';
				//echo 'prioritas ['.$o.'] = '.$prioritas[$o];
				//echo '<br />';
			}
			//matriks penjumlahan setiap baris
			$array3 = array();
			for($r=0;$r<$jumlah_subkriteria;$r++)
			{
				for($s=0;$s<$jumlah_subkriteria;$s++)
				{				
					$array3[$s][$r] = round($array1[$s][$r]*$prioritas[$r],2);
					//echo '['.$r.']['.$s.'] = '.$array3[$r][$s];
					//echo '<br />';
				}
			}
			//print matriks penjumlahan setiap baris
			$jumlah_per_baris3 = array();
			$hasil = array();
			$jumlah_per_cell3 = 0;
			$jumlah = 0;
			for($t=0;$t<$jumlah_subkriteria;$t++)
			{
				for($u=0;$u<$jumlah_subkriteria;$u++)
				{	
					$jumlah_per_cell3 = $jumlah_per_cell3 + $array3[$u][$t];			
					//echo '['.$t.']['.$u.'] = '.$array3[$t][$u];
					//echo '<br />';
				}
				$jumlah_per_baris3[$t] = $jumlah_per_cell3;
				$hasil[$t] = $jumlah_per_baris3[$t] + $prioritas[$t];
				$jumlah = $jumlah + $hasil[$t];
				$jumlah_per_cell3 = 0;
				//echo 'jumlah baris 3 ['.$t.'] = '.$jumlah_per_baris3[$t];
				//echo '<br />';
				//echo 'hasil ['.$t.'] => '.$jumlah_per_baris3[$t].'+'.$prioritas[$t].' = '.$hasil[$t];
				//echo '<br />';
			}
			$alpha_max = $jumlah/$jumlah_subkriteria;
			$consistency_index = ($alpha_max - $jumlah_subkriteria)/$jumlah_subkriteria;
			$consistency_ratio = $consistency_index/1.12;
			//echo 'CR = '.$consistency_ratio;
			//menentukan subprioritas subkriteria
			$prioritas_max = max($prioritas);
			$subprioritas = array();
			for($v=0;$v<$jumlah_subkriteria;$v++)
			{
				$subprioritas[$v] = round($prioritas[$v]/$prioritas_max,2);
				//echo 'subprioritas ['.$v.'] => '.$prioritas[$v].' / '.$prioritas_max.' = '.$subprioritas[$v];
				//echo '<br />';
				$data = array('PRIORITAS_SUBKRITERIA' => $subprioritas[$v]);
				$this->subkriteria_model->update($this->input->post($v), $data);
			}
			if($consistency_ratio <= 0.1)
				$keterangan = 'rasio konsistensi dari perhitungan dapat diterima.';
			else
				$keterangan = 'rasio konsistensi dari perhitungan tidak dapat diterima.';
			$data['array1'] = $array1;
			$data['jumlah_subkriteria'] = $jumlah_subkriteria;
			$data['result_subkriteria'] = $this->ahp_subkriteria_model->get_subkriteria($kriteria_id)->result();
			$data['array2'] = $array2;
			$data['array3'] = $array3;
			$data['jumlah_per_baris'] = $jumlah_per_baris;
			$data['jumlah_per_baris2'] = $jumlah_per_baris2;
			$data['jumlah_per_baris3'] = $jumlah_per_baris3;
			$data['prioritas'] = $prioritas;
			$data['subprioritas'] = $subprioritas;
			$data['keterangan'] = $keterangan;
			$data['alpha_max'] = $alpha_max;
			$data['consistency_index'] = $consistency_index;
			$data['consistency_ratio'] = $consistency_ratio;
			$data['kriteria_id'] = $kriteria_id;
			$data['content'] = $this->load->view('tampilan_hasil_subkriteria',$data,true);
			$this->load->view('main',$data);
			//redirect('master_subkriteria/grid/'.$kriteria_id);
		}
		else
		{
			$this->index();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
