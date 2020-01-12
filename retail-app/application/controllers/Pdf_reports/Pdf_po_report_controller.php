<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_po_report_controller extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->load->model('Store_config/Store_config_model','store');
        $this->load->model('PO/PO_model','po');
        $this->load->model('PO_details/PO_details_model','po_details');
        $this->load->model('Products/Products_model','products');
        $this->load->model('Suppliers/Suppliers_model','suppliers');
        $this->load->model('Users/Users_model','users');
	}

	public function index($po_id)
	{
		// check if logged in and admin
		if($this->session->userdata('user_id') == '' || ($this->session->userdata('administrator') == "0" && $this->session->userdata('cashier') == "0"))
		{
          redirect('error500');
        }

        // get today's date and yesterday
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $today) ) ));

        $po_data = $this->po->get_by_id($po_id);

		$data['po'] = $po_data;

		// ==================================== REPORT ESSENTIALS ========================================================


		$data['logo_img'] = $this->store->get_store_config_img(1);

		$data['comp_name'] = $this->store->get_store_config_name(1);

		$data['data'] = $this->LoadData($po_id); // load and fetch data
		
		$data['title'] = 'Purchase Order';

		$data['date_today'] = $today;

		$data['current_date'] = date('l, F j, Y', strtotime(date('Y-m-d')));

		$data['current_time'] = date("h:i:s A");

		$data['user_fullname'] = $this->session->userdata('firstname') .' '. $this->session->userdata('lastname');

		// column titles
		$data['header'] = array('#', 'Product ID', 'Name', 'Qty', 'Unit', 'Arrived Qty');

		$this->load->library('MYPDF');
		$this->load->view('reports/makepdf_po_view', $data);
	}

	// Load table data from file
	public function LoadData($po_id) 
	{
		$list = $this->po_details->get_po_items($po_id);
        $data = array();
        $no = 0;

		foreach ($list as $po_details) {
            $no++;
            $arrived_qty = $po_details->arrived_qty;
            if ($arrived_qty != 0){
                $arrived_qty = $arrived_qty;
            } else {
                $arrived_qty = "";
            }
            $row = array();
            $row[] = $no;
		    $row[] = 'P' . $po_details->prod_id;
            $row[] = $po_details->prod_name;

            $row[] = $po_details->unit_qty;
            $row[] = $po_details->unit;
            $row[] = $arrived_qty;

		    $data[] = $row;
		}

		return $data;
	}

}
