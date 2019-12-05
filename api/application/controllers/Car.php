<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/input_validator.php';

class Car extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CarModel');

        $this->rules = [
            'name' => [
                'type'             => 'alphanumeric_space',
                'required'         => true
            ],
            'color' => [
                'type'             => 'alpha_space',
                'required'         => true
            ],
            'regno' => [
                'type'             => 'alphanumeric_space',
                'required'         => true
            ],
            'manufacturer_id' => [
                'type'             => 'numeric',
                'min'             => 1,
                'required'         => true
            ],
            'year' => [
                'type'             => 'pattern',
                'required'         => true,
                'pattern'        => '/^[1-9]{1}\d{3}$/'
            ],
            'note' => [
                'type'            => 'any',
                'required'        => false,
            ]
        ];
    }

    public function index_options()
    {
        $this->response([], REST_Controller::HTTP_OK);
    }

    public function index_get($page)
    {
        $limit = 10;
        $offset = $limit * ($page - 1);
        $list = $this->CarModel->getList($limit, $offset);
        $count = $this->CarModel->getTotalCount();
        $this->response(['list' => $list, 'count' => $count], REST_Controller::HTTP_OK);
    }

    public function index_post()
    {
        $postData =  $this->input->post();

        $required  = array(
            'name' => $this->rules['name'],
            'color' => $this->rules['color'],
            'regno' => $this->rules['regno'],
            'year' => $this->rules['year'],
            'manufacturer_id' => $this->rules['manufacturer_id'],
            'note' => $this->rules['note']
        );
        $validate_result = validate_all($required, $postData);

        if(!$validate_result['code']){
            $this->response(['msg' => $validate_result['error']], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }

        $fields = $validate_result['fields'];
        $data = [
            'name' => $fields['name']['value'],
            'color' => $fields['color']['value'],
            'regno' => $fields['regno']['value'],
            'year' => $fields['year']['value'],
            'manufacturer_id' => $fields['manufacturer_id']['value'],
            'note' => $fields['note']['value']
        ];

        $insert_id = $this->CarModel->add($data);
        if (!$insert_id) {
            $this->response(['msg' => 'Something went wrong!'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }

        $this->uploader('image1', $insert_id, 'a');
        $this->uploader('image2', $insert_id, 'b');

        $this->response(['data' => $insert_id], REST_Controller::HTTP_OK);
    }

    public function index_put()
    { }

    public function index_delete($id)
    {
        $result = $this->CarModel->markSold($id);
        if ($result) {
            $this->response(['data' => $result], REST_Controller::HTTP_OK);
        } else {
            $this->response(['data' => $result], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function uploader($file, $insert_id, $index)
    {
        $file_path = "./uploads/";
        $filename = 'image_' . $insert_id . $index . '.jpg';

        $config['upload_path']   = $file_path;
        $config['allowed_types'] = 'jpg|jpeg';
        $config['overwrite']     = true;
        $config['remove_spaces'] = true;
        $config['file_name']     = $filename;

        $this->load->library('upload');
        $this->upload->initialize($config);
        $this->upload->do_upload($file);
    }
}
