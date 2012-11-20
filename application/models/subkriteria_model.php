<?php
class Subkriteria_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
	}
	
	function add($data)
	{
		$this->db->insert('subkriteria',$data);
	}
	
	function delete($id)
	{
		$this->db->delete('subkriteria', array('subkriteria_id' => $id)); 
	}
	
	function get_data_flexigrid($kriteria_id)
	{
		$this->db->select('*')->from('subkriteria');
		$this->db->join('kriteria','kriteria.kriteria_id = subkriteria.kriteria_id');
		$this->db->where('subkriteria.kriteria_id', $kriteria_id);
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*')->from('subkriteria');
		$this->db->join('kriteria','kriteria.kriteria_id = subkriteria.kriteria_id');
		$this->db->where('subkriteria.kriteria_id', $kriteria_id);
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
	
	function update($subkriteria_id, $data)
	{
		$this->db->where('SUBKRITERIA_ID',$subkriteria_id)->update('subkriteria', $data);
	}
	
	function get_subkriteria_by_kriteria($kriteria_id)
	{
		$this->db->select('*');
		$this->db->from('subkriteria');
		$this->db->where('KRITERIA_ID',$kriteria_id);
		return $this->db->get();
	}
	
	function get_subkriteria_by_id($subkriteria_id)
	{
		$this->db->select('*');
		$this->db->from('subkriteria');
		$this->db->where('SUBKRITERIA_ID',$subkriteria_id);
		return $this->db->get();
	}
}
