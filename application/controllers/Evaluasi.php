<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluasi extends CI_Controller {

	public $user, $ssw;

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->helper('my');
		$this->load->model('Master_model', 'master');
		$this->load->model('Soal_model', 'soal');
		$this->load->model('Evaluasi_model', 'evaluasi');
		$this->form_validation->set_error_delimiters('','');

		$this->user = $this->ion_auth->user()->row();
		$this->ssw 	= $this->evaluasi->getIdSiswa($this->user->username);
	}

    public function akses_pengajar()
    {
        if ( !$this->ion_auth->in_group('pengajar') ){
			show_error('Halaman ini khusus untuk pengajar untuk membuat Test Online, <a href="'.base_url('dashboard').'">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
    }

    public function akses_siswa()
    {
        if ( !$this->ion_auth->in_group('siswa') ){
			show_error('Halaman ini khusus untuk siswa mengikuti evaluasi, <a href="'.base_url('dashboard').'">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
    }

    public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
	}
	
	public function json($id=null)
	{
        $this->akses_pengajar();

		$this->output_json($this->evaluasi->getDataUjian($id), false);
	}

    public function master()
	{
        $this->akses_pengajar();
        $user = $this->ion_auth->user()->row();
        $data = [
			'user' => $user,
			'judul'	=> 'Ujian',
			'subjudul'=> 'Data Ujian',
			'pengajar' => $this->evaluasi->getIdPengajar($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('evaluasi/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function add()
	{
		$this->akses_pengajar();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'judul'		=> 'Ujian',
			'subjudul'	=> 'Tambah Ujian',
			'diklat'	=> $this->soal->getDiklatPengajar($user->username),
			'pengajar'		=> $this->evaluasi->getIdPengajar($user->username),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('evaluasi/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function edit($id)
	{
		$this->akses_pengajar();
		
		$user = $this->ion_auth->user()->row();

        $data = [
			'user' 		=> $user,
			'judul'		=> 'Ujian',
			'subjudul'	=> 'Edit Ujian',
			'diklat'	=> $this->soal->getDiklatPengajar($user->username),
			'pengajar'		=> $this->evaluasi->getIdPengajar($user->username),
			'evaluasi'		=> $this->evaluasi->getUjianById($id),
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('evaluasi/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function convert_tgl($tgl)
	{
		$this->akses_pengajar();
		return date('Y-m-d H:i:s', strtotime($tgl));
	}

	public function validasi()
	{
		$this->akses_pengajar();
		
		$user 	= $this->ion_auth->user()->row();
		$pengajar 	= $this->evaluasi->getIdPengajar($user->username);
		$jml 	= $this->evaluasi->getJumlahSoal($pengajar->id_pengajar)->jml_soal;
		$jml_a 	= $jml + 1; // Jika tidak mengerti, silahkan baca user_guide codeigniter tentang form_validation pada bagian less_than

		$this->form_validation->set_rules('nama_evaluasi', 'Nama Ujian', 'required|alpha_numeric_spaces|max_length[50]');
		$this->form_validation->set_rules('jumlah_soal', 'Jumlah Soal', "required|integer|less_than[{$jml_a}]|greater_than[0]", ['less_than' => "Soal tidak cukup, anda hanya punya {$jml} soal"]);
		$this->form_validation->set_rules('tgl_mulai', 'Tanggal Mulai', 'required');
		$this->form_validation->set_rules('tgl_selesai', 'Tanggal Selesai', 'required');
		$this->form_validation->set_rules('waktu', 'Waktu', 'required|integer|max_length[4]|greater_than[0]');
		$this->form_validation->set_rules('jenis', 'Acak Soal', 'required|in_list[acak,urut]');
	}

	public function save()
	{	
		$kd_evaluasi = $this->input->post('kd_evaluasi');
		$materi_id	= $this->input->post('materi_id');
		$kritik_saran = $this->input->post('kritik_saran');
		$inspirasi = $this->input->post('inspirasi');
		$perbedaan = $this->input->post('perbedaan');
		//$total = $this->evaluasi->countPenilaian($materi_id);

		$penilaian = $this->input->post('penilaian');
		$detail = $this->input->post('detail');
		$total = count($penilaian);

		for ($x=0; $x<$total; $x++){
			$data = array(
				'evaluasi_kd'    => $kd_evaluasi,
				'penilaian_id'   => $penilaian[$x],
				'nilai'			 => $detail[$x],
				);          
			$this->master->create('detail_evaluasi', $data);
		}

		$input1 = [
			'kd_evaluasi' => $kd_evaluasi,
			'materi_id'	=> $materi_id,
			'siswa_id'	=> $this->ssw->id_siswa,
			'kritik_saran' => $kritik_saran,
			'inspirasi'	=> $inspirasi,
			'perbedaan' => $perbedaan,
		];

		$action = $this->master->create('evaluasi', $input1);

		//if ($action) {
		//	$this->output_json(['status' => true]);
		//} else {
		//	$this->output_json(['status' => false]);
		//}
		redirect('evaluasi/list');
	}

	public function delete()
	{
		$this->akses_pengajar();
		$chk = $this->input->post('checked', true);
        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('m_evaluasi', $chk, 'id_evaluasi')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}

	public function refresh_token($id)
	{
		$this->load->helper('string');
		$data['token'] = strtoupper(random_string('alpha', 5));
		$refresh = $this->master->update('m_evaluasi', $data, 'id_evaluasi', $id);
		$data['status'] = $refresh ? TRUE : FALSE;
		$this->output_json($data);
	}

	/**
	 * BAGIAN BARU
	 */

	public function isi($id)
	{
		$this->akses_siswa();
		
		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Evaluasi',
			'subjudul'	=> 'Entry Evaluasi',
			'kd_eval'	=>  $this->evaluasi->get_kdeval(),
			'id_materi'	=> $id,
			'ssw' 		=>  $this->evaluasi->getIdSiswa($user->username),
			'materi'	=>	$this->evaluasi->getMateriEval($id),
			'penilaian'	=>	$this->evaluasi->getPenilaianEval($id),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('evaluasi/isi');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function simpan()
	{
		$employee   = $this->input->post('employee');
		$location   = $this->input->post('location');
		$detail     = $this->input->post('detail');
		$note       = $this->input->post('note');
		$total      = count($employee);
		$filtered_detail = array_filter($detail);
		if (empty($filtered_detail) === true) {
			$errors['detail'] = 'please select one';
		}

		if (!empty($errors)){
			$info['success'] = false;
			$info['errors']  = $errors;
		}

		else {
			for ($x=0; $x<$total; $x++){
				$data = array(
					'sn'    => $employee[$x],
					'lab'   => $location[$x],
					'stat'  => (isset($detail[$x]) && !empty($detail[$x])) ? implode(",",$detail[$x]) : '',
					'note'  => $note[$x]
					);          
				$this->m_human_capital->insert_attend($data);
			}

			$info['success'] = true;
		}
	}

	public function list_json()
	{
		$this->akses_siswa();
		
		$list = $this->evaluasi->getListEvaluasi($this->ssw->id_siswa, $this->ssw->diklat_id);
		$this->output_json($list, false);
	}
	
	public function list()
	{
		$this->akses_siswa();

		$user = $this->ion_auth->user()->row();
		
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Evaluasi',
			'subjudul'	=> 'List Evaluasi',
			'ssw' 		=> $this->evaluasi->getIdSiswa($user->username),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('evaluasi/list');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	

	public function index()
	{
		$this->akses_siswa();
		$key = $this->input->get('key', true);
		$id  = $this->encryption->decrypt(rawurldecode($key));
		
		$evaluasi 		= $this->evaluasi->getUjianById($id);
		$soal 		= $this->evaluasi->getSoal($id);
		
		$ssw		= $this->ssw;
		$h_evaluasi 	= $this->evaluasi->HslUjian($id, $ssw->id_siswa);
	
		$cek_sudah_ikut = $h_evaluasi->num_rows();

		if ($cek_sudah_ikut < 1) {
			$soal_urut_ok 	= array();
			$i = 0;
			foreach ($soal as $s) {
				$soal_per = new stdClass();
				$soal_per->id_soal 		= $s->id_soal;
				$soal_per->soal 		= $s->soal;
				$soal_per->file 		= $s->file;
				$soal_per->tipe_file 	= $s->tipe_file;
				$soal_per->opsi_a 		= $s->opsi_a;
				$soal_per->opsi_b 		= $s->opsi_b;
				$soal_per->opsi_c 		= $s->opsi_c;
				$soal_per->opsi_d 		= $s->opsi_d;
				$soal_per->opsi_e 		= $s->opsi_e;
				$soal_per->jawaban 		= $s->jawaban;
				$soal_urut_ok[$i] 		= $soal_per;
				$i++;
			}
			$soal_urut_ok 	= $soal_urut_ok;
			$list_id_soal	= "";
			$list_jw_soal 	= "";
			if (!empty($soal)) {
				foreach ($soal as $d) {
					$list_id_soal .= $d->id_soal.",";
					$list_jw_soal .= $d->id_soal."::N,";
				}
			}
			$list_id_soal 	= substr($list_id_soal, 0, -1);
			$list_jw_soal 	= substr($list_jw_soal, 0, -1);
			$waktu_selesai 	= date('Y-m-d H:i:s', strtotime("+{$evaluasi->waktu} minute"));
			$time_mulai		= date('Y-m-d H:i:s');

			$input = [
				'evaluasi_id' 		=> $id,
				'siswa_id'	=> $ssw->id_siswa,
				'list_soal'		=> $list_id_soal,
				'list_jawaban' 	=> $list_jw_soal,
				'jml_benar'		=> 0,
				'nilai'			=> 0,
				'nilai_bobot'	=> 0,
				'tgl_mulai'		=> $time_mulai,
				'tgl_selesai'	=> $waktu_selesai,
				'status'		=> 'Y'
			];
			$this->master->create('h_evaluasi', $input);

			// Setelah insert wajib refresh dulu
			redirect('evaluasi/?key='.urlencode($key), 'location', 301);
		}
		
		$q_soal = $h_evaluasi->row();
		
		$urut_soal 		= explode(",", $q_soal->list_jawaban);
		$soal_urut_ok	= array();
		for ($i = 0; $i < sizeof($urut_soal); $i++) {
			$pc_urut_soal	= explode(":",$urut_soal[$i]);
			$pc_urut_soal1 	= empty($pc_urut_soal[1]) ? "''" : "'{$pc_urut_soal[1]}'";
			$ambil_soal 	= $this->evaluasi->ambilSoal($pc_urut_soal1, $pc_urut_soal[0]);
			$soal_urut_ok[] = $ambil_soal; 
		}

		$detail_tes = $q_soal;
		$soal_urut_ok = $soal_urut_ok;

		$pc_list_jawaban = explode(",", $detail_tes->list_jawaban);
		$arr_jawab = array();
		foreach ($pc_list_jawaban as $v) {
			$pc_v 	= explode(":", $v);
			$idx 	= $pc_v[0];
			$val 	= $pc_v[1];
			$rg 	= $pc_v[2];

			$arr_jawab[$idx] = array("j"=>$val,"r"=>$rg);
		}

		$arr_opsi = array("a","b","c","d","e");
		$html = '';
		$no = 1;
		if (!empty($soal_urut_ok)) {
			foreach ($soal_urut_ok as $s) {
				$path = 'uploads/bank_soal/';
				$vrg = $arr_jawab[$s->id_soal]["r"] == "" ? "N" : $arr_jawab[$s->id_soal]["r"];
				$html .= '<input type="hidden" name="id_soal_'.$no.'" value="'.$s->id_soal.'">';
				$html .= '<input type="hidden" name="rg_'.$no.'" id="rg_'.$no.'" value="'.$vrg.'">';
				$html .= '<div class="step" id="widget_'.$no.'">';

				$html .= '<div class="text-center"><div class="w-25">'.tampil_media($path.$s->file).'</div></div>'.$s->soal.'<div class="funkyradio">';
				for ($j = 0; $j < $this->config->item('jml_opsi'); $j++) {
					$opsi 			= "opsi_".$arr_opsi[$j];
					$file 			= "file_".$arr_opsi[$j];
					$checked 		= $arr_jawab[$s->id_soal]["j"] == strtoupper($arr_opsi[$j]) ? "checked" : "";
					$pilihan_opsi 	= !empty($s->$opsi) ? $s->$opsi : "";
					$tampil_media_opsi = (is_file(base_url().$path.$s->$file) || $s->$file != "") ? tampil_media($path.$s->$file) : "";
					$html .= '<div class="funkyradio-success" onclick="return simpan_sementara();">
						<input type="radio" id="opsi_'.strtolower($arr_opsi[$j]).'_'.$s->id_soal.'" name="opsi_'.$no.'" value="'.strtoupper($arr_opsi[$j]).'" '.$checked.'> <label for="opsi_'.strtolower($arr_opsi[$j]).'_'.$s->id_soal.'"><div class="huruf_opsi">'.$arr_opsi[$j].'</div> <p>'.$pilihan_opsi.'</p><div class="w-25">'.$tampil_media_opsi.'</div></label></div>';
				}
				$html .= '</div></div>';
				$no++;
			}
		}

		// Enkripsi Id Tes
		$id_tes = $this->encryption->encrypt($detail_tes->id);

		$data = [
			'user' 		=> $this->user,
			'ssw'		=> $this->ssw,
			'judul'		=> 'Ujian',
			'subjudul'	=> 'Lembar Ujian',
			'soal'		=> $detail_tes,
			'no' 		=> $no,
			'html' 		=> $html,
			'id_tes'	=> $id_tes
		];
		$this->load->view('_templates/topnav/_header.php', $data);
		$this->load->view('evaluasi/sheet');
		$this->load->view('_templates/topnav/_footer.php');
	}

	public function simpan_satu()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		$input 	= $this->input->post(null, true);
		$list_jawaban 	= "";
		for ($i = 1; $i < $input['jml_soal']; $i++) {
			$_tjawab 	= "opsi_".$i;
			$_tidsoal 	= "id_soal_".$i;
			$_ragu 		= "rg_".$i;
			$jawaban_ 	= empty($input[$_tjawab]) ? "" : $input[$_tjawab];
			$list_jawaban	.= "".$input[$_tidsoal].":".$jawaban_.":".$input[$_ragu].",";
		}
		$list_jawaban	= substr($list_jawaban, 0, -1);
		$d_simpan = [
			'list_jawaban' => $list_jawaban
		];
		
		// Simpan jawaban
		$this->master->update('h_evaluasi', $d_simpan, 'id', $id_tes);
		$this->output_json(['status'=>true]);
	}

	public function simpan_akhir()
	{
		// Decrypt Id
		$id_tes = $this->input->post('id', true);
		$id_tes = $this->encryption->decrypt($id_tes);
		
		// Get Jawaban
		$list_jawaban = $this->evaluasi->getJawaban($id_tes);

		// Pecah Jawaban
		$pc_jawaban = explode(",", $list_jawaban);
		
		$jumlah_benar 	= 0;
		$jumlah_salah 	= 0;
		$jumlah_ragu  	= 0;
		$nilai_bobot 	= 0;
		$total_bobot	= 0;
		$jumlah_soal	= sizeof($pc_jawaban);

		foreach ($pc_jawaban as $jwb) {
			$pc_dt 		= explode(":", $jwb);
			$id_soal 	= $pc_dt[0];
			$jawaban 	= $pc_dt[1];
			$ragu 		= $pc_dt[2];

			$cek_jwb 	= $this->soal->getSoalById($id_soal);
			$total_bobot = $total_bobot + $cek_jwb->bobot;

			$jawaban == $cek_jwb->jawaban ? $jumlah_benar++ : $jumlah_salah++;
		}

		$nilai = ($jumlah_benar / $jumlah_soal)  * 100;
		$nilai_bobot = ($total_bobot / $jumlah_soal)  * 100;

		$d_update = [
			'jml_benar'		=> $jumlah_benar,
			'nilai'			=> number_format(floor($nilai), 0),
			'nilai_bobot'	=> number_format(floor($nilai_bobot), 0),
			'status'		=> 'N'
		];

		$this->master->update('h_evaluasi', $d_update, 'id', $id_tes);
		$this->output_json(['status'=>TRUE, 'data'=>$d_update, 'id'=>$id_tes]);
	}
}