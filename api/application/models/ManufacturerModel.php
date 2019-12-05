<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManufacturerModel extends CI_Model {

    public function add($name) {
        $datetime = new DateTime('NOW');
        $data = [
            'name' => $name,
            'created_at' => $datetime->format(DateTime::ATOM)
        ];
        return $this->db->insert('manufacturers', $data);
    
    }

    public function getByName($name) {
        $row = null;

        $query = $this->db->where('name', $name)->get('manufacturers');
        if($query->num_rows() >= 1){
            $row = $query->row();
        }
        
        return $row;
    }

    public function getList() {
        $query = $this->db->order_by('created_at', 'DESC')->get('manufacturers');
        return $query->result_array();
    }
}