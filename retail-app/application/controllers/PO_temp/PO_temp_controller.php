<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO_temp_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PO_temp/PO_temp_model','po_temp');
        $this->load->model('Products/Products_model','products');
        $this->load->model('Suppliers/Suppliers_model','suppliers');
    }

    public function index()						
    {
        if($this->session->userdata('administrator') == '0')
        {
            redirect('error500');
        }

        $this->load->helper('url');	
        
        $products_data = $this->products->get_products();
        $suppliers_data = $this->suppliers->get_suppliers();
        
        $data['products'] = $products_data;
        $data['suppliers'] = $suppliers_data;

        $data['supplier'] = $this->po_temp->get_set_by_id(1);

        $data['title'] = '<i class="fa fa-archive"></i> Create Purchase Order';					
        $this->load->view('template/dashboard_header',$data);
        $this->load->view('po_temp/po_temp_view',$data);
        $this->load->view('template/dashboard_navigation');
        $this->load->view('template/dashboard_footer');

    }
   
    public function ajax_list()
    {
        $list = $this->po_temp->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $po_temp) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = 'P' . $po_temp->prod_id;
            $row[] = '<b>' . $po_temp->prod_name . '</b>';

            $row[] = $po_temp->unit_qty;
            $row[] = $po_temp->unit;

            //add html for action
            $row[] = '<a class="btn btn-info" href="javascript:void(0)" title="Edit" onclick="edit_po_temp('."'".$po_temp->num."'".')"><i class="fa fa-pencil-square-o"></i></a>
                      
                      <a class="btn btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_po_temp('."'".$po_temp->num."'".')"><i class="fa fa-trash"></i></a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->po_temp->count_all(),
                        "recordsFiltered" => $this->po_temp->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($num)
    {
        $data = $this->po_temp->get_by_id($num);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $prod_id = $this->input->post('prod_id');
        $duplicates = $this->po_temp->get_duplicates($prod_id);
        if ($duplicates->num_rows() == 0)
        {
            $data = array(
                'prod_id' => $prod_id,
                'unit_qty' => $this->input->post('unit_qty'),
                'unit' => 'pcs',
            );
            $insert = $this->po_temp->save($data);
        }
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_set()
    {
        $data = array(
            'supplier_id' => $this->input->post('supplier_id'),
            'date' => $this->input->post('date')
        );
        $this->po_temp->set(array('id' => 1), $data);

        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate_edit();
        $data = array(
                'unit_qty' => $this->input->post('unit_qty'),
            );
        $this->po_temp->update(array('num' => $this->input->post('num')), $data);
        echo json_encode(array("status" => TRUE));
    }

    // delete a record
    public function ajax_delete($num)
    {
        $this->po_temp->delete_by_id($num);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $prod_id = $this->input->post('prod_id');

        if($prod_id == '')
        {
            $data['inputerror'][] = 'prod_id';
            $data['error_string'][] = 'Product is required';
            $data['status'] = FALSE;
        }
        else 
        {
            $duplicates = $this->po_temp->get_duplicates($prod_id);
            if($duplicates->num_rows() != 0)
            {
                $data['inputerror'][] = 'prod_id';
                $data['error_string'][] = 'Product is already in the list';
                $data['status'] = FALSE;
            }
        }

        if($this->input->post('unit_qty') == '')
        {
            $data['inputerror'][] = 'unit_qty';
            $data['error_string'][] = 'Unit quantity is required';
            $data['status'] = FALSE;
        }

        else if($this->input->post('unit_qty') <= 0)
        {
            $data['inputerror'][] = 'unit_qty';
            $data['error_string'][] = 'Unit quantity should be greater than zero';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    private function _validate_edit()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('unit_qty') == '')
        {
            $data['inputerror'][] = 'unit_qty';
            $data['error_string'][] = 'Unit quantity is required';
            $data['status'] = FALSE;
        }

        else if($this->input->post('unit_qty') <= 0)
        {
            $data['inputerror'][] = 'unit_qty';
            $data['error_string'][] = 'Unit quantity should be greater than zero';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

 }