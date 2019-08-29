<?php

namespace App\Http\Controllers\Rest\Administration\Vouchers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Oracle\VoucherModel;
use Elibyy\TCPDF\Facades\TCPDF;

use Illuminate\Http\Request;

class VoucherPdfController extends Controller
{
            
    public function voucherCreatePdfHorizontalLayout(Request $request)
    {
        $backoffice_session_id = $request->get('backoffice_session_id', null);
        $backoffice_username = $request->get('backoffice_username', null);
        $from_serial = $request->get('serial_number_start', null);
        $to_serial = $request->get('serial_number_end', null);
        $barcode_type = $request->get('barcode_type', 'C128');
        
        //if ($request->isMethod('POST')) {
        
            $arrData = VoucherModel::getPrepaidCards($backoffice_session_id, $from_serial, $to_serial);
            //print_r($arrData); die;
            $cards_data = $arrData['list_prepaid_cards'];
            if (isset($cards_data) && !empty($cards_data)) {
                $currency = $cards_data[0]['currency'];
                $expiry_date = $cards_data[0]['expiry_date'];
                $refill_type = $cards_data[0]['refill_type'];
                $username = $cards_data[0]['username'];
                foreach ($cards_data as $data) {
                    if (($username && !$data['username']) || (!$username && $data['username'])) {
                        return;
                    }
                    if ($data['status']=='E' || $data['status']=='U' || $currency != $data['currency'] || $expiry_date != $data['expiry_date'] || $refill_type != $data['refill_type']) {
                        return;
                    }
                }
            }
            
            //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            
            TCPDF::setPageFormat(PDF_PAGE_ORIENTATION, PDF_UNIT);
            
            
            TCPDF::setCreator("CASINO");
            //$pdf->SetAuthor($backoffice_username);
            TCPDF::setTitle("PPCard");
            TCPDF::setSubject("PPCard");
            TCPDF::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            TCPDF::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            TCPDF::SetFooterMargin(PDF_MARGIN_FOOTER);
            TCPDF::SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $style = array(
                'position' => 'S',
                'align' => '',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => false,
                'font' => 'helvetica',
                'fontsize' => 1,
                'stretchtext' => 4);
            $card_counter = 1;
            $j=1;
            $barcode_size = 16;
            $html = "";
            $limit_per_page = 8;
            
            foreach ($cards_data as $data) {
                $expire_date = "";
                $refill = (isset($data['refill_allowed']) && $data['refill_allowed']=="Y") ? " - Refill" : "";
                
                if (isset($data['expiry_date']) && $data['expiry_date']) {
                    $expire_date = explode(' ', $data['expiry_date']);
                    $expire_date = explode('.', $expire_date[0]);
                    $expire_date = $expire_date[2] . '/' . $expire_date[1] . '/' . $expire_date[0];
                }
                
                $code_to_show = chunk_split($data['prepaid_code'], 4, ' ');
                $card_name = "Voucher";
                if ($data['refill_type'] == "PROMO MONEY") {
                    $card_name = "Promotion Voucher";
                }
                if (isset($data['username']) && $data['username'] && isset($data['password']) && $data['password']) {
                    $barcode_size = 12;
                    //$barcode = $pdf->serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], 'C39', '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    $barcode = TCPDF::serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], $barcode_type, '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    $card_name = "Prepaid Card";
                    $limit_per_page = 8;
                    $card_member_addon = <<<CARD_MEMEBER_ADDON
				<tr>
					<td colspan="3" style="font-size:4mm;font-weight:bold;">&nbsp;New Account</td>
				</tr>
				<tr>
					<td style="font-size:3mm;font-weight:bold;"> Username</td>
					<td></td>
					<td style="font-size:3mm;font-weight:bold;">Password</td>
				</tr>
				<tr>
					<td style="font-size:3mm;"> {$data['username']}</td>
					<td></td>
					<td style="font-size:3mm;">{$data['password']}</td>
				</tr>
CARD_MEMEBER_ADDON;
                } else {
                    $barcode_size = 16;
                    //$barcode = $pdf->serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], 'C39', '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    $barcode = TCPDF::serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], $barcode_type, '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    $card_member_addon = <<<CARD_MEMEBER_ADDON
				<tr>
					<td colspan="3" style="font-size:4mm;font-weight:bold;"></td>
				</tr>
CARD_MEMEBER_ADDON;
                }
                $card = <<<CARD
			<table><tr><td></td></tr></table>
			<table style="height:53.98mm;background:#FFF;width:85.60mm;margin:10mm;padding:0;border:1px dashed #666" cellpadding="1" border="0">
				{$card_member_addon}
				<tr>
					<td style="font-size:4mm;width:57%;font-weight:bold;">&nbsp;{$card_name}{$refill}</td>
					<td style="font-size:3mm;width:40%;text-align:right;font-weight:bold;" colspan="2">Expiry date: {$expire_date}</td>
				</tr>
				<tr>
					<td style="width:18%;font-size:3mm;height:20px;line-height:10px;text-align:right;">Amount:</td>
					<td style="width:35%;"><span style="font-size:5mm;height:12px;line-height:6px;font-weight:bold;">{$data['amount']} </span><span style="color:#999;font-size:5mm;height:12px;line-height:6px;font-weight:bold;">{$data['currency']}</span></td>
					<td style="width:47%;"></td>
				</tr>
				<tr>
					<td style="font-size:3mm" colspan="3">&nbsp;Serial number: {$data['serial_number']}</td>
				</tr>
				<tr>
					<td style="font-size:1mm" colspan="3"><tcpdf method="write1DBarcode" params="{$barcode}"/></td>
				</tr>
				<tr>
					<td style="font-size:3mm;" colspan="3">&nbsp;Voucher code: <span style="font-weight:bold;">{$code_to_show}</span></td>
				</tr>
			</table>
CARD;
				if ($j%9==0 || $j==1) {
				    $html .= "<table style=''>";
				}
				if ($j%2) {
				    $html .= "<tr>";
				}
				$html .= "<td>" . $card . "</td>";
				if (!($j%2)) {
				    $html .= "</tr>";
				}
				if ($j>$limit_per_page-1 || $card_counter==count($cards_data)) {
				    $html .= "</table>";
				    //echo "<xmp>".$html."</xmp>";
				    TCPDF::AddPage();
				    TCPDF::writeHTML($html, true, false, false, false, '');
				    $html = "";
				    $j = 0;
				    //continue;
				}
				$j++;
				$card_counter++;
            }
            //echo "<xmp>".$html."</xmp>";
            TCPDF::lastPage();
            //$pdf_title = "PPCard-" . date("Ymd-His") . ".pdf";
            $pdf_title = "Voucher-WIDE-{$from_serial}-{$to_serial}.pdf";
            TCPDF::Output($pdf_title, 'D');            
        //}
    }
    
