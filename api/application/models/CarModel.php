<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CarModel extends CI_Model {

    public function add($data) {
        $datetime = new DateTime('NOW');
        $data = [
            'name' => $data['name'],
            'manufacturer_id' => $data['manufacturer_id'],
            'color' => $data['color'],
            'year' => $data['year'],
            'regno' => $data['regno'],
            'note' => $data['note'],
            'created_at' => $datetime->format(DateTime::ATOM)
        ];
        $this->db->insert('cars', $data);

        $insert_id = $this->db->insert_id();
	    return $insert_id;
    }


    public function markSold($id) {
        $datetime = new DateTime('NOW');
        $data = [
            'sold' => true,
            'sold_at' => $datetime->format(DateTime::ATOM)
        ];
        return $this->db->where('id', $id)->update('cars', $data);
    }

    public function getTotalCount() {
        $this->db->from('cars c');
        $this->db->where('c.sold', 0);
        return $this->db->count_all_results();
    }

    public function getList($limit = 10, $offset = 0) {
        $this->db->select('c.*, m.name as manufacturer');
        $this->db->join('manufacturers m','c.manufacturer_id = m.id','left');
        $this->db->where('c.sold', 0);
        $this->db->order_by('c.created_at', 'DESC');
        $query = $this->db->get('cars c', $limit, $offset);
        return $query->result_array();
    }
}