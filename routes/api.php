<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
 * http://192.168.3.63/laravel_oracle_test/public/api/rest/oracle/test
 * */
Route::group(
    [
        'prefix' => 'rest',
        'middleware' => ['cors']
    ],
    function()
    {
        Route::match( array('GET', 'POST'), '/oracle/test', 'Rest\OracleController@test');
        Route::match( array('GET', 'POST'), '/oracle/list-countries', 'Rest\OracleController@listCountries');
        
        
        
        Route::match( array('POST'), '/authenticate/login-backoffice', 'Rest\AuthController@loginBackoffice');
        Route::match( array('POST'), '/authenticate/logout-backoffice', 'Rest\AuthController@logoutBackoffice');
        
        
        
        Route::match( array('POST', 'GET'), '/index/list-countries', 'Rest\IndexController@listCountries' );
        
        
        
        Route::match( array('POST'), '/session/get-session-remaining-time', 'Rest\SessionController@pingSession');
        Route::match( array('POST'), '/session/validate-session', 'Rest\SessionController@validateSession' );
        
        
        
        Route::match( array('POST'), '/my-account/personal-information', 'Rest\MyAccountController@personalInformation');
        
        Route::match( array('POST'), '/my-account/save-personal-information', 'Rest\MyAccountController@savePersonalInformation');
        
        
        
        Route::match( array('POST'), '/administration/vouchers/search-prepaid-cards', 'Rest\Administration\Vouchers\VoucherController@searchPrepaidCards');
        
        Route::match( array('POST'), '/administration/vouchers/list-prepaid-cards', 'Rest\Administration\Vouchers\VoucherController@listPrepaidCards');
        
        Route::match( array('POST'), '/administration/vouchers/list-available-amounts', 'Rest\Administration\Vouchers\VoucherController@listAvailableAmounts');
        
        Route::match( array('POST'), '/administration/vouchers/list-available-currency', 'Rest\Administration\Vouchers\VoucherController@listAvailableCurrency');
        
        Route::match( array('POST'), '/administration/vouchers/list-available-statuses', 'Rest\Administration\Vouchers\VoucherController@listAvailableStatuses');
        
        Route::match( array('POST'), '/administration/vouchers/list-affiliate-creators', 'Rest\Administration\Vouchers\VoucherController@listAffiliateCreators');
        
        Route::match( array('POST'), '/administration/vouchers/list-affiliate-owners', 'Rest\Administration\Vouchers\VoucherController@listAffiliateOwners');
        
        Route::match( array('POST'), '/administration/vouchers/list-used-by-player', 'Rest\Administration\Vouchers\VoucherController@listUsedByPlayer');
        
        Route::match( array('POST'), '/administration/vouchers/list-affiliates-for-currency', 'Rest\Administration\Vouchers\VoucherController@listAffiliatesForCurrency');
        
        Route::match( array('POST'), '/administration/vouchers/create-voucher-card', 'Rest\Administration\Vouchers\VoucherController@createVoucherCard');
        
        Route::match( array('POST'), '/administration/vouchers/edit-voucher-card', 'Rest\Administration\Vouchers\VoucherController@editVoucherCard');
        
        Route::match( array('POST', 'GET'), '/administration/vouchers/pdf/pdf-horizontal-layout', 'Rest\Administration\Vouchers\Pdf\VoucherPdfController@voucherCreatePdfHorizontalLayout');
        
        Route::match( array('POST', 'GET'), '/administration/vouchers/pdf/pdf', 'Rest\Administration\Vouchers\Pdf\VoucherPdfController@voucherCreatePdf');
        
        Route::match( array('POST', 'GET'), '/administration/vouchers/excel/list-vouchers', 'Rest\Administration\Vouchers\Excel\VoucherExcelController@voucherCreateExcel');
        
        Route::match( array('POST'), '/administration/vouchers/create-member-card', 'Rest\Administration\Vouchers\VoucherController@createMemberCard');
        
        Route::match( array('POST'), '/administration/vouchers/edit-member-card', 'Rest\Administration\Vouchers\VoucherController@editMemberCard');
        
        Route::match( array('POST', 'GET'), '/administration/member/pdf/pdf-horizontal-layout', 'Rest\Administration\Vouchers\Pdf\MemberPdfController@memberCardCreatePdfHorizontalLayout');
        
        Route::match( array('POST', 'GET'), '/administration/member/pdf/pdf', 'Rest\Administration\Vouchers\Pdf\MemberPdfController@memberCardCreatePdf');
        
        Route::match( array('POST', 'GET'), '/administration/member/pdf/qr-pdf', 'Rest\Administration\Vouchers\Pdf\MemberPdfController@memberCardCreateQrPdf');
        
        Route::match( array('POST', 'GET'), '/administration/member/pdf/qr-pdf-wide', 'Rest\Administration\Vouchers\Pdf\MemberPdfController@memberCardCreateQrPdfHorizontalLayout');
        
        
        Route::match( array('POST', 'GET'), '/administration/users-and-administrators/list-all-roles', 
            'Rest\Administration\UsersAndAdministrators\NewUserController@listAllRoles'
        );
        
        Route::match( array('POST', 'GET'), '/administration/users-and-administrators/list-affiliates-for-new-user',
            'Rest\Administration\UsersAndAdministrators\NewUserController@listAffiliatesForNewUserForm'
        );
                
    }
);

Route::group(
    [
        'prefix' => 'rest',
    ],
    function()
    {        
        
        Route::match( array('POST', 'GET'), '/administration/vouchers/excel/list-vouchers', 'Rest\Administration\Vouchers\Excel\VoucherExcelController@voucherCreateExcel');
        
        Route::match( array('POST', 'GET'), '/administration/member/excel/list-member-cards', 'Rest\Administration\Vouchers\Excel\MemberExcelController@memberCardsCreateExcel');
    }
);
