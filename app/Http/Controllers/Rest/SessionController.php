<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Oracle\SessionModel;


class SessionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    public function validateSession(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $result = SessionModel::validateSession($backoffice_session_id);
        
        return $result;
    }
    
    public function pingSession(Request $request)
    {
        $backoffice_session_id = \Request::json()->get('backoffice_session_id');
        
        $result = SessionModel::pingSession($backoffice_session_id);
        
        return $result;
    }
}