<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items/Items_model','items');
        $this->load->model('PO_temp/PO_temp_model','po_temp');
    }

    public function index()						
    {
        if($this->session->userdata('administrator') == '0')
        {
            redirect('error500');
        }

        $this->load->helper('url');							

        $data['title'] = '<i class="fa fa-archive"></i> Stocks';					
        $this->load->view('template/dashboard_header',$data);
        $this->load->view('items/items_view',$data);
        $this->load->view('template/dashboard_navigation');
        $this->load->view('template/dashboard_footer');

    }
   
    public function ajax_list()
    {
        $list = $this->items->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            $row[] = $items->prod_id;
            $row[] = 'P' . $items->prod_id;
            $row[] = '<b>' . $items->name . '</b>';
            $row[] = $items->short_name;

            $row[] = $items->stock;
            $row[] = $items->stock_in;
            $row[] = $items->stock_out;
            $row[] = $items->reorder_pt;

            $row[] = $items->encoded;

            if($this->session->userdata('administrator') == '0')
            {
                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="View" onclick="view_product('."'".$items->prod_id."'".')" disabled><i class="fa fa-eye"></i> </a>';
            }
            else
            {
                //add html for action
                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="View" onclick="view_product('."'".$items->prod_id."'".')"><i class="fa fa-eye"></i> </a>';
            }
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->items->count_all(),
                        "recordsFiltered" => $this->items->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($item_id)
    {
        $data = $this->items->get_by_id($item_id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $selected = $this->input->post('selected');

        if ($selected != '')
        {
            $prod_ids = explode(',', $selected);
        
            foreach ($prod_ids as $prod_id) {
                $duplicates = $this->po_temp->get_duplicates($prod_id);
                if ($duplicates->num_rows() == 0)
                {
                    $data = array(
                        'prod_id' => $prod_id,
                        'unit' => 'pcs',
                        'unit_qty' => 0
                    );
                    $insert = $this->po_temp->save($data);
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate();
        $data = array(
                'name' => $this->input->post('name'),
                'descr' => $this->input->post('descr'),
                'type' => $this->input->post('type'),
            );
        $this->items->update(array('item_id' => $this->input->post('item_id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    // delete a items
    public function ajax_delete($item_id)
    {
        $data = array(
                'removed' => 1
            );
        $this->items->update(array('item_id' => $item_id), $data);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('name') == '')
        {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'item name is required';
            $data['status'] = FALSE;
        }
        // validation for duplicates
        else
        {
            $new_name = $this->input->post('name');
            // check if name has a new value or not
            if ($this->input->post('current_name') != $new_name)
            {
                // validate if name already exist in the databaase table
                $duplicates = $this->items->get_duplicates($this->input->post('name'));

                if ($duplicates->num_rows() != 0)
                {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'item name already registered';
                    $data['status'] = FALSE;
                }
            }
        }

        if($this->input->post('type') == '')
        {
            $data['inputerror'][] = 'type';
            $data['error_string'][] = 'item type is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }



    // ================================================ API GET REQUEST METHOD ============================================


    public function ajax_api_list()
    {
        $list = $this->items->get_api_datatables();
        $data = array();
        
        foreach ($list as $items) {

            $row = array();
            $row['item_id'] = $items->item_id;
            $row['name'] = $items->name;
            $row['descr'] = $items->descr;

            if ($items->type == 0)
            {
                $item_type = 'Non Perishable';
            }
            else
            {
                $item_type = 'Perishable';
            }
            
            $row['item_type'] = $item_type;

            $row['encoded'] = $items->encoded;

            $data[] = $row;
        }
    
        //output to json format
        echo json_encode($data);
    }


    // ================================================ API POST REQUEST METHOD ============================================


    public function ajax_input()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        // $ready = $request->ready;
        $array = json_decode(json_encode($request), true);

        $data = array();
        
        foreach ($array as $items) {

            $row = array();
            $row['item_id'] = $items['item_id'];
            $row['name'] = $items['name'];
            $row['descr'] = $items['descr'];
            
            $row['item_type'] = $items['item_type'];

            $row['encoded'] = $items['encoded'];

            $data[] = $row;
        }
        


        //sample gi update nako ni
        // bag ong edit
        //sample
        //output to json format
        echo json_encode($data);
    }
 }