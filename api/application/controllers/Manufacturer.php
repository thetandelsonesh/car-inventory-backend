<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Manufacturer extends REST_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('ManufacturerModel');        
    }

    public function index_options() {
        $this->response([], REST_Controller::HTTP_OK);
    }

    public function index_get() {
        $list = $this->ManufacturerModel->getList();
        $this->response($list, REST_Controller::HTTP_OK);
    }

    public function index_post() {
        $data = $this->input->post();

        if($this->ManufacturerModel->getByName($data['name'])){
            $this->response(['msg' => 'Name already existing!'], REST_Controller::HTTP_PRECONDITION_FAILED);
            return;
        }

        $status = $this->ManufacturerModel->add($data['name']);
        if($status){
            $this->response([], REST_Controller::HTTP_OK);
        }else{
            $this->response(['msg' => 'Something went wrong! Please try again'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
