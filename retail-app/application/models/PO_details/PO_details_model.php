<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class PO_details_model extends CI_Model {
 
    var $table = 'po_details';

    var $column_order = array('num','prod_id','prod_name','unit_qty','unit','arrived_qty',null); //set column field database for datatable orderable
    var $column_search = array('num','prod_id','prod_name','unit_qty','unit','arrived_qty',null); //set column field database for datatable searchable

    var $order = array('num' => 'asc'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        $this->db->select('po_details.*, products.name as prod_name');
        $this->db->from($this->table);

        $this->db->join('products', 'products.prod_id = po_details.prod_id');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($po_id)
    {        
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);

        $this->db->where('po_id',$po_id); // if data is part of the object by ID

        $query = $this->db->get();
        return $query->result();
    }

    // check for duplicates in the database table for validation
    function get_duplicates($po_id, $prod_id)
    {      
        $this->db->from($this->table);
        $this->db->where('po_id',$po_id);
        $this->db->where('prod_id',$prod_id);

        $query = $this->db->get();

        return $query;
    }

    function get_po_items($po_id)
    {
        $this->db->select('po_details.*, products.name as prod_name');
        $this->db->from($this->table);
        $this->db->join('products', 'products.prod_id = po_details.prod_id');
        $this->db->where('po_id',$po_id); // if data is part of the object by ID

        $query = $this->db->get();
        return $query->result();
    }

    function get_supplier_id($po_id)
    {
        $this->db->select('supplier_id');
        $this->db->from($this->table);
        $this->db->where('po_id',$po_id);

        $query = $this->db->get();

        $row = $query->row();

        return $row->supplier_id;
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();

        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);

        return $this->db->count_all_results();
    }
 
    public function get_by_id($num)
    {
        $this->db->from($this->table);
        $this->db->where('num',$num);
        $query = $this->db->get();
 
        return $query->row();
    }
 
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
 
    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($num)
    {
        $this->db->where('num', $num);
        $this->db->delete($this->table);
    }

    function get_no_entry($po_id)
    {      
        $this->db->from($this->table);
        $this->db->where('po_id',$po_id);

        $query = $this->db->get();

        return $query;
    }

    function get_no_quantity($po_id)
    {      
        $this->db->from($this->table);
        $this->db->where('arrived_qty >',0);
        $this->db->where('po_id',$po_id);

        $query = $this->db->get();

        return $query;
    }
}