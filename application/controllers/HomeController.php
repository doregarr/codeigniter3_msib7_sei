<?php
class HomeController extends CI_Controller{
	public function index(){
		$data['judul']='PT.Surya Energi Indotama';
		$this->load->view('templates/header', $data);
		$this->load->view('home');
		$this->load->view('templates/footer');
	}

}
