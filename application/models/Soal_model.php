<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Soal_model extends CI_Model {
    
    public function getDataSoal($id, $pengajar)
    {
        $this->datatables->select('a.id_soal, a.soal, FROM_UNIXTIME(a.created_on) as created_on, FROM_UNIXTIME(a.updated_on) as updated_on, b.nama_diklat, c.nama_pengajar');
        $this->datatables->from('tb_soal a');
        $this->datatables->join('diklat b', 'b.id_diklat=a.diklat_id');
        $this->datatables->join('pengajar c', 'c.id_pengajar=a.pengajar_id');
        if ($id!==null && $pengajar===null) {
            $this->datatables->where('a.diklat_id', $id);            
        }else if($id!==null && $pengajar!==null){
            $this->datatables->where('a.pengajar_id', $pengajar);
        }
        return $this->datatables->generate();
    }

    public function getSoalById($id)
    {
        return $this->db->get_where('tb_soal', ['id_soal' => $id])->row();
    }

 
    public function getDiklatPengajar($nip)
    {
        $this->db->select('diklat_id, nama_diklat, id_pengajar, nama_pengajar');
        $this->db->join('diklat', 'diklat_id=id_diklat');
        $this->db->from('pengajar')->where('nip', $nip);
        return $this->db->get()->row();
    }

   
    public function getAllPengajar()
    {
        $this->db->select('*');
        $this->db->from('pengajar a');
        $this->db->join('diklat b', 'a.diklat_id=b.id_diklat');
        return $this->db->get()->result();
    }
}