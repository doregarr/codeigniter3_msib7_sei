<?php
class ProyekLokasiController extends CI_Controller{
	public function index(){
		$data['judul']='Lokasi';
		$this->load->view('templates/header', $data);
		$this->load->view('proyeklokasi');
		$this->load->view('templates/footer');
	}

}
