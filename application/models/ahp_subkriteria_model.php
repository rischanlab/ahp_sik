<?php
class Ahp_subkriteria_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		//$this->CI = get_instance();
	}
	
	function get_jumlah_subkriteria($kriteria_id)
	{
		$this->db->where('KRITERIA_ID', $kriteria_id);
		$this->db->from('subkriteria');
		return $this->db->count_all_results();
	}
	
	function get_subkriteria($kriteria_id)
	{
		$this->db->select('*');
		$this->db->from('subkriteria');
		$this->db->where('kriteria_id', $kriteria_id);
		return $this->db->get();
	}
}
