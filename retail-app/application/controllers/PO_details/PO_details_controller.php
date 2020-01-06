<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PO_details_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PO/PO_model','po');
        $this->load->model('PO_details/PO_details_model','po_details');
        $this->load->model('Products/Products_model','products');
        $this->load->model('Suppliers/Suppliers_model','suppliers');
    }

    public function index($po_id)						
    {
        if($this->session->userdata('administrator') == '0')
        {
            redirect('error500');
        }

        $this->load->helper('url');	
        
        $products_data = $this->products->get_products();
        $suppliers_data = $this->suppliers->get_suppliers();
        $po_data = $this->po->get_by_id($po_id);
        
        $data['products'] = $products_data;
        $data['suppliers'] = $suppliers_data;
        $data['po'] = $po_data;

        $data['title'] = '<i class="fa fa-archive"></i> Purchase Order Details';					
        $this->load->view('template/dashboard_header',$data);
        $this->load->view('po_details/po_details_view',$data);
        $this->load->view('template/dashboard_navigation');
        $this->load->view('template/dashboard_footer');

    }
   
    public function ajax_list($po_id)
    {
        $list = $this->po_details->get_datatables($po_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $po_details) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = 'P' . $po_details->prod_id;
            $row[] = '<b>' . $po_details->prod_name . '</b>';

            $row[] = $po_details->unit_qty;
            $row[] = $po_details->unit;
            $row[] = $po_details->arrived_qty;

            //add html for action
            $row[] = '<a class="btn btn-info" href="javascript:void(0)" title="Edit" onclick="edit_po_details('."'".$po_details->num."'".')"><i class="fa fa-pencil-square-o"></i></a>
                      
                      <a class="btn btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_po_details('."'".$po_details->num."'".')"><i class="fa fa-trash"></i></a>';
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->po_details->count_all(),
                        "recordsFiltered" => $this->po_details->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($num)
    {
        $data = $this->po_details->get_by_id($num);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $po_id = $this->input->post('po_id');
        $prod_id = $this->input->post('prod_id');
        $duplicates = $this->po_details->get_duplicates($po_id, $prod_id);
        if ($duplicates->num_rows() == 0)
        {
            $data = array(
                'po_id' => $po_id,
                'prod_id' => $prod_id,
                'unit_qty' => $this->input->post('unit_qty'),
                'unit' => 'pcs',
                'arrived_qty' => 0,
            );
            $insert = $this->po_details->save($data);
        }
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate_edit();
        $data = array(
                'unit_qty' => $this->input->post('unit_qty'),
            );
        $this->po_details->update(array('num' => $this->input->post('num')), $data);
        echo json_encode(array("status" => TRUE));
    }

    // delete a record
    public function ajax_delete($num)
    {
        $this->po_details->delete_by_id($num);
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
            $duplicates = $this->po_details->get_duplicates($prod_id);
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