    public function voucherCreatePdf(Request $request)
    {
        $backoffice_session_id = $request->get('backoffice_session_id', null);
        $backoffice_username = $request->get('backoffice_username', null);
        $from_serial = $request->get('serial_number_start', null);
        $to_serial = $request->get('serial_number_end', null);
        $barcode_type = $request->get('barcode_type', 'C128');
        
        //if ($request->isMethod('POST')) {
                       
            $arrData = VoucherModel::getPrepaidCards($backoffice_session_id, $from_serial, $to_serial);
            $cards_data = $arrData['list_prepaid_cards'];
            if (isset($cards_data) && !empty($cards_data)) {
                $currency = $cards_data[0]['currency'];
                $expiry_date = $cards_data[0]['expiry_date'];
                $refill_type = $cards_data[0]['refill_type'];
                $username = $cards_data[0]['username'];
                foreach ($cards_data as $data) {
                    if (($username && !$data['username']) || (!$username && $data['username'])) {
                        return;
                    }
                    if ($data['status']=='E' || $data['status']=='U' || $currency != $data['currency'] || $expiry_date != $data['expiry_date'] || $refill_type != $data['refill_type']) {
                        return;
                    }
                }
            }
            //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            TCPDF::setPageFormat(PDF_PAGE_ORIENTATION, PDF_UNIT);
            TCPDF::setCreator(PDF_CREATOR);
            TCPDF::setAuthor($backoffice_username);
            TCPDF::setTitle('PPCard');
            TCPDF::setSubject('PPCard');
            TCPDF::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            TCPDF::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            TCPDF::setFooterMargin(PDF_MARGIN_FOOTER);
            //$pdf->SetFont('freeserif', '', 10);
            TCPDF::setAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $style = array(
                'position' => 'S',
                'align' => '',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => false,
                'font' => 'helvetica',
                'fontsize' => 1,
                'stretchtext' => 4);
            $card_counter = 1;
            $j=1;
            $html = "";
            $limit_per_page = 9;
            $new_row=1;
            foreach ($cards_data as $data) {
                $expire_date = "";
                //$refill = (isset($data['refill_allowed']) && $data['refill_allowed']=="Y") ? " - Refill" : "";
                if (isset($data['expiry_date']) && $data['expiry_date']) {
                    $expire_date = explode(' ', $data['expiry_date']);
                    $expire_date = explode('.', $expire_date[0]);
                    $expire_date = $expire_date[2] . '/' . $expire_date[1] . '/' . $expire_date[0];
                }
                //$barcode_size++;
                $code_to_show = chunk_split($data['prepaid_code'], 4, ' ');
                $card_name = "Voucher";
                if ($data['refill_type'] == "PROMO MONEY") {
                    $card_name = "Promotion Voucher";
                }
                if (isset($data['username']) && $data['username'] && isset($data['password']) && $data['password']) {
                    $barcode_size = 22;
                    $barcode = TCPDF::serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], $barcode_type, '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    //$card_name = "Prepaid Card";
                    $limit_per_page = 9;
                    $card_member_addon = <<<CARD_MEMEBER_ADDON
			<tr><td colspan="3" style="font-size:5px;"></td></tr>
			<tr>
				<td colspan="3" style="font-size:4mm;font-weight:bold;">&nbsp;New Account</td>
			</tr>
			<tr>
				<td colspan="3" style="font-size:2px;font-weight:bold;">
					<table cellspacing="2"><tr><td style="border-top:1px solid #AAA;"></td></tr></table>
				</td>
			</tr>
			<tr>
				<td style="font-size:3mm;"> &nbsp;Username</td>
				<td style="font-size:3mm;font-weight:bold;"></td>
				<td style="font-size:3mm;">Password</td>
			</tr>
			<tr>
				<td style="font-size:3mm;font-weight:bold;" colspan="2"> &nbsp;{$data['username']}</td>
				<td style="font-size:3mm;font-weight:bold;">{$data['password']}</td>
			</tr>
			<tr>
				<td style="width:100%;font-size:3mm;" colspan="3">&nbsp;&nbsp;Voucher code: <span style="font-weight:bold;">{$code_to_show}</span></td>
			</tr>
			<tr>
				<td style="font-size:4mm;width:57%;">&nbsp;{$card_name}</td>
				<td style="font-size:3mm;width:42%;text-align:left;line-height:5px;" colspan="2">Serial number:</td>
			</tr>
			<tr>
				<td style="font-size:3mm;">&nbsp;</td>
				<td colspan="2" style="font-size:3mm;text-align:center;font-weight:bold;">{$data['serial_number']}</td>
			</tr>
CARD_MEMEBER_ADDON;
                } else {
                    $barcode_size = 26;
                    $barcode = TCPDF::serializeTCPDFtagParameters(array('$$'.$data['prepaid_code'], $barcode_type, '', '', 0, $barcode_size, 0.3, $style, 'N'));
                    $card_member_addon = <<<CARD_MEMEBER_ADDON
			<tr><td colspan="3" style="font-size:18px;"></td></tr>
			<tr>
				<td style="font-size:5mm;text-align:left;font-weight:bold;" colspan="3">&nbsp;{$card_name}</td>
			</tr>
			<tr><td colspan="3" style="font-size:8px;"></td></tr>
			<tr>
				<td colspan="3" style="font-size:3mm;font-weight:bold;">
					<table cellspacing="2"><tr><td style="border-top:1px solid #AAA;"></td></tr></table>
				</td>
			</tr>
			<tr>
				<td style="width:100%;font-size:3mm;" colspan="3">&nbsp;&nbsp;Voucher code: <span style="font-weight:bold;">{$code_to_show}</span></td>
			</tr>
			<tr><td colspan="3" style="font-size:8px;"></td></tr>
			<tr><td colspan="3" style="font-size:8px;"></td></tr>
			<tr>
				<td style="font-size:3mm; width:42%;">&nbsp;&nbsp;Serial number:</td>
				<td colspan="2" style="font-size:3mm; text-align: left;font-weight:bold;">{$data['serial_number']}</td>
				
			</tr>
CARD_MEMEBER_ADDON;
                }
                $amount = number_format($data['amount'], 0, '', '.');
                $card = <<<CARD
		<table><tr><td></td></tr></table>
		<table><tr><td></td></tr></table>
		<table style="height:85.60mm;background:#FFF;width:54mm;margin:10mm;padding:0;border:1px dashed #666" cellpadding="1" border="0">
			{$card_member_addon}
			<tr>
				<td style="width:27%;font-size:3mm;height:20px;line-height:10px;text-align:right;">Amount:</td>
				<td style="width:70%;" colspan="2"><span style="font-size:5mm;height:12px;line-height:6px;font-weight:bold;">{$amount} </span><span style="color:#999;font-size:5mm;height:12px;line-height:6px;font-weight:bold;">{$data['currency']}</span></td>
			</tr>
			<tr>
				<td style="width:100%;font-size:3mm" colspan="3">&nbsp;&nbsp;Expiry date: <span style="font-weight:bold;">{$expire_date}</span></td>
			</tr>
			<tr>
				<td style="width:100%;font-size:1mm" colspan="3"><tcpdf method="write1DBarcode" params="{$barcode}"/></td>
			</tr>
		</table>
CARD;
			if ($j==1) {
			    $html .= "<table style=''>";
			}
			if ($new_row) {
			    $html .= "<tr>";
			}
			$html .= "<td>" . $card . "</td>";
			$new_row = ($j%3 == 0) ? 1 : 0;
			if ($j%3 == 0 || $card_counter == count($cards_data)) {
			    $html .= "</tr>";
			}
			if ($j==$limit_per_page || $card_counter == count($cards_data)) {
			    $html .= "</table>";
			    TCPDF::AddPage();
			    TCPDF::writeHTML($html, true, false, false, false, '');
			    $html = "";
			    $j = 0;
			}
			$j++;
			$card_counter++;
            }
            TCPDF::lastPage();
            $pdf_title = "Voucher-{$from_serial}-{$to_serial}.pdf";
            TCPDF::Output($pdf_title, 'D');
        //}
    }
    
}