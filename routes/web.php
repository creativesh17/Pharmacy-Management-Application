<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\OtherDeviceLogout;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// users
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth']], function() {
    
    // dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    //BranchwiseSearch
    Route::get('/branchwise', 'DashboardController@branchwise')->name('branchwise');
    Route::get('/branchwise/search', 'DashboardController@branchwiseSearch')->name('branchwise.search');
    
    // users
    Route::get('/changeuserstatus', 'UserController@changeStatus')->name('user.status');
    Route::get('/user/restore', 'UserController@restore')->name('user.restore');
    Route::delete('/user/forcedelete', 'UserController@forceDelete')->name('user.forcedelete');
    Route::delete('/user/destroy', 'UserController@destroy')->name('user.destroy');
    Route::resource('/users', 'UserController')->except('destroy');


    // roles
    Route::get('/roles', 'RoleController@role')->name('role');
    Route::get('/roles/{id}/edit', 'RoleController@edit')->name('role.edit');
    Route::put('/roles/{id}', 'RoleController@update')->name('role.update');

    // profile
    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::put('profile/update', 'ProfileController@updateProfile')->name('profile.update');
    Route::put('password/update', 'ProfileController@updatePassword')->name('password.update');

    // branches
    Route::get('/autosearch', 'BranchController@autosearch')->name('autosearch');
    Route::get('/changebranchstatus', 'BranchController@changeStatus')->name('branch.status');
    Route::get('/branch/restore', 'BranchController@restore')->name('branch.restore');
    Route::delete('/branch/forcedelete', 'BranchController@forceDelete')->name('branch.forcedelete');
    Route::delete('/branch/destroy', 'BranchController@destroy')->name('branch.destroy');
    Route::resource('/branches', 'BranchController')->except('destroy');

    // settings
    Route::get('/pharmacy/settings', 'SettingController@pharmacysetting')->name('pharmacy.setting');
    Route::post('/pharmacy/settings/update', 'SettingController@update_pharmacysetting')->name('pharmacy.setting.update');
    Route::get('/web/settings', 'SettingController@websetting')->name('web.setting');
    Route::post('/web/settings/update', 'SettingController@update_websetting')->name('web.setting.update');

    // suppliers
    Route::get('/changesupplierstatus', 'SupplierController@changeStatus')->name('supplier.status');
    Route::get('/supplier/restore', 'SupplierController@restore')->name('supplier.restore');
    Route::delete('/supplier/forcedelete', 'SupplierController@forceDelete')->name('supplier.forcedelete');
    Route::delete('/supplier/destroy', 'SupplierController@destroy')->name('supplier.destroy');
    Route::resource('/suppliers', 'SupplierController')->except(['show', 'destroy']);

    // medicine category
    Route::get('/medcategory/restore', 'MedicineCategoryController@restore')->name('medcategory.restore');
    Route::delete('/medcategory/forcedelete', 'MedicineCategoryController@forceDelete')->name('medcategory.forcedelete');
    Route::delete('/medcategory/destroy', 'MedicineCategoryController@destroy')->name('medcategory.destroy');
    Route::resource('/medcategories', 'MedicineCategoryController')->except(['show', 'destroy']);


    // manufacturers
    Route::get('/manufacturer/restore', 'ManufacturerController@restore')->name('manufacturer.restore');
    Route::delete('/manufacturer/forcedelete', 'ManufacturerController@forceDelete')->name('manufacturer.forcedelete');
    Route::delete('/manufacturer/destroy', 'ManufacturerController@destroy')->name('manufacturer.destroy');
    Route::resource('/manufacturers', 'ManufacturerController')->except(['show', 'destroy']);

    // medicines
    Route::get('/changemedicinestatus', 'MedicineController@changeStatus')->name('medicine.status');
    Route::get('/medicine/restore', 'MedicineController@restore')->name('medicine.restore');
    Route::delete('/medicine/forcedelete', 'MedicineController@forceDelete')->name('medicine.forcedelete');
    Route::delete('/medicine/destroy', 'MedicineController@destroy')->name('medicine.destroy');
    Route::resource('/medicines', 'MedicineController')->except('destroy');

    // medicine import csv file
    Route::get('csv/import', 'MedicineController@getImportCsv')->name('get.csv.import');
    Route::post('csv/import', 'MedicineController@postImportCsv')->name('csv.import');

    // purchases
    Route::get('/purchase/supplierautosearch', 'PurchaseController@supplierautosearch')->name('purchase.supplier.autosearch');
    Route::get('/purchase/medicineautosearch', 'PurchaseController@medicineautosearch')->name('purchase.medicine.autosearch');
    Route::get('/purchases/due', 'PurchaseController@due')->name('purchases.due');
    Route::get('/purchase/restore', 'PurchaseController@restore')->name('purchase.restore');
    Route::delete('/purchase/forcedelete', 'PurchaseController@forceDelete')->name('purchase.forcedelete');
    Route::delete('/purchase/destroy', 'PurchaseController@destroy')->name('purchase.destroy');
    Route::resource('/purchases', 'PurchaseController')->except(['destroy']);


    // customers
    Route::get('/customer/restore', 'CustomerController@restore')->name('customer.restore');
    Route::delete('/customer/forcedelete', 'CustomerController@forceDelete')->name('customer.forcedelete');
    Route::delete('/customer/destroy', 'CustomerController@destroy')->name('customer.destroy');
    Route::resource('/customers', 'CustomerController')->except(['destroy']);


    // invoices
    Route::get('/invoice/index2', 'InvoiceController@index2')->name('invoice.index2');
    Route::get('/invoice/customerautosearch', 'InvoiceController@customerautosearch')->name('invoice.supplier.autosearch');
    Route::get('/invoice/medicineautosearch', 'InvoiceController@medicineautosearch')->name('invoice.medicine.autosearch');
    Route::get('/invoice/medicineinfo', 'InvoiceController@medicineinfo')->name('invoice.medicine.medicineinfo');
    Route::get('/invoice/sale', 'InvoiceController@sale')->name('invoice.sale');
    Route::get('/invoice/received', 'InvoiceController@received')->name('invoice.received');
    Route::get('/invoice/due', 'InvoiceController@due')->name('invoice.due');
    Route::get('/invoice/restore', 'InvoiceController@restore')->name('invoice.restore');
    Route::delete('/invoice/forcedelete', 'InvoiceController@forceDelete')->name('invoice.forcedelete');
    Route::delete('/invoice/destroy', 'InvoiceController@destroy')->name('invoice.destroy');
    Route::resource('/invoices', 'InvoiceController')->except(['destroy']);

    // refunds
    Route::get('/refund/add', 'RefundController@add')->name('refund.add');
    Route::get('/refund/create', 'RefundController@create')->name('refund.create');
    Route::get('/refund/restore', 'RefundController@restore')->name('refund.restore');
    Route::delete('/refund/forcedelete', 'RefundController@forceDelete')->name('refund.forcedelete');
    Route::delete('/refund/destroy', 'RefundController@destroy')->name('refund.destroy');
    Route::resource('/refunds', 'RefundController')->except(['create', 'destroy']);

    // staffs
    Route::get('/changestaffstatus', 'StaffController@changeStatus')->name('staff.status');
    Route::get('/staff/restore', 'StaffController@restore')->name('staff.restore');
    Route::delete('/staff/forcedelete', 'StaffController@forceDelete')->name('staff.forcedelete');
    Route::delete('/staff/destroy', 'StaffController@destroy')->name('staff.destroy');
    Route::resource('/staffs', 'StaffController')->except('destroy');


    // expense categories
    Route::get('/expensecategory/restore', 'ExpenseCategoryController@restore')->name('expensecategory.restore');
    Route::delete('/expensecategory/forcedelete', 'ExpenseCategoryController@forceDelete')->name('expensecategory.forcedelete');
    Route::delete('/expensecategory/destroy', 'ExpenseCategoryController@destroy')->name('expensecategory.destroy');
    Route::resource('/expensecategories', 'ExpenseCategoryController')->except(['destroy', 'show']);

    // expenses
    Route::get('/expense/restore', 'ExpenseController@restore')->name('expense.restore');
    Route::delete('/expense/forcedelete', 'ExpenseController@forceDelete')->name('expense.forcedelete');
    Route::delete('/expense/destroy', 'ExpenseController@destroy')->name('expense.destroy');
    Route::resource('/expenses', 'ExpenseController')->except(['destroy']);

    // damaged
    Route::get('/damaged', 'DamagedController@damaged')->name('damaged');
    Route::get('/damaged/medicineautosearch', 'DamagedController@medicineautosearch')->name('damaged.medicineautosearch');
    Route::post('/damaged/create', 'DamagedController@createDamage')->name('damaged.create');
    Route::get('/damaged/search', 'DamagedController@searchDamage')->name('damaged.search');
    Route::get('/damaged/index', 'DamagedController@index')->name('damaged.index');


    // stocks
    Route::get('/stock/report', 'StockController@stockReport')->name('stock.stockReport');
    Route::get('/stock/report/search', 'StockController@stockReportSearch')->name('stock.stockReport.search');
    Route::get('/stockout', 'StockController@stockOut')->name('stock.out');
    Route::get('/stockout/search', 'StockController@stockOutSearch')->name('stock.out.search');
    Route::get('/stock/expired', 'StockController@expired')->name('stock.expired');
    Route::get('/stock/expired/search', 'StockController@expiredSearch')->name('stock.expired.search');
    Route::get('/stock/expired-soon', 'StockController@expiredSoon')->name('stock.expired.soon');
    Route::get('/stock/expired-soon/search', 'StockController@expiredSoonSearch')->name('stock.expired-soon.search');


    // reports
    Route::get('/reports/sales', 'ReportController@saleToday')->name('reports.saleToday');
    Route::get('/reports/sale/search', 'ReportController@saleSearch')->name('reports.salesearch');
    Route::get('/reports/purchases', 'ReportController@purchaseToday')->name('reports.purchaseToday');
    Route::get('/reports/purchase/search', 'ReportController@purchaseSearch')->name('reports.purchasesearch');
    Route::get('/reports/refunds', 'ReportController@refundToday')->name('reports.refundToday');
    Route::get('/reports/refund/search', 'ReportController@refundSearch')->name('reports.refundsearch');
    Route::get('/reports/expenses', 'ReportController@expenseToday')->name('reports.expenseToday');
    Route::get('/reports/expense/search', 'ReportController@expenseSearch')->name('reports.expensesearch');
    Route::get('/reports/profits', 'ReportController@profitMonth')->name('reports.profitMonth');
    Route::get('/reports/profit/search', 'ReportController@profitSearch')->name('reports.profitSearch');


    Route::get('/recycle', 'RecycleController@index')->name('recycle');
    Route::get('/recycle/users', 'RecycleController@user')->name('recycle.users');
    Route::get('/recycle/branches', 'RecycleController@branch')->name('recycle.branches');
    Route::get('/recycle/suppliers', 'RecycleController@supplier')->name('recycle.suppliers');
    Route::get('/recycle/medcategories', 'RecycleController@medcategory')->name('recycle.medcategories');
    Route::get('/recycle/manufacturers', 'RecycleController@manufacturer')->name('recycle.manufacturers');
    Route::get('/recycle/medicines', 'RecycleController@medicine')->name('recycle.medicines');
    Route::get('/recycle/purchases', 'RecycleController@purchase')->name('recycle.purchases');
    Route::get('/recycle/customers', 'RecycleController@customer')->name('recycle.customers');
    Route::get('/recycle/invoices', 'RecycleController@invoice')->name('recycle.invoices');
    Route::get('/recycle/refunds', 'RecycleController@refund')->name('recycle.refunds');
    Route::get('/recycle/staffs', 'RecycleController@staff')->name('recycle.staffs');
    Route::get('/recycle/expensecategories', 'RecycleController@expensecategory')->name('recycle.expensecategories');
    Route::get('/recycle/expenses', 'RecycleController@expense')->name('recycle.expenses');
});

// Route::post('/admin/autosearch', 'BranchController@autosearch')->name('admin.autosearch');
// Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth']], function() {    

// });

Route::group(['namespace' => 'Dispenser', 'middleware' => ['auth', 'dispenser']], function() {
    Route::get('/admin/dispenser', 'DashboardController@index')->name('dispenser.dashboard');
});


