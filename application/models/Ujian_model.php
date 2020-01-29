<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
    
    public function getDataUjian($id)
    {
        $this->datatables->select('a.id_ujian, a.token, a.nama_ujian, b.nama_diklat, a.jumlah_soal, CONCAT(a.tgl_mulai, " <br/> (", a.waktu, " Menit)") as waktu, a.jenis');
        $this->datatables->from('m_ujian a');
        $this->datatables->join('diklat b', 'a.diklat_id = b.id_diklat');
        if($id!==null){
            $this->datatables->where('pengajar_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListUjian($id, $diklat)
    {
        
        $this->datatables->select("a.id_ujian, e.nama_pengajar, a.nama_ujian, b.nama_diklat, a.jumlah_soal, CONCAT(a.tgl_mulai, ' <br/> (', a.waktu, ' Menit)') as waktu,  (SELECT COUNT(id) FROM h_ujian h WHERE h.siswa_id = {$id} AND h.ujian_id = a.id_ujian) AS ada");
        
        $this->datatables->from('m_ujian a');
        $this->datatables->join('diklat b', 'a.diklat_id = b.id_diklat');
        $this->datatables->join('pengajar e', 'e.id_pengajar = a.pengajar_id');
        $this->datatables->where('b.id_diklat', $diklat);
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('m_ujian a');
        $this->db->join('pengajar b', 'a.pengajar_id=b.id_pengajar');
        $this->db->join('diklat c', 'a.diklat_id=c.id_diklat');
        $this->db->where('id_ujian', $id);
        return $this->db->get()->row();
    }

    public function getIdPengajar($nip)
    {
        $this->db->select('id_pengajar, nama_pengajar')->from('pengajar')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getJumlahSoal($pengajar)
    {
        $this->db->select('COUNT(id_soal) as jml_soal');
        $this->db->from('tb_soal');
        $this->db->where('pengajar_id', $pengajar);
        return $this->db->get()->row();
    }

    public function getIdSiswa($nis)
    {
        $this->db->select('*');
        $this->db->from('siswa a');
        $this->db->join('diklat b', 'a.diklat_id=b.id_diklat');
        $this->db->where('nis', $nis);
        return $this->db->get()->row();
    }

    public function HslUjian($id, $ssw)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('h_ujian');
        $this->db->where('ujian_id', $id);
        $this->db->where('siswa_id', $ssw);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $ujian = $this->getUjianById($id);
        $order = $ujian->jenis==="acak" ? 'rand()' : 'id_soal';

        $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
        $this->db->from('tb_soal');
        $this->db->where('pengajar_id', $ujian->pengajar_id);
        $this->db->where('diklat_id', $ujian->diklat_id);
        $this->db->order_by($order);
        $this->db->limit($ujian->jumlah_soal);
        return $this->db->get()->result();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('tb_soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('list_jawaban');
        $this->db->from('h_ujian');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_jawaban;
    }

    public function getHasilUjian($nip = null)
    {
        $this->datatables->select('b.id_ujian, b.nama_ujian, b.jumlah_soal, CONCAT(b.waktu, " Menit") as waktu, b.tgl_mulai');
        $this->datatables->select('c.nama_diklat, d.nama_pengajar');
        $this->datatables->from('h_ujian a');
        $this->datatables->join('m_ujian b', 'a.ujian_id = b.id_ujian');
        $this->datatables->join('diklat c', 'b.diklat_id = c.id_diklat');
        $this->datatables->join('pengajar d', 'b.pengajar_id = d.id_pengajar');
        //$this->datatables->group_by('b.id_ujian');
        if($nip !== null){
            $this->datatables->where('d.nip', $nip);
        }
        return $this->datatables->generate();
    }

    public function HslUjianById($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nama, b.nama_diklat, d.jml_benar, d.nilai');
        $this->$db->from('siswa a');
        $this->$db->join('diklat b', 'a.diklat_id=b.id_diklat');
        $this->$db->join('h_ujian d', 'a.id_siswa=d.siswa_id');
        $this->$db->where(['d.ujian_id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('nilai', 'min_nilai');
        $this->db->select_max('nilai', 'max_nilai');
        $this->db->select_avg('FORMAT(FLOOR(nilai),0)', 'avg_nilai');
        $this->db->where('ujian_id', $id);
        return $this->db->get('h_ujian')->row();
    }

}