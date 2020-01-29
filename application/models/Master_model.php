<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function create($table, $data, $batch = false)
    {
        if($batch === false){
            $insert = $this->db->insert($table, $data);
        }else{
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if($batch === false){
            $insert = $this->db->update($table, $data, array($pk => $id));
        }else{
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data Kelas
     */

    public function getDataKelas()
    {
        $this->datatables->select('id_kelas, nama_kelas, id_jurusan, nama_jurusan');
        $this->datatables->from('kelas');
        $this->datatables->join('jurusan', 'jurusan_id=id_jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_kelas, nama_kelas, id_jurusan, nama_jurusan');        
        return $this->datatables->generate();
    }

    public function getKelasById($id)
    {
        $this->db->where_in('id_kelas', $id);
        $this->db->order_by('nama_kelas');
        $query = $this->db->get('kelas')->result();
        return $query;
    }

    /**
     * Data Jurusan
     */

    public function getDataJurusan()
    {
        $this->datatables->select('id_jurusan, nama_jurusan');
        $this->datatables->from('jurusan');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_jurusan, nama_jurusan');
        return $this->datatables->generate();
    }

    public function getJurusanById($id)
    {
        $this->db->where_in('id_jurusan', $id);
        $this->db->order_by('nama_jurusan');
        $query = $this->db->get('jurusan')->result();
        return $query;
    }



    /**
     * Data Diklat
     */

    public function getDataDiklat()
    {
        $this->datatables->select('id_diklat, nama_diklat');
        $this->datatables->from('diklat');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_diklat, nama_diklat');
        return $this->datatables->generate();
    }
    public function getDataMateri()
    {
        $this->datatables->select('a.id_materi, a.nama_materi, a.diklat_id,b.nama_diklat');
        $this->datatables->from('materi a');
        $this->datatables->join('diklat b', 'b.id_diklat=a.diklat_id');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_materi, nama_materi', 'materi_id');
        return $this->datatables->generate();
    }
    public function getDataPenilaian()
    {
        $this->datatables->select('a.id_penilaian, a.nama_penilaian, a.materi_id,b.nama_materi');
        $this->datatables->from('penilaian a');
        $this->datatables->join('materi b', 'b.id_materi=a.materi_id');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_penilaian, nama_penilaian', 'penilaian_id');
        return $this->datatables->generate();
    }

    public function getDiklatById($id)
    {
        $this->db->where_in('id_diklat', $id);
        $this->db->order_by('nama_diklat');
        $query = $this->db->get('diklat')->result();
        return $query;
    }


    /**
     * Data Siswa
     */

    public function getDataSiswa()
    {
        $this->datatables->select('a.id_siswa, a.nama, a.nis, a.email, b.nama_diklat');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nis) AS ada');
        $this->datatables->from('siswa a');
        $this->datatables->join('diklat b', 'a.diklat_id=b.id_diklat');
        return $this->datatables->generate();
    }

    public function getSiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('diklat', 'diklat_id=id_diklat');
        $this->db->where(['id_siswa' => $id]);
        return $this->db->get()->row();
    }

    public function getJurusan()
    {
        $this->db->select('id_jurusan, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_jurusan', 'ASC');
        $this->db->group_by('id_jurusan');
        $query = $this->db->get();
        return $query->result();
    }

    public function getDiklat()
    {
        $this->db->select('id_diklat, nama_diklat');
        $this->db->from('diklat');
        $query = $this->db->get();
        return $query->result();
    }
    public function getMateri()
    {
        $this->db->select('id_materi, nama_materi', 'diklat_id');
        $this->db->from('mater');
        $this->db->join('diklat', 'diklat_id=id_diklat');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllJurusan($id = null)
    {
        if($id === null){
            $this->db->order_by('nama_jurusan', 'ASC');
            return $this->db->get('jurusan')->result();    
        }else{
            $this->db->select('jurusan_id');
            $this->db->from('jurusan_diklat');
            $this->db->where('diklat_id', $id);
            $jurusan = $this->db->get()->result();
            $id_jurusan = [];
            foreach ($jurusan as $j) {
                $id_jurusan[] = $j->jurusan_id;
            }
            if($id_jurusan === []){
                $id_jurusan = null;
            }
            
            $this->db->select('*');
            $this->db->from('jurusan');
            $this->db->where_not_in('id_jurusan', $id_jurusan);
            $diklat = $this->db->get()->result();
            return $diklat;
        }
    }

    public function getKelasByJurusan($id)
    {
        $query = $this->db->get_where('kelas', array('jurusan_id'=>$id));
        return $query->result();
    }

    /**
     * Data Dosen
     */

    public function getDataDosen()
    {
        $this->datatables->select('a.id_pengajar,a.nip, a.nama_pengajar, a.email, a.diklat_id, b.nama_diklat, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('pengajar a');
        $this->datatables->join('diklat b', 'a.diklat_id=b.id_diklat');
        return $this->datatables->generate();
    }

    public function getDataPengajar()
    {
        $this->datatables->select('a.id_pengajar,a.nip, a.nama_pengajar, a.email, a.diklat_id, b.nama_diklat, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('pengajar a');
        $this->datatables->join('diklat b', 'a.diklat_id=b.id_diklat');
        return $this->datatables->generate();
    }

    public function getDosenById($id)
    {
        $query = $this->db->get_where('pengajar', array('id_pengajar'=>$id));
        return $query->row();
    }
    public function getPengajarById($id)
    {
        $query = $this->db->get_where('pengajar', array('id_pengajar'=>$id));
        return $query->row();
    }
    public function getMateriById($id)
    {
        $query = $this->db->get_where('materi', array('id_materi'=>$id));
        return $query->row();
    }
    public function getPenilaianById($id)
    {
        $query = $this->db->get_where('penilaian', array('id_penilaian'=>$id));
        return $query->row();
    }

    /**
     * Data Matkul
     */

    public function getDataMatkul()
    {
        $this->datatables->select('id_diklat, nama_diklat');
        $this->datatables->from('diklat');
        return $this->datatables->generate();
    }

    public function getAllMatkul()
    {
        return $this->db->get('diklat')->result();
    }

    public function getAllDiklat()
    {
        return $this->db->get('diklat')->result();
    }
    public function getAllMateri()
    {
        return $this->db->get('materi')->result();
    }

    public function getMatkulById($id, $single = false)
    {
        if($single === false){
            $this->db->where_in('id_diklat', $id);
            $this->db->order_by('nama_diklat');
            $query = $this->db->get('diklat')->result();
        }else{
            $query = $this->db->get_where('diklat', array('id_diklat'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data Kelas Dosen
     */

    public function getKelasDosen()
    {
        $this->datatables->select('kelas_pengajar.id, pengajar.id_pengajar, pengajar.nip, pengajar.nama_pengajar, GROUP_CONCAT(kelas.nama_kelas) as kelas');
        $this->datatables->from('kelas_pengajar');
        $this->datatables->join('kelas', 'kelas_id=id_kelas');
        $this->datatables->join('pengajar', 'pengajar_id=id_pengajar');
        $this->datatables->group_by('pengajar.nama_pengajar');
        return $this->datatables->generate();
    }

    public function getAllDosen($id = null)
    {
        $this->db->select('pengajar_id');
        $this->db->from('kelas_pengajar');
        if($id !== null){
            $this->db->where_not_in('pengajar_id', [$id]);
        }
        $pengajar = $this->db->get()->result();
        $id_pengajar = [];
        foreach ($pengajar as $d) {
            $id_pengajar[] = $d->pengajar_id;
        }
        if($id_pengajar === []){
            $id_pengajar = null;
        }

        $this->db->select('id_pengajar, nip, nama_pengajar');
        $this->db->from('pengajar');
        $this->db->where_not_in('id_pengajar', $id_pengajar);
        return $this->db->get()->result();
    }

    public function getAllPengajar($id = null)
    {
        $this->db->select('id_pengajar, nip, nama_pengajar');
        $this->db->from('pengajar');
        $this->db->join('diklat','pengajar.diklat_id=diklat.id_diklat');
        return $this->db->get()->result();
    }

    
    public function getAllKelas()
    {
        $this->db->select('id_kelas, nama_kelas, nama_jurusan');
        $this->db->from('kelas');
        $this->db->join('jurusan', 'jurusan_id=id_jurusan');
        $this->db->order_by('nama_kelas');
        return $this->db->get()->result();
    }
    
    public function getKelasByDosen($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_pengajar');
        $this->db->join('kelas', 'kelas_pengajar.kelas_id=kelas.id_kelas');
        $this->db->where('pengajar_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Jurusan Matkul
     */

    public function getJurusanMatkul()
    {
        $this->datatables->select('jurusan_diklat.id, diklat.id_diklat, diklat.nama_diklat, jurusan.id_jurusan, GROUP_CONCAT(jurusan.nama_jurusan) as nama_jurusan');
        $this->datatables->from('jurusan_diklat');
        $this->datatables->join('diklat', 'diklat_id=id_diklat');
        $this->datatables->join('jurusan', 'jurusan_id=id_jurusan');
        $this->datatables->group_by('diklat.nama_diklat');
        return $this->datatables->generate();
    }

    public function getMatkul($id = null)
    {
        $this->db->select('diklat_id');
        $this->db->from('jurusan_diklat');
        if($id !== null){
            $this->db->where_not_in('diklat_id', [$id]);
        }
        $diklat = $this->db->get()->result();
        $id_diklat = [];
        foreach ($diklat as $d) {
            $id_diklat[] = $d->diklat_id;
        }
        if($id_diklat === []){
            $id_diklat = null;
        }

        $this->db->select('id_diklat, nama_diklat');
        $this->db->from('diklat');
        $this->db->where_not_in('id_diklat', $id_diklat);
        return $this->db->get()->result();
    }

    public function getJurusanByIdMatkul($id)
    {
        $this->db->select('jurusan.id_jurusan');
        $this->db->from('jurusan_diklat');
        $this->db->join('jurusan', 'jurusan_diklat.jurusan_id=jurusan.id_jurusan');
        $this->db->where('diklat_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}