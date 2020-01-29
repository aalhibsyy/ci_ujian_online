<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_file extends CI_Model
{

    function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getAllFiles($id){
		 $this->db->select('*');
        $this->db->from('tbl_file a');
        $this->db->join('diklat b', 'a.diklat_id=b.id_diklat');
        $this->db->where('a.diklat_id', $id);
        $query = $this->db->get()->result();
        return $query;
	}
	
	public function insertfile($file){
		return $this->db->insert('tbl_file', $file);
	}

	public function download($id){
		$query = $this->db->get_where('tbl_file',array('id'=>$id));
		return $query->row_array();
    }
    
    public function getFile($nip)
    {
        $this->db->select('*');
        $this->db->from('tbl_file a');
        $this->db->join('diklat b', 'b.id_diklat=a.diklat_id');
        $this->db->join('pengajar c', 'c.diklat_id=a.diklat_id');
        $this->db->where('c.nip', $nip);
        return $this->db->get()->result();

    }
    public function getFileSiswa($nis)
    {
        $this->db->select('*');
        $this->db->from('tbl_file a');
        $this->db->join('diklat b', 'b.id_diklat=a.diklat_id');
        $this->db->join('siswa c', 'c.diklat_id=a.diklat_id');
        $this->db->where('c.nis', $nis);
        return $this->db->get()->result();

    }
    public function getDiklatID($nip)
    {
        $this->db->select('diklat_id, nama_diklat, id_pengajar, nama_pengajar');
        $this->db->join('diklat', 'diklat_id=id_diklat');
        $this->db->from('pengajar')->where('nip', $nip);
        return $this->db->get()->row();

    }
	
}
