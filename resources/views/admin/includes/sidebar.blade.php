<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">
            <div class="pull-left">
                @if($user->profile_photo != '')
                    <img src="{{ Storage::disk('public')->url('profile/'.$user->profile_photo) }}" alt="user-image" class="thumb-md rounded-circle">
                @else
                    <img src="{{Storage::disk('public')->url('avatar.png')}}" alt="user-image" class="thumb-md rounded-circle">
                @endif
            </div>
            <div class="user-info">
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('admin/profile') }}" class="dropdown-item"><i class="md md-face-unlock mr-2"></i> Profile<div class="ripple-wrapper"></div></a></li>

                        @if(auth()->user()->role_id <= 2)
                        <li><a href="{{ url('admin/pharmacy/settings') }}" class="dropdown-item"><i class="md md-settings mr-2"></i> Settings</a></li>
                        @endif

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                <i class="md md-settings-power mr-2"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

                <p class="text-muted m-0">{{ Auth::user()->role->role_name }}</p>
            </div>
        </div>
        <!--- Divider -->
        <div id="sidebar-menu">
            @if (Request::is('admin*'))
            <ul>
                @if(Auth::user()->role_id <= 2)
                <li>
                    {{-- <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="md md-home"></i><span> Dashboard </span>
                    </a> --}}
                    <a href="#" class="waves-effect">
                        <i class="md md-home"></i>
                        <span> Dashboard </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.dashboard') }}"> Main </a></li>
                        <li><a href="{{ route('admin.branchwise') }}"> Branchwise </a></li>
                    </ul>
                </li>
                @else
                <li>
                    <a href="{{ route('dispenser.dashboard') }}" class="waves-effect">
                        <i class="md md-home"></i><span> Dashboard </span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->role_id == 1)
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-account-child"></i>
                        <span> Users </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.users.create') }}">Add User</a></li>
                        <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->role_id == 1)
                <li>
                    <a href="{{ route('admin.role') }}" class="waves-effect">
                        <i class="md md-label"></i><span> Role </span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->role_id == 1)
                    <li class="has_sub">
                        <a href="#" class="waves-effect">
                            <i class="md md-account-balance"></i>
                            <span> Branches </span>
                            <span class="pull-right"><i class="md md-add"></i></span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('admin.branches.create') }}"> Add Branch</a></li>
                            <li><a href="{{ route('admin.branches.index') }}"> All Branches</a></li>
                        </ul>
                    </li>
                @endif
                @if(auth()->user()->role_id > 2)
                <li>
                    <a href="{{ route('admin.invoices.create') }}" class="waves-effect">
                        <i class="md md-payment"></i><span> POS </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-receipt"></i>
                        <span> Invoices </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.invoices.create') }}"> Add Invoice</a></li>
                        <li><a href="{{ route('admin.invoices.index') }}"> All Invoices</a></li>
                    </ul>
                </li>
                @endif
                @if(auth()->user()->role_id <= 2)
                <li>
                    <a href="{{ route('admin.invoices.index') }}" class="waves-effect">
                        <i class="md md-payment"></i><span> Invoices </span>
                    </a>
                </li>
                @endif
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-book"></i>
                        <span> Sales </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.invoice.sale') }}"> All Sales</a></li>
                        <li><a href="{{ route('admin.invoice.received') }}"> Received </a></li>
                        <li><a href="{{ route('admin.invoice.due') }}"> Due </a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-aspect-ratio"></i>
                        <span> Medicines </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.medcategories.create') }}"> Add Category</a></li>
                        <li><a href="{{ route('admin.medcategories.index') }}"> All Categories</a></li>
                        <li><a href="{{ route('admin.medicines.create') }}"> Add Medicine</a></li>
                        <li><a href="{{ route('admin.medicines.index') }}"> All Medicines</a></li>
                        <li><a href="{{ route('admin.get.csv.import') }}"> Import Medicines (CSV)</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-games"></i>
                        <span> Manufacturer </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.manufacturers.create') }}"> Add Manufacturer</a></li>
                        <li><a href="{{ route('admin.manufacturers.index') }}"> All Manufacturers</a></li>
                    </ul>
                </li>

                @if(auth()->user()->role_id > 2)
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-pages"></i>
                        <span> Refunds </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.refund.add') }}"> Add Refund</a></li>
                        <li><a href="{{ route('admin.refunds.index') }}"> All Refunds</a></li>
                    </ul>
                </li>
                @endif
                @if(auth()->user()->role_id <= 2)
                <li>
                    <a href="{{ route('admin.refunds.index') }}" class="waves-effect">
                        <i class="md md-pages"></i><span> Refunds </span>
                    </a>
                </li>
                @endif
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-wallet-membership"></i>
                        <span> Purchase </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        @if(auth()->user()->role_id > 2)
                        <li><a href="{{ route('admin.purchases.create') }}"> Add Purchase</a></li>
                        @endif
                        <li><a href="{{ route('admin.purchases.index') }}"> All Purchases</a></li>
                        <li><a href="{{ route('admin.purchases.due') }}"> Due Purchases</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.damaged.index') }}" class="waves-effect">
                        <i class="md md-remove-circle"></i><span> Damage </span>
                    </a>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-my-library-books"></i>
                        <span> Stock </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.stock.stockReport') }}"> Stock Report </a></li>
                        <li><a href="{{ route('admin.stock.out') }}"> Out of Stock </a></li>
                        <li><a href="{{ route('admin.stock.expired') }}"> Expired Medicine </a></li>
                        <li><a href="{{ route('admin.stock.expired.soon') }}"> Expires Within 30 Days </a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-forum"></i>
                        <span> Reports </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.reports.profitMonth') }}"> Profit Statement</a></li>
                        <li><a href="{{ route('admin.reports.saleToday') }}"> Sale Report</a></li>
                        <li><a href="{{ route('admin.reports.purchaseToday') }}"> Purchase Report</a></li>
                        <li><a href="{{ route('admin.reports.refundToday') }}"> Refund Report</a></li>
                        <li><a href="{{ route('admin.reports.expenseToday') }}"> Expense Report</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-account-circle"></i>
                        <span> Customers </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.customers.create') }}"> Add Customer</a></li>
                        <li><a href="{{ route('admin.customers.index') }}"> All Customers</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-face-unlock"></i>
                        <span> Staffs </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.staffs.create') }}"> Add Staff</a></li>
                        <li><a href="{{ route('admin.staffs.index') }}"> All Staffs</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-perm-contact-cal"></i>
                        <span> Suppliers </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.suppliers.create') }}"> Add Supplier</a></li>
                        <li><a href="{{ route('admin.suppliers.index') }}"> All Suppliers</a></li>
                    </ul>
                </li>
                
                @if(Auth::user()->role_id <= 2)
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-view-stream"></i>
                        <span> Expenses </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.expensecategories.create') }}"> Add Category</a></li>
                        <li><a href="{{ route('admin.expensecategories.index') }}"> All Categories</a></li>
                        <li><a href="{{ route('admin.expenses.create') }}"> Add Expense</a></li>
                        <li><a href="{{ route('admin.expenses.index') }}"> All Expenses</a></li>
                    </ul>
                </li>
                @endif

                @if(Auth::user()->role_id > 2)
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-view-stream"></i>
                        <span> Expenses </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.expenses.create') }}"> Add Expense</a></li>
                        <li><a href="{{ route('admin.expenses.index') }}"> All Expenses</a></li>
                    </ul>
                </li>
                @endif

                <li>
                    <a href="{{ route('admin.profile.index') }}" class="waves-effect">
                        <i class="md md-perm-identity"></i><span> Profile </span>
                    </a>
                </li>

                @if(Auth::user()->role_id <= 2)
                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="md md-settings"></i>
                        <span> Settings </span>
                        <span class="pull-right"><i class="md md-add"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.pharmacy.setting') }}">Pharmacy Settings</a></li>
                        <li><a href="{{ route('admin.web.setting') }}">Web Settings</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->role_id == 1)
                    <li>
                        <a href="{{ route('admin.recycle') }}" class="waves-effect">
                            <i class="md md-delete"></i><span> Recycle Bin </span>
                        </a>
                    </li>
                @endif
               
                <li>
                    <a class="waves-effect" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        <i class="md md-settings-power"></i><span> Logout </span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            <div class="clearfix"></div>
            @endif            
        </div>
        <div class="clearfix"></div>
    </div>
</div>