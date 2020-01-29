<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->model('Dashboard_model', 'dashboard');
		$this->user = $this->ion_auth->user()->row();
	}

	public function admin_box()
	{
		$box = [
			[
				'box' 		=> 'light-blue',
				'total' 	=> $this->dashboard->total('diklat'),
				'title'		=> 'Diklat',
				'icon'		=> 'graduation-cap'
			],
			[
				'box' 		=> 'yellow-active',
				'total' 	=> $this->dashboard->total('pengajar'),
				'title'		=> 'Pengajar',
				'icon'		=> 'user-secret'
			],
			[
				'box' 		=> 'red',
				'total' 	=> $this->dashboard->total('siswa'),
				'title'		=> 'Siswa',
				'icon'		=> 'user'
			],
		];
		$info_box = json_decode(json_encode($box), FALSE);
		return $info_box;
	}

	public function index()
	{
		$user = $this->user;
		$data = [
			'user' 		=> $user,
			'judul'		=> 'Dashboard',
			'subjudul'	=> 'Data Aplikasi',
		];

		if ( $this->ion_auth->is_admin() ) {
			$data['info_box'] = $this->admin_box();
		} elseif ( $this->ion_auth->in_group('pengajar') ) {
			$diklat = ['diklat' => 'pengajar.diklat_id=diklat.id_diklat'];
			$data['pengajar'] = $this->dashboard->get_where('pengajar', 'nip', $user->username, $diklat)->row();

			//$kelas = ['kelas' => 'kelas_pengajar.kelas_id=kelas.id_kelas'];
			//$data['kelas'] = $this->dashboard->get_where('kelas_pengajar', 'pengajar_id' , $data['pengajar']->id_pengajar, $kelas, ['nama_kelas'=>'ASC'])->result();
		}else{
			$join = [
				'diklat b' 	=> 'a.diklat_id = b.id_diklat'
			];
			$data['siswa'] = $this->dashboard->get_where('siswa a', 'nis', $user->username, $join)->row();
		}

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('dashboard');
		$this->load->view('_templates/dashboard/_footer.php');
	}
}