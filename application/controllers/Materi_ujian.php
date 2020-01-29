<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Materi_ujian extends CI_Controller {
        public $user, $ssw;

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
                $this->load->helper('my');
                $this->load->helper('url');
                $this->load->model('Master_model', 'master');
                $this->load->model('m_file');
		$this->load->model('Soal_model', 'soal');
		$this->load->model('Ujian_model', 'ujian');
		$this->form_validation->set_error_delimiters('','');

		$this->user = $this->ion_auth->user()->row();
		$this->ssw 	= $this->ujian->getIdSiswa($this->user->username);
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
                            show_error('Halaman ini khusus untuk siswa mengikuti ujian, <a href="'.base_url('dashboard').'">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
                    }
        }

        public function index(){
              //load session library to use flashdata
                $user = $this->ion_auth->user()->row();
                $this->load->library('session');
                        //fetch all files i the database
                $data = [
                        'user' 		=> $user,
                        'judul'		=> 'Materi Ujian',
                        'subjudul'	=> 'Materi Ujian',
                        'materi_ujian'	=> $this->m_file->getFile($user->username),
                ];
                $this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('materi_ujian/list');
		$this->load->view('_templates/dashboard/_footer.php');
//                $this->load->view('pengajar/materi_ujian', $data);
          }

          public function siswa(){
                //load session library to use flashdata
                  $user = $this->ion_auth->user()->row();
                  $this->load->library('session');
                          //fetch all files i the database
                  $data = [
                          'user' 		=> $user,
                          'judul'		=> 'Materi Ujian',
                          'subjudul'	=> 'Materi Ujian',
                          'materi_ujian'	=> $this->m_file->getFileSiswa($user->username),
                  ];
                  $this->load->view('_templates/dashboard/_header.php', $data);
                  $this->load->view('materi_ujian/listsiswa');
                  $this->load->view('_templates/dashboard/_footer.php');
  //                $this->load->view('pengajar/materi_ujian', $data);
            }
        

          public function input(){
                $user = $this->ion_auth->user()->row();
                $data = [
                        'user' 		=> $user,
                        'judul'		=> 'Tambah Materi Ujian',
                        'subjudul'	=> 'Tambah Materi Ujian',
                        'materi_ujian'	=> $this->m_file->getFile($user->username),
                        'diklat_id'     => $this->m_file->getDiklatID($user->username),
                ];
                
                $this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('materi_ujian/form');
		$this->load->view('_templates/dashboard/_footer.php');
                //$this->load->view('pengajar/materi_ujian_form');
               
            }
        
        public function insert(){
                $this->akses_pengajar();
                $user = $this->ion_auth->user()->row();
                //load session library to use flashdata
               // $this->load->library('session');

                //Check if file is not empty
                if(!empty($_FILES['upload']['name'])){
                $config['upload_path'] = 'upload/';
                //restrict uploads to this mime types
                $config['allowed_types'] = 'jpeg|jpg|xlsx|docx|pptx|pdf';
                $config['file_name'] = $_FILES['upload']['name'];

                //Load upload library and initialize configuration
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('upload')){
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];

                                //set file data to insert to database
                $file['diklat_id'] = $this->input->post('diklat_id');
                $file['filename'] = $filename;

                $query = $this->m_file->insertfile($file);

                if($query){
                        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                        Data Berhasil Di Tambahkan!
                       </div>');
                        redirect('materi_ujian');
                }
                else{
                        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                        File uploaded but not inserted to database
                       </div>');
                        redirect('materi_ujian');
                }

                }else{
                        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                        Cannot Upload File
                       </div>');
                        redirect('materi_ujian');
                }
        
                }
        }

        public function input_aksi()
        { 

                $data = array(
                'nama_diklat' => $this->input->post('nama_diklat'),
                'filename' => $this->input->post('filename'),
                
            );
            $this->m_file->insertfile($data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
            Data Berhasil Di Tambahkan!
        </div>');
            redirect('materi_ujian');

        }
        
        public function delete($id)
        {                
                $this->akses_pengajar();
                $this->master->delete('tbl_file', $id, 'id');
                redirect('materi_ujian');
        }

        public function download($id){

            $this->load->helper('download');
            $fileinfo = $this->m_file->download($id);
            
            
            $file = 'upload/'.$fileinfo['filename'];
            //var_dump(base_url($file));
            force_download($file, NULL);
        }

}
?>