<?php
class ProyekController extends CI_Controller{
	public function index(){
		$data['judul']='Proyek';
		$this->load->view('templates/header', $data);
		$this->load->view('proyek');
		$this->load->view('templates/footer');
	}

}
