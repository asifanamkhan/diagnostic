<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'role:super_admin']], function() {
	//resource routes
	Route::resource('user', 'UserController');

	//print routes
	Route::get('user/print', 'UserController@printAllUsers')->name('user.all.print');

	//pdf routes
	Route::get('user/pdf', 'UserController@pdfAllUsers')->name('user.all.pdf');

	//excel routes
	Route::get('user/excel', 'UserController@excelAllUsers')->name('user.all.excel');
});

Route::group(['middleware' => ['auth', 'role:super_admin|admin|operator']], function() {
	//resource routes
	Route::resource('product-category', 'ProductCategoryController');//done
	Route::resource('product', 'ProductController');//done
	Route::resource('stock-adjustment', 'StockAdjustmentController');
	Route::resource('supplier', 'SupplierController');//done
	Route::resource('purchase', 'PurchaseController');
	Route::resource('purchase-list', 'PurchaseListController');
	Route::resource('purchase-payment', 'PurchasePaymentController');//done
	Route::resource('expense-category', 'ExpenseCategoryController');//done
	Route::resource('expense', 'ExpenseController');//done
	Route::resource('patient', 'PatientController');//done
	Route::resource('doctor', 'DoctorController');//done
	Route::resource('test-category', 'TestCategoryController');//done
	Route::resource('test', 'TestController');//done
	Route::resource('commission', 'CommissionController');//done
	Route::resource('service', 'ServiceController');
	Route::resource('service-list', 'ServiceListController');
	Route::resource('service-payment', 'ServicePaymentController');
	Route::resource('doctor-payment', 'DoctorPaymentController');//done
	Route::resource('setting', 'SettingController');//done

	//to create a payment for a service
	Route::get('service-payment/create/{service}', 'ServicePaymentController@createFromService')->name('service-payment.createFromService');

	//server side datatable routes
	Route::post('product/server-side', 'ProductController@getProductList')->name('getProductList');
	Route::post('stock-adjustment/server-side', 'StockAdjustmentController@getAdjustmentList')->name('getAdjustmentList');
	Route::post('purchase/server-side', 'PurchaseController@getPurchaseList')->name('getPurchaseList');
	Route::post('purchase-payment/server-side', 'PurchasePaymentController@getPurchasePaymentList')->name('getPurchasePaymentList');
	Route::post('expense/server-side', 'ExpenseController@getExpenseList')->name('getExpenseList');
	Route::post('patient/server-side', 'PatientController@getPatientList')->name('getPatientList');
	Route::post('doctor/server-side', 'DoctorController@getDoctorList')->name('getDoctorList');
	Route::post('commission/server-side', 'CommissionController@getCommissionList')->name('getCommissionList');
	Route::post('service/server-side', 'ServiceController@getServiceList')->name('getServiceList');
	Route::post('service-payment/server-side', 'ServicePaymentController@getServicePaymentList')->name('getServicePaymentList');
	Route::post('doctor-payment/server-side', 'DoctorPaymentController@getDoctorPaymentList')->name('getDoctorPaymentList');
	Route::post('test/server-side', 'TestController@getTestList')->name('getTestList');

	//report routes
	Route::prefix('report')->group(function () {
		//date based stock adjustments
	    Route::get('stock-adjustment', 'StockAdjustmentController@returnPageView')->name('stock-adjustment.report.get');
	    Route::post('stock-adjustment', 'StockAdjustmentController@adjustmentsReport')->name('stock-adjustment.report');
	    //date based supplier report
		Route::get('supplier', 'SupplierController@returnPageView')->name('supplier.report.get');
		Route::post('supplier', 'SupplierController@supplierReport')->name('supplier.report');
		//date based purchase report
		Route::get('purchase', 'PurchaseController@returnPageView')->name('purchase.report.get');
		Route::post('purchase', 'PurchaseController@purchasesReport')->name('purchase.report');
		//date based purchase payment report
		Route::get('purchase-payment', 'PurchasePaymentController@returnPageView')->name('purchase-payment.report.get');
		Route::post('purchase-payment', 'PurchasePaymentController@purchasePaymentsReport')->name('purchase-payment.report');
		//date based expense report
		Route::get('expense', 'ExpenseController@returnPageView')->name('expense.report.get');
		Route::post('expense', 'ExpenseController@expensesReport')->name('expense.report');
        //date based doctor report
        Route::get('doctor', 'DoctorController@returnPageView')->name('doctor.report.get');
        Route::post('doctor', 'DoctorController@doctorReport')->name('doctor.report');
        //date based patient report
		Route::get('patient', 'PatientController@returnPageView')->name('patient.report.get');
		Route::post('patient', 'PatientController@patientReport')->name('patient.report');
		//date based test report
		Route::get('test', 'TestController@returnPageView')->name('test.report.get');
		Route::post('test', 'TestController@testReport')->name('test.report');
		//doctor based commission list
		Route::get('doctor-commission', 'CommissionController@returnPageView')->name('commission.doctor.get');
		Route::post('doctor-commission', 'CommissionController@doctorBasedCommission')->name('commission.doctor.report');
		//test based commission list
		Route::get('test-commission', 'CommissionController@returnPageView')->name('commission.test.get');
		Route::post('test-commission', 'CommissionController@testBasedCommission')->name('commission.test.report');
		//date based service report
		Route::get('service', 'ServiceController@returnPageView')->name('service.report.get');
		Route::post('service', 'ServiceController@allServicesReport')->name('service.report');
		//date based service payment report
		//Route::post('service-payment', 'ServicePaymentController@allServicePaymentsReport')->name('service-payment.report');
		//date based doctor payment report
		Route::post('doctor-payment', 'DoctorPaymentController@allPaymentsReport')->name('doctor-payment.report');
		//date and doctor based doctor payment report
		Route::post('doctor-payment/{doctor}', 'DoctorPaymentController@individualPaymentsReport')->name('doctor-payment.individual.report');
	});

	//print routes
	//product list
	Route::get('product/print', 'ProductController@printAllProducts')->name('product.all.print');
	//date based stock adjustments
	Route::post('stock-adjustment/print', 'StockAdjustmentController@printAllAdjustments')->name('stock-adjustment.all.print');
	//supplier list
	Route::get('supplier/print', 'SupplierController@printAllSuppliers')->name('supplier.all.print');
	//date based supplier report
	Route::post('supplier/report/print', 'SupplierController@printSupplierReport')->name('supplier.report.print');
	//date based purchase report
	Route::post('purchase/print', 'PurchaseController@printAllPurchases')->name('purchase.all.print');
	//a single purchase
	Route::get('purchase/print/{purchase}', 'PurchaseController@printPurchase')->name('purchase.print');
	//date based purchase payment report
	Route::post('purchase-payment/print', 'PurchasePaymentController@printAllPurchasePayments')->name('purchase-payment.all.print');
	//date based expense report
	Route::post('expense/print', 'ExpenseController@printAllExpenses')->name('expense.all.print');
	//date and category based expense report
	Route::post('expense/{category}/print', 'ExpenseController@printCategorizedExpenses')->name('expense.categorized.print');
	//patient list
	Route::get('patient/print', 'PatientController@printAllPatients')->name('patient.all.print');
	//date based patient report
	Route::post('patient/report/print', 'PatientController@printPatientReport')->name('patient.report.print');
	//doctor list
	Route::get('doctor/print', 'DoctorController@printAllDoctors')->name('doctor.all.print');
	//date based doctor report
	Route::post('doctor/report/print', 'DoctorController@printDoctorReport')->name('doctor.report.print');
	//test list
	Route::get('test/print', 'TestController@printAllTests')->name('test.all.print');
	//date based test report
	Route::post('test/report/print/{test}', 'TestController@printTestReport')->name('test.report.print');
	//doctor based commission list
	Route::get('commission/doctor/{doctor}/print', 'CommissionController@printDoctorBasedCommission')->name('commission.doctor.print');
	//test based commission list
	Route::get('commission/test/{test}/print', 'CommissionController@printTestBasedCommission')->name('commission.test.print');
	//date based service report
	Route::post('service/print', 'ServiceController@printAllServices')->name('service.all.print');
	//a single service
	Route::get('service/print/{service}', 'ServiceController@printService')->name('service.print');
	//date based service payment report
	Route::post('service-payment/print', 'ServicePaymentController@printAllServicePayments')->name('service-payment.all.print');
	//date based doctor payment report
	Route::post('doctor-payment/print', 'DoctorPaymentController@printAllPayments')->name('doctor-payment.all.print');
	//date and doctor based doctor payment report
	Route::post('doctor-payment/{doctor}/print', 'DoctorPaymentController@printIndividualPayments')->name('doctor-payment.individual.print');

	//pdf routes
	Route::get('product/pdf', 'ProductController@pdfAllProduct')->name('product.all.pdf');
	Route::post('stock-adjustment/pdf', 'StockAdjustmentController@pdfAllAdjustments')->name('stock-adjustment.all.pdf');
	Route::get('supplier/pdf', 'SupplierController@pdfAllSuppliers')->name('supplier.all.pdf');
	Route::post('supplier/report/pdf', 'SupplierController@pdfSupplierReport')->name('supplier.report.pdf');
	Route::post('purchase/pdf', 'PurchaseController@pdfAllPurchases')->name('purchase.all.pdf');
	Route::get('purchase/pdf/{purchase}', 'PurchaseController@pdfPurchase')->name('purchase.pdf');
	Route::post('purchase-payment/pdf', 'PurchasePaymentController@pdfAllPurchasePayments')->name('purchase-payment.all.pdf');
	Route::post('expense/pdf', 'ExpenseController@pdfAllExpenses')->name('expense.all.pdf');
	Route::post('expense/{category}/pdf', 'ExpenseController@pdfCategorizedExpenses')->name('expense.categorized.pdf');
	Route::get('patient/pdf', 'PatientController@pdfAllPatients')->name('patient.all.pdf');
	Route::post('patient/report/pdf', 'PatientController@pdfPatientReport')->name('patient.report.pdf');
	Route::get('doctor/pdf', 'DoctorController@pdfAllDoctors')->name('doctor.all.pdf');
	Route::post('doctor/report/pdf', 'DoctorController@pdfDoctorReport')->name('doctor.report.pdf');
	Route::get('test/pdf', 'TestController@pdfAllTests')->name('test.all.pdf');
	Route::post('test/report/pdf/{test}', 'TestController@pdfTestReport')->name('test.report.pdf');
	Route::get('commission/doctor/{doctor}/pdf', 'CommissionController@pdfDoctorBasedCommission')->name('commission.doctor.pdf');
	Route::get('commission/test/{test}/pdf', 'CommissionController@pdfTestBasedCommission')->name('commission.test.pdf');
	Route::post('service/pdf', 'ServiceController@pdfAllServices')->name('service.all.pdf');
	Route::get('service/pdf/{service}', 'ServiceController@pdfService')->name('service.pdf');
	Route::post('service-payment/pdf', 'ServicePaymentController@pdfAllServicePayments')->name('service-payment.all.pdf');
	Route::post('doctor-payment/pdf', 'DoctorPaymentController@pdfAllPayments')->name('doctor-payment.all.pdf');
	Route::post('doctor-payment/{doctor}/pdf', 'DoctorPaymentController@pdfIndividualPayments')->name('doctor-payment.individual.pdf');

	//excel routes
	Route::get('product/excel', 'ProductController@excelAllProduct')->name('product.all.excel');
	Route::post('stock-adjustment/excel', 'StockAdjustmentController@excelAllAdjustments')->name('stock-adjustment.all.excel');
	Route::get('supplier/excel', 'SupplierController@excelAllSuppliers')->name('supplier.all.excel');
	Route::post('supplier/report/excel', 'SupplierController@excelSupplierReport')->name('supplier.report.excel');
	Route::post('purchase/excel', 'PurchaseController@excelAllPurchases')->name('purchase.all.excel');
	Route::get('purchase/excel/{purchase}', 'PurchaseController@excelPurchase')->name('purchase.excel');
	Route::post('purchase-payment/excel', 'PurchasePaymentController@excelAllPurchasePayments')->name('purchase-payment.all.excel');
	Route::post('expense/excel', 'ExpenseController@excelAllExpenses')->name('expense.all.excel');
	Route::post('expense/{category}/excel', 'ExpenseController@excelCategorizedExpenses')->name('expense.categorized.excel');
	Route::get('patient/excel', 'PatientController@excelAllPatients')->name('patient.all.excel');
	Route::post('patient/report/excel', 'PatientController@excelPatientReport')->name('patient.report.excel');
	Route::get('doctor/excel', 'DoctorController@excelAllDoctors')->name('doctor.all.excel');
	Route::post('doctor/report/excel', 'DoctorController@excelDoctorReport')->name('doctor.report.excel');
	Route::get('test/excel', 'TestController@excelAllTests')->name('test.all.excel');
	Route::post('test/report/excel/{test}', 'TestController@excelTestReport')->name('test.report.excel');
	Route::get('commission/doctor/{doctor}/excel', 'CommissionController@excelDoctorBasedCommission')->name('commission.doctor.excel');
	Route::get('commission/test/{test}/excel', 'CommissionController@excelTestBasedCommission')->name('commission.test.excel');
	Route::post('service/excel', 'ServiceController@excelAllServices')->name('service.all.excel');
	Route::get('service/excel/{service}', 'ServiceController@excelService')->name('service.excel');
	Route::post('service-payment/excel', 'ServicePaymentController@excelAllServicePayments')->name('service-payment.all.excel');
	Route::post('doctor-payment/excel', 'DoctorPaymentController@excelAllPayments')->name('doctor-payment.all.excel');
	Route::post('doctor-payment/{doctor}/excel', 'DoctorPaymentController@excelIndividualPayments')->name('doctor-payment.individual.excel');

    //Graph
	Route::post('service-graph', 'ServiceController@serviceGraph')->name('service.graph');


	Route::post('purchase-product-unit/{product_id}', 'PurchaseController@purchaseProductUnit')->name('purchase.product.unit');
	Route::get('purchase-payment-createFromPurchase/{id}', 'PurchaseController@purchasePayment')->name('purchase-payment.createFromPurchase');
	Route::post('purchase.paymentStore', 'PurchasePaymentController@purchasePaymentStore')->name('purchase.paymentStore');
	Route::get('purchase-payment-edit/{id}', 'PurchasePaymentController@purchasePaymentEdit')->name('purchasePayment.edit');
	Route::post('purchase-payment-update', 'PurchasePaymentController@purchasePaymentUpdate')->name('purchase.paymentUpdate');
	Route::get('purchase-payment-destroy/{id}', 'PurchasePaymentController@purchasePaymentDestroy')->name('purchasePayment.destroy');

	Route::get('service-invoice-print/{id}', 'ServiceController@serviceInvoicePrint')->name('service.invoice.print');

});
