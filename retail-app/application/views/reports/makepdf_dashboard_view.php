<?php

//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($user_fullname);
$pdf->SetTitle($title);
$pdf->SetSubject('Dashboard Report');
$pdf->SetKeywords('dashboard');


// set default header data
$pdf->SetHeaderData(null, 0, $title . ' ( ' . $current_date . " )", $comp_name . "\r\n" . 'Prepared by: ' . $user_fullname . "\r\n" . 'Date: ' . $current_date . "\r\n" . 'Time: ' . $current_time . "\r\n");



// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 48, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(12);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(8);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$text = '<h3 align="center">Report Summary</h3>
<p align="left">1. Total Net Sales: <b color="#006600">' . $today_net_sales_str . ' </b> | ' . $percent_net_sales_str . ' </p>
<p align="left">2. Total Transactions: <b color="#006600">' . $total_trans_count_today . ' </b> | Dine-In [ ' . $dine_in_today . ' ] | Take-Out [ ' . $dine_in_today . ' ] </p>
<p align="left">3. Total Menu Items Sold: <b color="#006600">' . $total_menu_items_sold_today . ' </b> | Individual Products [ ' . $individual_products_sold_today . ' ] | Packages [ ' . $packages_sold_today . ' ] </p>
<p align="left">4. Total Discounts Rendered: <b color="#006600">' . $discounts_rendered_today_str . ' </b> | ' . $discounts_gross_percentage_str . '</p>
<p align="left">5. Total Cancelled Transactions: <b color="#006600">' . $cancelled_trans_today . ' </b> | ' . $voided_menu_items_today_str . '</p>
<hr>'
;
$pdf->writeHTML($text, true, 0, true, 0);

$pdf->ColoredTable_dashboard($header, $data);

// close and output PDF document
$pdf->Output('dashboard.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
