<?php
class LokasiController extends CI_Controller{
	public function index(){
		$data['judul']='Lokasi';
		$this->load->view('templates/header', $data);
		$this->load->view('lokasi');
		$this->load->view('templates/footer');
	}

}
