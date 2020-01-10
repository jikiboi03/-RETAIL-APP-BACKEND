<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prod_details_controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Items/Items_model','items');
        $this->load->model('Products/Products_model','products');
        $this->load->model('Categories/Categories_model','categories');

        $this->load->model('Prod_details/Prod_details_model','prod_details');
    }

    public function index($prod_id)						
    {
        if($this->session->userdata('user_id') == '')
        {
            redirect('error500');
        }

        $this->load->helper('url');

        $product_data = $this->products->get_by_id($prod_id);
        
        $data['product'] = $product_data;

        $data['title'] = 'Product Details';
        $this->load->view('template/dashboard_header',$data);
        $this->load->view('prod_details/prod_details_view',$data);
        $this->load->view('template/dashboard_navigation');
        $this->load->view('template/dashboard_footer');

    }
   
    public function ajax_list($prod_id) // get all that belongs to this ID via foreign key
    {
        $list = $this->prod_details->get_datatables($prod_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $prod_details) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $prod_details->qty;

            $row[] = $prod_details->qty_before;
            $row[] = $prod_details->qty_after;

            $row[] = $prod_details->remarks;
            $row[] = $prod_details->encoded;
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prod_details->count_all($prod_id),
                        "recordsFiltered" => $this->prod_details->count_filtered($prod_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($prod_id, $item_id)
    {
        $data = $this->prod_details->get_by_id($prod_id, $item_id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $prod_id = $this->input->post('prod_id');
        $qty = $this->input->post('qty');
        $stock_in = $this->items->get_stock_in($prod_id);
        $stock_out = $this->items->get_stock_out($prod_id);
        $qty_before = ($stock_in - $stock_out);
        $data = array(
                'prod_id' => $prod_id,
                'qty' => $qty,
                'qty_before' => $qty_before,
                'qty_after' => ($qty_before + $qty),
                'remarks' => $this->input->post('remarks')
            );
        $insert = $this->prod_details->save($data);

        if ($qty > 0)
            $this->items->update_stock_in($prod_id, $qty);
        else
            $this->items->update_stock_out($prod_id, ($qty * -1));
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate_qty_only();
        $data = array(
                'qty' => $this->input->post('qty'),
            );
        $this->prod_details->update(array('prod_id' => $this->input->post('prod_id'), 'item_id' => $this->input->post('current_item_id')), $data); // update record with multiple where clause
        echo json_encode(array("status" => TRUE));
    }

    // delete a record
    public function ajax_delete($prod_id, $item_id)
    {
        $this->prod_details->delete_by_id($prod_id, $item_id);
        echo json_encode(array("status" => TRUE));
    }


    // ================================================= IMAGE SECTION =====================================================


    public function do_upload() 
    {
        $prod_id = $this->input->post('prod_id');

        $version = 0;

        // try
        // {
        //     $img_name = $this->products->get_product_img($prod_id);

        //     $version = explode("_", $img_name)[1]; // get index 1 of the exploded img_name to increment
        // }
        // catch (Exception $e) {
        //     // json_encode 'Caught exception: ',  $e->getMessage(), "\n";
        // }

        if ($this->products->get_product_img($prod_id) != '')
        {
            $img_name = $this->products->get_product_img($prod_id);

            $version = explode("_", $img_name)[1]; // get index 1 of the exploded img_name to increment
        }

        $new_version = ($version + 1);

         $config['upload_path']   = 'uploads/products'; 
         $config['allowed_types'] = 'jpg|jpeg'; 
         $config['max_size']      = 2000; 
         $config['max_width']     = 5000; 
         $config['max_height']    = 5000;
         $new_name = $prod_id . '_' . $new_version . '_.jpg';
         $config['file_name'] = $new_name;
         $config['overwrite'] = TRUE;

         $this->load->library('upload', $config);
            
         if ( ! $this->upload->do_upload('userfile1')) // upload fail
         {
            $error = array('error' => $this->upload->display_errors() . '<a href="javascript:history.back()">Back to Product</a>'); 
            $this->load->view('upload_form', $error);
            // echo '<script type="text/javascript">alert("' . $error.toString() . '"); </script>';
         }
         else // upload success
         { 
            $data = array('upload_data' => $this->upload->data()); 
            
            $data = array(
                'img' => $new_name
            );
            $this->products->update(array('prod_id' => $prod_id), $data);
            redirect('/prod-details-page/' . $prod_id);
         } 
    }


    // ======================================= END IMAGE SECTION ===========================================================

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('qty') == '')
        {
            $data['inputerror'][] = 'qty';
            $data['error_string'][] = 'Stock adjustment quantity is required';
            $data['status'] = FALSE;
        }
        else if($this->input->post('qty') == 0)
        {
            $data['inputerror'][] = 'qty';
            $data['error_string'][] = 'Quantity value should not be zero';
            $data['status'] = FALSE;
        }

        if($this->input->post('remarks') == '')
        {
            $data['inputerror'][] = 'remarks';
            $data['error_string'][] = 'Adjustment remark is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }


    // ================================================ API GET REQUEST METHOD ============================================


    public function ajax_api_list($prod_id) // get all that belongs to this ID via foreign key (api function)
    {
        $list = $this->prod_details->get_api_datatables($prod_id);
        $data = array();
        
        foreach ($list as $prod_details) {
            
            $row = array();
            $row['item_id'] = $prod_details->item_id;

            $item_name = $this->items->get_item_name($prod_details->item_id);
            $row['item_name'] = $item_name;

            $row['descr'] = $this->items->get_item_descr($prod_details->item_id);

            $row['qty'] = $prod_details->qty;

            $row['encoded'] = $prod_details->encoded;

            $data[] = $row;
        }
 
        //output to json format
        echo json_encode($data);
    }


    // ================================================ API POST REQUEST METHOD ============================================
 }