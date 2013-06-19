<?php
class Kriteria_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
	}
	
	function add($data)
	{
		$this->db->insert('kriteria',$data);
	}
	
	function get_data_flexigrid()
	{
		$this->db->select('*')->from('kriteria');
			
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*')->from('kriteria');
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;
	}
	
	function get_pegawai_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('pegawai');
		$this->db->where('ID',$id);
		return $this->db->get();
	}
	
	function update($kriteria_id, $data)
	{
		$this->db->where('KRITERIA_ID',$kriteria_id)->update('kriteria', $data);
	}
	
	function get_kriteria()
	{
		$this->db->select('*');
		$this->db->from('kriteria');
		return $this->db->get();
	}
	
	function get_kriteria_by_id($kriteria_id)
	{
		$this->db->select('*');
		$this->db->from('kriteria');
		$this->db->where('KRITERIA_ID',$kriteria_id);
		return $this->db->get();
	}
	
	function get_max_kriteria_id()
	{
		$this->db->select_max('kriteria_id');
		$query = $this->db->get('kriteria');
		return $query;
	}
	
	function get_absensi_ketidakhadiran($date_start, $date_end)
	{
		return $this->db->query('SELECT * FROM absensi JOIN pegawai ON pegawai.ID = absensi.ID WHERE KODE_ABSENSI > 1 AND TANGGAL_ABSENSI BETWEEN "'.$date_start.'" AND "'.$date_end.'" ORDER BY TANGGAL_ABSENSI');
	}
	
	function delete($id)
	{ 
		$this->db->delete('subkriteria', array('kriteria_id' => $id)); 
		$this->db->delete('kriteria', array('kriteria_id' => $id));
	}
}
