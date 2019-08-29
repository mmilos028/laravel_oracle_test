<?php

namespace App\Http\Controllers\Rest\Administration\Vouchers\Excel;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use App\Models\Oracle\VoucherModel;

class VoucherExportExcel implements FromView
{

    private $backoffice_session_id = '';
    private $backoffice_username = '';
    private $from_serial = '';
    private $to_serial = '';
    
    public function __construct($backoffice_session_id, $backoffice_username, $from_serial, $to_serial)
    {
        $this->backoffice_session_id = $backoffice_session_id;
        $this->backoffice_username = $backoffice_username;
        $this->from_serial = $from_serial;
        $this->to_serial = $to_serial;
    }
    
    public function view(): View
    {
            
      $arrData = VoucherModel::getPrepaidCards($this->backoffice_session_id, $this->from_serial, $this->to_serial);
            
      return view("exports.excel.administration.vouchers.list_vouchers", [
          'data' => $arrData['list_prepaid_cards']
      ]);
      
    }
}