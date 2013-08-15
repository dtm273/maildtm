<?php

class App_Version_Model extends CI_Model {

    // table name
    private $tbl = 'app_version';

    function __construct() {
        parent::__construct();
    }

    // get object by id
    function get_all() {
        $this->db->order_by('id', 'asc');
        $this->db->where('is_active', '1');
        return $this->db->get($this->tbl);
    }

    // get new
    function get_new_version() {
        $this->db->select('version');
        $this->db->order_by('version', 'desc');
        $this->db->limit(1);
        $this->db->where('is_active', '1');
        return $this->db->get($this->tbl);
    }
    // get object with paging
    function get_paged_list($limit = 10, $offset = 0, $keyword = '') {
        $this->db->order_by('created', 'asc');
        $this->db->where('is_active', '1');
        if ($keyword)
            $this->db->where('version LIKE \'%' . $keyword . '%\'');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    // get object by id
    function get_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('is_active', '1');
        return $this->db->get($this->tbl);
    }
    
    // get number of object in database
    function count_all($keyword = '') {
        $this->db->select('count(*) as result');
        $this->db->where('is_active', '1');
        if ($keyword){
            $this->db->where('version LIKE \'%' . $keyword . '%\'');
        }
        return intval($this->db->get($this->tbl)->row()->result);
    }

    // add new object
    function save($tbl) {
        $tbl['created'] = date('Y-m-d H:i:s');
        $tbl['is_active'] = '1';
        $this->db->insert($this->tbl, $tbl);
        return $this->db->insert_id();
    }

    // update object by id
    function update($id, $tbl) {
        $tbl['modified'] = date('Y-m-d H:i:s');
        $tbl['is_active'] = '1';
        $this->db->where('id', $id);
        $this->db->update($this->tbl, $tbl);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    // delete object by id
    function delete($id) {
        $tbl['modified'] = date('Y-m-d H:i:s');
        $tbl['is_active'] = '0'; //delete change is_active
        $this->db->where('id', $id);
        $this->db->update($this->tbl, $tbl);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    // delete object by id
    function count_in($ids) {
        $this->db->select('count(*) as result');
        $this->db->where('is_active', '1');
        $this->db->where('id IN (' . $ids . ')');
        return $this->db->get($this->tbl)->row()->result;
    }

    // delete object by id
    function delete_in($ids) {
        $tbl['modified'] = date('Y-m-d H:i:s');
        $tbl['is_active'] = '0'; //delete change is_active
        $this->db->where('id IN (' . $ids . ')');
        $this->db->update($this->tbl, $tbl);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    function get_fields() {
        return $this->db->list_fields($this->tbl);
    }
}