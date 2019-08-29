<?php

namespace App\Http\Controllers\Rest\Administration\Vouchers\Excel;

use App\Http\Controllers\Controller;
use App\Models\Oracle\VoucherModel;
use Maatwebsite\Excel\Facades\Excel;
//use Excel;

use Illuminate\Http\Request;

class MemberExcelController extends Controller
{
            
    public function memberCardsCreateExcel(Request $request)
    {
        $backoffice_session_id = $request->get('backoffice_session_id', null);
        $backoffice_username = $request->get('backoffice_username', null);
        $from_serial = $request->get('serial_number_start', null);
        $to_serial = $request->get('serial_number_end', null);
        
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
        
        $exportExcel = new MemberCardsExportExcel($backoffice_session_id, $backoffice_username, $from_serial, $to_serial);
        
        return Excel::download($exportExcel, "member_cards_list_{$from_serial}_{$to_serial}.xlsx");
    }    
}