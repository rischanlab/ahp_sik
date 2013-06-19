<?php
class Pertanyaan_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
	}
	
	function add($data)
	{
		$this->db->insert('pertanyaan',$data);
	}
	
	function get_data_flexigrid()
	{
		$this->db->select('*')->from('pertanyaan');
		$this->db->join('kriteria','kriteria.kriteria_id = pertanyaan.kriteria_id');	
		$this->db->join('bagian','bagian.bagian_id = pertanyaan.bagian_id');
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*')->from('pertanyaan');
		$this->db->join('kriteria','kriteria.kriteria_id = pertanyaan.kriteria_id');	
		$this->db->join('bagian','bagian.bagian_id = pertanyaan.bagian_id');		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;
	}
	
	function update($pertanyaan_id, $data)
	{
		$this->db->where('PERTANYAAN_ID',$pertanyaan_id)->update('pertanyaan', $data);
	}
	
	function get_pertanyaan()
	{
		$this->db->select('*');
		$this->db->from('pertanyaan');
		return $this->db->get();
	}
	
	function get_pertanyaan_by_id($pertanyaan_id)
	{
		$this->db->select('*');
		$this->db->from('pertanyaan');
		$this->db->where('PERTANYAAN_ID',$pertanyaan_id);
		return $this->db->get();
	}
	
	function delete($id)
	{
		$this->db->delete('pertanyaan', array('pertanyaan_id' => $id)); 
	}
}
