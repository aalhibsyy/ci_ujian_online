<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('', '');
	}

	public function output_json($data, $encode = true)
	{
		if ($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Siswa',
			'subjudul' => 'Data Siswa'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/siswa/data');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function data()
	{
		$this->output_json($this->master->getDataSiswa(), false);
	}

	public function add()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Siswa',
			'subjudul' => 'Tambah Data Siswa',
			'diklat'	=> $this->master->getAllDiklat()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/siswa/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$ssw = $this->master->getSiswaById($id);
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Siswa',
			'subjudul'	=> 'Edit Data Siswa',
			'siswa' => $ssw,
			'diklat'	=> $this->master->getAllDiklat(),
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('master/siswa/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function validasi_siswa($method)
	{
		$id_siswa 	= $this->input->post('id_siswa', true);
		$nis 			= $this->input->post('nis', true);
		$email 			= $this->input->post('email', true);
		if ($method == 'add') {
			$u_nis = '|is_unique[siswa.nis]';
			$u_email = '|is_unique[siswa.email]';
		} else {
			$dbdata 	= $this->master->getSiswaById($id_siswa);
			$u_nis		= $dbdata->nis === $nis ? "" : "|is_unique[siswa.nis]";
			$u_email	= $dbdata->email === $email ? "" : "|is_unique[siswa.email]";
		}
		$this->form_validation->set_rules('nis', 'NIS', 'required' . $u_nis);
		$this->form_validation->set_rules('nama', 'Nama', 'required|trim|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $u_email);
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('diklat', 'diklat', 'required');

		$this->form_validation->set_message('required', 'Kolom {field} wajib diisi');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->validasi_siswa($method);

		if ($this->form_validation->run() == FALSE) {
			$data = [
				'status'	=> false,
				'errors'	=> [
					'nis' => form_error('nis'),
					'nama' => form_error('nama'),
					'email' => form_error('email'),
					'jenis_kelamin' => form_error('jenis_kelamin'),
					'diklat' => form_error('diklat'),
				]
			];
			$this->output_json($data);
		} else {
			$input = [
				'nis' 			=> $this->input->post('nis', true),
				'email' 		=> $this->input->post('email', true),
				'nama' 			=> $this->input->post('nama', true),
				'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
				'diklat_id' 		=> $this->input->post('diklat', true),
			];
			if ($method === 'add') {
				$action = $this->master->create('siswa', $input);
			} else if ($method === 'edit') {
				$id = $this->input->post('id_siswa', true);
				$action = $this->master->update('siswa', $input, 'id_siswa', $id);
			}

			if ($action) {
				$this->output_json(['status' => true]);
			} else {
				$this->output_json(['status' => false]);
			}
		}
	}

	public function delete()
	{
		$chk = $this->input->post('checked', true);
		if (!$chk) {
			$this->output_json(['status' => false]);
		} else {
			if ($this->master->delete('siswa', $chk, 'id_siswa')) {
				$this->output_json(['status' => true, 'total' => count($chk)]);
			}
		}
	}

	public function create_user()
	{
		$id = $this->input->get('id', true);
		$data = $this->master->getSiswaById($id);
		$nama = explode(' ', $data->nama);
		$first_name = $nama[0];
		$last_name = end($nama);

		$username = $data->nis;
		$password = $data->nis;
		$email = $data->email;
		$additional_data = [
			'first_name'	=> $first_name,
			'last_name'		=> $last_name
		];
		$group = array('3'); // Sets user to pengajar.

		if ($this->ion_auth->username_check($username)) {
			$data = [
				'status' => false,
				'msg'	 => 'Username tidak tersedia (sudah digunakan).'
			];
		} else if ($this->ion_auth->email_check($email)) {
			$data = [
				'status' => false,
				'msg'	 => 'Email tidak tersedia (sudah digunakan).'
			];
		} else {
			$this->ion_auth->register($username, $password, $email, $additional_data, $group);
			$data = [
				'status'	=> true,
				'msg'	 => 'User berhasil dibuat. NIP digunakan sebagai password pada saat login.'
			];
		}
		$this->output_json($data);
	}

	public function import($import_data = null)
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Siswa',
			'subjudul' => 'Import Data Siswa',
			'kelas' => $this->master->getAllKelas()
		];
		if ($import_data != null) $data['import'] = $import_data;

		$this->load->view('_templates/dashboard/_header', $data);
		$this->load->view('master/siswa/import');
		$this->load->view('_templates/dashboard/_footer');
	}
	public function preview()
	{
		$config['upload_path']		= './uploads/import/';
		$config['allowed_types']	= 'xls|xlsx|csv';
		$config['max_size']			= 2048;
		$config['encrypt_name']		= true;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload_file')) {
			$error = $this->upload->display_errors();
			echo $error;
			die;
		} else {
			$file = $this->upload->data('full_path');
			$ext = $this->upload->data('file_ext');

			switch ($ext) {
				case '.xlsx':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					break;
				case '.xls':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
					break;
				case '.csv':
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					break;
				default:
					echo "unknown file ext";
					die;
			}

			$spreadsheet = $reader->load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			$data = [];
			for ($i = 1; $i < count($sheetData); $i++) {
				$data[] = [
					'nis' => $sheetData[$i][0],
					'nama' => $sheetData[$i][1],
					'email' => $sheetData[$i][2],
					'jenis_kelamin' => $sheetData[$i][3],
					'kelas_id' => $sheetData[$i][4]
				];
			}

			unlink($file);

			$this->import($data);
		}
	}

	public function do_import()
	{
		$input = json_decode($this->input->post('data', true));
		$data = [];
		foreach ($input as $d) {
			$data[] = [
				'nis' => $d->nis,
				'nama' => $d->nama,
				'email' => $d->email,
				'jenis_kelamin' => $d->jenis_kelamin,
				'kelas_id' => $d->kelas_id
			];
		}

		$save = $this->master->create('siswa', $data, true);
		if ($save) {
			redirect('siswa');
		} else {
			redirect('siswa/import');
		}
	}
}
