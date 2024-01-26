<?php

namespace App\Http\Controllers;

use App\User;
use App\Staff;
use App\Branch;
use App\Refund;
use App\Expense;
use App\Invoice;
use App\Customer;
use App\Medicine;
use App\Purchase;
use App\Supplier;
use App\Manufacturer;
use App\ExpenseCategory;
use App\MedicineCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecycleController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function index() {
        $allUser = User::onlyTrashed()->count();
        $allBranch = Branch::onlyTrashed()->count();
        $allSupplier = Supplier::onlyTrashed()->count();
        $allMedCategory = MedicineCategory::onlyTrashed()->count();
        $allManufacturer = Manufacturer::onlyTrashed()->count();
        $allMedicine = Medicine::onlyTrashed()->count();
        $allPurchase = Purchase::onlyTrashed()->count();
        $allCustomer = Customer::onlyTrashed()->count();
        $allInvoice = Invoice::onlyTrashed()->count();
        $allRefund = Refund::onlyTrashed()->count();
        $allStaff = Staff::onlyTrashed()->count();
        $allExpCategory = ExpenseCategory::onlyTrashed()->count();
        $allExpense = Expense::onlyTrashed()->count();
        return view('recycle.index', compact('allUser', 'allBranch', 'allSupplier', 'allMedCategory', 'allManufacturer', 'allMedicine', 'allPurchase', 'allCustomer', 'allInvoice', 'allRefund', 'allStaff', 'allExpCategory', 'allExpense'));
    }


    public function user(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = User::orderBy('id', 'DESC')->onlyTrashed()->get(['users.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->email;
                        })
                        ->addColumn('role', function($data) {
                            return $data->role->role_name;
                        })
                        ->addColumn('photo', function($data) {
                            if($data->photo != '')
                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('users/'.$data->photo).'" alt="user-photo"/>';

                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('avatar.png').'" alt="user-photo"/>';
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="id[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.user.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.user.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })
                        
                        ->rawColumns(['action', 'photo'])
                        ->make(true);
        }
        return view('recycle.recycle-users');
    }

    public function branch(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Branch::orderBy('id', 'DESC')->onlyTrashed()->get(['branches.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                        return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('title', function($data) {
                            return $data->branch_title;
                        })
                        ->addColumn('code', function($data) {
                            return $data->branch_code;
                        })
                        ->addColumn('dispenser', function($data) {
                            return $data->user->name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->branch_phone;
                        })
                        ->addColumn('address', function($data) {
                            return $data->branch_address;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="id[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.branch.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.branch.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })
                        
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('recycle.recycle-branches');
    }

    public function supplier(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Supplier::orderBy('id', 'DESC')->onlyTrashed()->get(['suppliers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->sup_name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->sup_phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->sup_email;
                        })
                        ->addColumn('address', function($data) {
                            return $data->sup_address;
                        })
                        ->addColumn('note', function($data) {
                            return $data->sup_note;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.supplier.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.supplier.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('recycle.recycle-suppliers');
    }


    public function medcategory(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = MedicineCategory::orderBy('id', 'ASC')->onlyTrashed()->get(['medicine_categories.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                    return DataTables::of($data)
                    ->addColumn('sl', function($data) {
                        return $data->rownum;
                    })
                    ->addColumn('name', function($data) {
                        return $data->cate_name;
                    })
                    ->addColumn('note', function($data) {
                        return $data->cate_note;
                    })

                    ->addColumn('action', function($data){
                        $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medcategory.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medcategory.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                        
                        return $a;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
                    // ->toJson();
        }
        return view('recycle.recycle-medicine-category');
    }


    public function manufacturer(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Manufacturer::orderBy('id', 'ASC')->onlyTrashed()->get(['manufacturers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                    return DataTables::of($data)
                    ->addColumn('sl', function($data) {
                        return $data->rownum;
                    })
                    ->addColumn('name', function($data) {
                        return $data->manu_name;
                    })
                    ->addColumn('note', function($data) {
                        return $data->manu_note;
                    })

                    ->addColumn('action', function($data){
                        $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.manufacturer.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.manufacturer.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                        
                        return $a;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
                    // ->toJson();
        }
        return view('recycle.recycle-manufacturer');
    }

    public function medicine(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Medicine::orderBy('id', 'DESC')->onlyTrashed()->get(['medicines.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                    ->addColumn('sl', function($data) {
                        return $data->rownum;
                    })
                    ->addColumn('name', function($data) {
                        return $data->med_name;
                    })
                    ->addColumn('generic name', function($data) {
                        return $data->generic_name;
                    })
                    ->addColumn('category', function($data) {
                        return $data->category->cate_name;
                    })
                    ->addColumn('manufacturer', function($data) {
                        return $data->manufacturer->manu_name;
                    })
                    ->addColumn('note', function($data) {
                        return $data->note;
                    })

                    ->addColumn('action', function($data){
                        $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medicine.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';
                      
                        $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.medicine.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';

                        return $a;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('recycle.recycle-medicines');
    }


    public function purchase(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        
        if($request->ajax()) {
            $data = Purchase::orderBy('id', 'DESC')->onlyTrashed()->get(['purchases.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('purchase invoice id', function($data) {
                            return $data->purchase_invoice_id;
                        })
                        ->addColumn('added by', function($data) {
                            return $data->user->name;
                        })
                        ->addColumn('purchase date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('supplier', function($data) {
                            return $data->supplier->sup_name;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->purchase_total;
                        })
                        ->addColumn('note', function($data) {
                            return $data->purchase_note;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';
                      
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.purchase.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                         
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('recycle.recycle-purchases');
    }


    public function customer(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Customer::orderBy('id', 'DESC')->onlyTrashed()->get(['customers.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->customer_name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->customer_phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->customer_email;
                        })
                        ->addColumn('added by', function($data) {
                            return $data->user->name ?: null;
                        })
                        ->addColumn('addrress', function($data) {
                            return $data->customer_address;
                        })
                        ->addColumn('note', function($data) {
                            return Str::words($data->customer_note, 3);
                        })

                        ->addColumn('action', function($data){
                                $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.customer.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';
                      
                                $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.customer.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;

                        })

                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('recycle.recycle-customers');
    }

    public function invoice(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        
        if($request->ajax()) {
            $data = Invoice::orderBy('id', 'DESC')->onlyTrashed()->get(['invoices.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('invoice date', function($data) {
                            return $data->purchase_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->invoice_total;
                        })
                        ->addColumn('discount', function($data) {
                            return $data->invoice_discount;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->invoice_received;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->invoice_nettotal;
                        })
                        ->addColumn('due', function($data) {
                            return $data->invoice_due;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';
                      
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.invoice.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                         
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('recycle.recycle-invoices');
    }


    public function refund(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        
        if($request->ajax()) {
            $data = Refund::orderBy('id', 'DESC')->onlyTrashed()->get(['refunds.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('refund date', function($data) {
                            return $data->refund_date;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('customer', function($data) {
                            return $data->customer->customer_name ?? null;
                        })
                        ->addColumn('payment type', function($data) {
                            return $data->payment_type;
                        })
                        ->addColumn('total', function($data) {
                            return $data->refund_total;
                        })
                        ->addColumn('cut', function($data) {
                            return $data->refund_cut;
                        })
                        ->addColumn('net total', function($data) {
                            return $data->refund_nettotal;
                        })
                        ->addColumn('paid', function($data) {
                            return $data->refund_paid;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="ids[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.refund.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';
                      
                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.refund.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                         
                            
                            return $a;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
                        // ->toJson();
        }
        return view('recycle.recycle-refunds');
    }


    public function staff(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Staff::orderBy('id', 'DESC')->onlyTrashed()->get(['staffs.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);

                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->name;
                        })
                        ->addColumn('phone', function($data) {
                            return $data->phone;
                        })
                        ->addColumn('email', function($data) {
                            return $data->email;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('photo', function($data) {
                            if($data->photo != '')
                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('staffs/'.$data->photo).'" alt="user-photo"/>';

                                return '<img class="table-image-40" src="'.Storage::disk('public')->url('avatar.png').'" alt="user-photo"/>';
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="id[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.staff.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.staff.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })
                        
                        ->rawColumns(['action', 'photo'])
                        ->make(true);
        }
        return view('recycle.recycle-staffs');
    }



    public function expensecategory(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = ExpenseCategory::onlyTrashed()->orderBy('id', 'ASC')->get(['expense_categories.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('name', function($data) {
                            return $data->expcate_name;
                        })
                        ->addColumn('remarks', function($data) {
                            return $data->expcate_remarks;
                        })
                        ->addColumn('creator', function($data) {
                            return $data->user->name;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="id[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expensecategory.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expensecategory.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })
                        
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('recycle.recycle-expensecategory');
    }


    public function expense(Request $request) {
        DB::statement(DB::raw('set @rownum=0'));
        
        if($request->ajax()) {
            $data = Expense::onlyTrashed()->orderBy('id', 'ASC')->get(['expenses.*', 
                    DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
            
                return DataTables::of($data)
                        ->addColumn('sl', function($data) {
                            return $data->rownum;
                        })
                        ->addColumn('branch', function($data) {
                            return $data->branch->branch_code;
                        })
                        ->addColumn('expense details', function($data) {
                            return $data->expense_details;
                        })
                        ->addColumn('expensecategory_id', function($data) {
                            return $data->expenseCategory->expcate_name;
                        })
                        ->addColumn('date', function($data) {
                            return $data->expense_date;
                        })
                        ->addColumn('amount', function($data) {
                            return $data->expense_amount;
                        })

                        ->addColumn('action', function($data){
                            $a = '<div class="custom-control custom-checkbox d-inline"><input type="checkbox" name="id[]" class="delete-checkbox custom-control-input" id="horizontalCheckbox'.$data->id.'" value="'.$data->id.'"><label class="custom-control-label" for="horizontalCheckbox'.$data->id.'"></label></div>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expense.restore') .'" data-id="'.$data->id .'" class="btn-restore"><i class="fa fa-refresh fa-lg edit-icon"></i></a>';

                            $a .= '&nbsp;&nbsp;<a href="#" data-url="'. route('admin.expense.forcedelete') .'" data-id="'.$data->id .'" class="btn-delete"><i class="fa fa-trash fa-lg delete-icon"></i></a>';
                            
                            return $a;
                        })
                        
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('recycle.recycle-expense');
    }
    
}
