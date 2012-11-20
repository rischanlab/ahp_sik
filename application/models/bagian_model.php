<?php
class Bagian_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
	}
	
	function add($data)
	{
		$this->db->insert('bagian',$data);
	}
	
	function get_data_flexigrid()
	{
		$this->db->select('*')->from('bagian');
			
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*')->from('bagian');
		
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;
	}
	
	function update($bagian_id, $data)
	{
		$this->db->where('BAGIAN_ID',$bagian_id)->update('bagian', $data);
	}
	
	function get_bagian()
	{
		$this->db->select('*');
		$this->db->from('bagian');
		return $this->db->get();
	}
	
	function get_bagian_by_id($bagian_id)
	{
		$this->db->select('*');
		$this->db->from('bagian');
		$this->db->where('BAGIAN_ID',$bagian_id);
		return $this->db->get();
	}
	
	function delete($id)
	{
		$this->db->delete('bagian', array('bagian_id' => $id)); 
	}
}
