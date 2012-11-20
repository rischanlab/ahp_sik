<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->CI = get_instance();
		}
		
	function get_daftar_role(){
		$this->db->select('*');
		$this->db->from('role_pengguna');
		$result = $this->db->get();
		return $result;
	}
	
	function grid_user(){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('role_pengguna', 'user.KODEROLE = role_pengguna.KODEROLE');
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('role_pengguna', 'user.KODEROLE = role_pengguna.KODEROLE');
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;		
		}
		
	function grid_user_by_id($iduser){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('role_pengguna', 'user.KODEROLE = role_pengguna.KODEROLE');
		$this->db->where('user.USERID', $iduser);
		$this->CI->flexigrid->build_query();		
		$return['records'] = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('role_pengguna', 'user.KODEROLE = role_pengguna.KODEROLE');
		$this->db->where('user.USERID', $iduser);
		$this->CI->flexigrid->build_query(FALSE);
		$return['record_count'] = $this->db->count_all_results();
		return $return;		
		}
		
	function add($user){
		$this->db->insert('user', $user);
		}
			
	function update($userid, $user){
		$this->db->where('user.USERID', $userid);
		$this->db->update('user', $user);
		}
		
	function delete($userid){
		$this->db->where('user.USERID', $userid);
		$this->db->delete('user');
		}
		
	function get_user($userid){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->join('role_pengguna', 'user.KODEROLE = role_pengguna.KODEROLE');
		$this->db->where('user.USERID', $userid);
		$result = $this->db->get();
		return $result;
		}
		
	function cek_username($username){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('USERNAME', $username);
		$result = $this->db->get();
		return $result;
	}
	
	function login($user, $pass)
	{
	    $this->db->select('*');
		$this->db->from('user');
		$this->db->where('USERNAME', $user);
		$this->db->where('PASSWORD', $pass);
		$resilt = $this->db->get();
		return $resilt;
	}
	
	function ubah_username($userid, $username){
		$this->db->where('user.USERID',$userid);
		$this->db->update('user.USERNAME', $username);
	}
	
	function cek_password($userid, $pass_lama){
		$this->db->select('*');
		$this->db->from('user');
		$this->db->where('PASSWORD', $pass_lama);
		$this->db->where('USERID', $userid);
		$result = $this->db->get();
		return $result;
	}
	
	function ubah_pass($userid, $pass){
		$this->db->where('user.USERID', $userid);
		$this->db->update('user.PASSWORD', $pass);
	}
	
}
