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

$po_status = $po->status;
$status_color = '<b color="#006600">';
if ($po_status == 'CANCELLED'){
    $status_color = '<b color="#A93226">';
} else if ($po_status == 'COMPLETED') {
    $status_color = '<b color="#212F3D">';
}

$text = '<h3 align="center">Purchase Order: PO' . $po->po_id . '</h3>
<br />
<table>
    <tr>
        <td style="width: 70%;">
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td>
            Supplier: <b>' . $this->suppliers->get_supplier_name($po->supplier_id) . ' </b>
        </td>
        <td>
            Date: <b>' . $po->date . ' </b>
        </td>
    </tr>
    <br />
    <tr>
        <td>
            Status: ' . $status_color . $po_status . ' </b>
        </td>
        <td>
            Created by: <b>' . $this->users->get_username($po->user_id) . ' </b>
        </td>
    </tr>
    <br />
    <tr>
        <td>
            Encoded: <b>' . $po->encoded . ' </b>
        </td>
        <td>
        </td>
    </tr>
    <br />
    <tr>
        <td>
        </td>
        <td>
        </td>
    </tr>
</table>
<hr>'
;
$pdf->writeHTML($text, true, 0, true, 0);

$pdf->ColoredTable_po($header, $data);

// close and output PDF document
$pdf->Output('PO' . $po->po_id . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
