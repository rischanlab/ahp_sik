<?php
class Capeg_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
	}
	
	function add($data)
	{
		$this->db->insert('calon_pegawai',$data);
		$this->db->select_max('CAPEG_ID');
		$result = $this->db->get('calon_pegawai')->row();
		return $result;
	}
	
	function add_pertanyaan_perpeg($data){
		$this->db->insert('nilai_pegawai_per_pertanyaan', $data);
	}
	
	function get_data_flexigrid()
	{
		$this->db->select('*')->from('calon_pegawai');
		$this->db->join('bagian','bagian.bagian_id = calon_pegawai.bagian_id');
		$this->db->order_by('nilai_pegawai', "desc");
			
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*')->from('calon_pegawai');
		$this->db->join('bagian','bagian.bagian_id = calon_pegawai.bagian_id');
		$this->db->order_by('nilai_pegawai', "desc");
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;
	}
	
	function update($capeg_id, $data)
	{
		$this->db->where('CAPEG_ID',$capeg_id)->update('calon_pegawai', $data);
	}
	
	function get_capeg_by_id($capeg_id)
	{
		$this->db->select('*');
		$this->db->from('calon_pegawai');
		$this->db->join('bagian','bagian.bagian_id = calon_pegawai.bagian_id');
		$this->db->where('CAPEG_ID',$capeg_id);
		$this->db->order_by('nilai_pegawai', "desc");
		return $this->db->get();
	}
	
	function delete($id)
	{
		$this->db->delete('calon_pegawai', array('CAPEG_ID' => $id)); 
	}
	
	function delete_nilai_kriteria_pegawai($capeg_id)
	{
		$this->db->delete('penilaian', array('CAPEG_ID' => $capeg_id)); 
	}
		
	function get_penilaian($capeg_id, $kriteria_id){
		$this->db->select('*');
		$this->db->from('penilaian');
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('KRITERIA_ID', $kriteria_id);
		return $this->db->get();
	}
	
	function insert_nilai($data){
		$this->db->insert('penilaian', $data);
	}
	
	function get_sum_nilai($capeg_id){
		$this->db->select_sum('TOTAL_NILAI');
		$this->db->from('penilaian');
		$this->db->where('CAPEG_ID', $capeg_id);
		return $this->db->get();
	}
	
	
	function get_prioritas_subkriteria($kriteria, $subkriteria){
		$this->db->select('*');
		$this->db->from('subkriteria');
		$this->db->join('kriteria', 'subkriteria.KRITERIA_ID = kriteria.KRITERIA_ID'); 		
		$this->db->where('NAMA_KRITERIA', $kriteria);
		$this->db->where('NAMA_SUBKRITERIA', $subkriteria);
		return $this->db->get();
	}
	
	function get_prioritas_kriteria($kriteria){
		$this->db->select('*');
		$this->db->from('kriteria');
		$this->db->where('NAMA_KRITERIA', $kriteria);
		return $this->db->get();
	}
	
	function get_bagian_id($bagian){
		$this->db->select('*');
		$this->db->from('bagian');
		$this->db->where('NAMA_BAGIAN', $bagian);
		return $this->db->get();
	}
	
	function get_sum_perbagian($capeg_id, $bagian){
		$this->db->select_sum('TOTAL_NILAI');
		$this->db->from('penilaian');
		$this->db->join('bagian', 'penilaian.BAGIAN_ID = bagian.BAGIAN_ID');
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_BAGIAN', $bagian);
		return $this->db->get();
	}
	
	function add_penilain($data){
		$this->db->insert('penilaian', $data);
	}
	
	function delete_penilaian($capeg_id){
		$this->db->delete('penilaian', array('CAPEG_ID' => $capeg_id));
	}
	
	/*function get_pertanyaan_tes_psikologi($capeg_id, $bagian){
		$this->db->select_sum('NILAI');
		$this->db->from('nilai_pegawai_per_pertanyaan');
		$this->db->join('pertanyaan', 'nilai_pegawai_per_pertanyaan.PERTANYAAN_ID = pertanyaan.PERTANYAAN_ID');
		$this->db->join('kriteria', 'pertanyaan.KRITERIA_ID = kriteria.KRITERIA_ID'); 		
		$this->db->join('bagian', 'pertanyaan.BAGIAN_ID = bagian.BAGIAN_ID');
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_KRITERIA', 'tes psikologi');
		$this->db->where('NAMA_BAGIAN', $bagian);
		return $this->db->get();
	}
	
	function get_tes_akademik($capeg_id, $bagian){
		$this->db->select('*');
		$this->db->from('nilai_pegawai_per_pertanyaan');
		$this->db->join('pertanyaan', 'nilai_pegawai_per_pertanyaan.PERTANYAAN_ID = pertanyaan.PERTANYAAN_ID');
		$this->db->join('kriteria', 'pertanyaan.KRITERIA_ID = kriteria.KRITERIA_ID'); 
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_KRITERIA', 'tes akademik');
		return $this->db->get();
	}
	
	function get_tes_kepribadian($capeg_id, $bagian){
		$this->db->select('*');
		$this->db->from('nilai_pegawai_per_pertanyaan');
		$this->db->join('pertanyaan', 'nilai_pegawai_per_pertanyaan.PERTANYAAN_ID = pertanyaan.PERTANYAAN_ID');
		$this->db->join('kriteria', 'pertanyaan.KRITERIA_ID = kriteria.KRITERIA_ID'); 
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_KRITERIA', 'tes kepribadian');
		return $this->db->get();
	}
	
	function get_tes_wawancara($capeg_id, $bagian){
		$this->db->select('*');
		$this->db->from('nilai_pegawai_per_pertanyaan');
		$this->db->join('pertanyaan', 'nilai_pegawai_per_pertanyaan.PERTANYAAN_ID = pertanyaan.PERTANYAAN_ID');
		$this->db->join('kriteria', 'pertanyaan.KRITERIA_ID = kriteria.KRITERIA_ID'); 
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_KRITERIA', 'tes wawancara');
		return $this->db->get();
	}
	
	function get_tes_pengetahuan($capeg_id, $bagian){
		$this->db->select('*');
		$this->db->from('nilai_pegawai_per_pertanyaan');
		$this->db->join('pertanyaan', 'nilai_pegawai_per_pertanyaan.PERTANYAAN_ID = pertanyaan.PERTANYAAN_ID');
		$this->db->join('kriteria', 'pertanyaan.KRITERIA_ID = kriteria.KRITERIA_ID'); 
		$this->db->where('CAPEG_ID', $capeg_id);
		$this->db->where('NAMA_KRITERIA', 'tes pengetahuan');
		return $this->db->get();
	}*/
}
