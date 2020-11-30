<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    
    <div class="scrollbar-sidebar" style="overflow-y: scroll;">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="{{ \Request::is('home') ? 'mm-active' : ''  }}">
                    <a href="{{ route('home') }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>

                @role('super_admin')
                <li class="{{ \Request::is('service/*') || \Request::is('service') ? 'mm-active' : ''  }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-bandaid"></i>
                        Service
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('service.index') }}" class="{{ \Request::is('service') ? 'mm-active' : ''  }}">
                                <i class="metismenu-icon"></i>
                                Services
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{ route('service.create') }}" class="{{ \Request::is('service/create') ? 'mm-active' : ''  }}">
                                <i class="metismenu-icon"></i>
                                Create
                            </a>
                        </li>

                        <li class="{{ \Request::is('patient/*') || \Request::is('patient') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Patient
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('patient/*') || \Request::is('patient') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('patient.index') }}" class="{{ \Request::is('patient') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Patients
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('patient.create') }}" class="{{ \Request::is('patient/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('patient.report.get') }}" class="{{ \Request::is('patient') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon"></i>
                                        Report
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ \Request::is('doctor/*') || \Request::is('doctor') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Doctor
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('doctor/*') || \Request::is('doctor') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('doctor.index') }}" class="{{ \Request::is('doctor') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Doctors
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('doctor.create') }}" class="{{ \Request::is('doctor/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create
                                    </a>
                                </li>
                                <li class="{{ \Request::is('report/doctor-commission') || \Request::is('report/test-commission') ? 'mm-active' : '' }}">
                                    <a href="#">
                                        <i class="metismenu-icon"></i>
                                        Commission
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ \Request::is('report/doctor-commission') || \Request::is('report/test-commission') ? 'mm-show' : '' }}">
                                        <li>
                                            <a href="{{ route('commission.doctor.get') }}" class="{{ \Request::is('report/doctor-commission') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Doctor
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('commission.test.get') }}" class="{{ \Request::is('report/test-commission') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Test
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="{{ \Request::is('doctor-payment/*') || \Request::is('doctor-payment') ? 'mm-active' : '' }}">
                                    <a href="#">
                                        <i class="metismenu-icon"></i>
                                        Payment
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ \Request::is('doctor-payment/*') || \Request::is('doctor-payment') ? 'mm-show' : '' }}">
                                        <li>
                                            <a href="{{ route('doctor-payment.index') }}" class="{{ \Request::is('doctor-payment') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>History
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('doctor-payment.create') }}" class="{{ \Request::is('doctor-payment/create') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>Create
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>

                <li @if (str_contains(url()->current(), 'supplier') || str_contains(url()->current(), 'product-category') ||   str_contains(url()->current(), 'purchase') && !str_contains(url()->current(), 'report')) class="mm-active" @endif>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-shopbag"></i>
                        Manage Inventory
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul @if (str_contains(url()->current(), 'purchase')  && !str_contains(url()->current(), 'report')) class="mm-show" @endif>
                        <li class="{{ \Request::is('purchase/*') || \Request::is('purchase') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Purchase
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('purchase/*') || \Request::is('purchase') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('purchase.index') }}" class="{{ \Request::is('purchase') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>History
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase.create') }}" class="{{ \Request::is('purchase/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>New Purchases
                                    </a>
                                </li>

                                <li class="{{ \Request::is('purchase-payment/*') || \Request::is('purchase-payment') ? 'mm-active' : '' }}">
                                    <a href="#">
                                        <i class="metismenu-icon"></i>
                                         Payment
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ \Request::is('purchase-payment/*') || \Request::is('purchase-payment') ? 'mm-show' : '' }}">
                                        <li>
                                            <a href="{{ route('purchase-payment.index') }}" class="{{ \Request::is('purchase-payment') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>History
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('purchase-payment.create') }}" class="{{ \Request::is('purchase-payment/create') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>Make Payment
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ \Request::is('product/*') || \Request::is('product') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Product
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('product/*') || \Request::is('product') ? 'mm-show' : '' }}">
                                <li class="{{ \Request::is('product-category/*') || \Request::is('product-category') ? 'mm-active' : '' }}">
                                    <a href="#">
                                        <i class="metismenu-icon"></i>
                                        Product Category
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ \Request::is('product-category/*') || \Request::is('product-category') ? 'mm-show' : '' }}">
                                        <li>
                                            <a href="{{ route('product-category.index') }}" class="{{ \Request::is('product-category') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>Category List
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('product-category.create') }}" class="{{ \Request::is('product-category/create') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>New Categotry
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="{{ route('product.index') }}" class="{{ \Request::is('product') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Product List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.create') }}" class="{{ \Request::is('product/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>New Product
                                    </a>
                                </li>

                                <li class="{{ \Request::is('stock-adjustment/*') || \Request::is('stock-adjustment') ? 'mm-active' : '' }}">
                                    <a href="#">
                                        <i class="metismenu-icon"></i>
                                        Stock Adjustment
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ \Request::is('stock-adjustment/*') || \Request::is('stock-adjustment') ? 'mm-show' : '' }}">
                                        <li>
                                            <a href="{{ route('stock-adjustment.index') }}" class="{{ \Request::is('stock-adjustment') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>History
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('stock-adjustment.create') }}" class="{{ \Request::is('stock-adjustment/create') ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon">
                                                </i>Make Adjustment
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ \Request::is('supplier/*') || \Request::is('supplier') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Supplier
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('supplier/*') || \Request::is('supplier') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('supplier.index') }}" class="{{ \Request::is('supplier') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Supplier List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.create') }}" class="{{ \Request::is('supplier/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>New Supplier
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supplier.report.get') }}" class="{{ \Request::is('supplier/report') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Supplier Report
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li @if (str_contains(url()->current(), 'expense') && !str_contains(url()->current(), 'report')) class="mm-active" @endif>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-box1"></i>
                        Manage Expense
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul @if (str_contains(url()->current(), 'expense') && !str_contains(url()->current(), 'report')) class="mm-show" @endif>
                        <li class="{{ \Request::is('expense-category/*') || \Request::is('expense-category') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Expense Category
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('expense-category/*') || \Request::is('expense-category') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('expense-category.index') }}" class="{{ \Request::is('expense-category') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Expense Category List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('expense-category.create') }}" class="{{ \Request::is('expense-category/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create Expense category
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ \Request::is('expense/*') || \Request::is('expense') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Expense
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('expense/*') || \Request::is('expense') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('expense.index') }}" class="{{ \Request::is('expense') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>History
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('expense.create') }}" class="{{ \Request::is('expense/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create Expense
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li @if (str_contains(url()->current(), 'test') && !str_contains(url()->current(), 'report')) class="mm-active" @endif>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-exapnd2"></i>
                        Tests
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul @if (str_contains(url()->current(), 'test') && !str_contains(url()->current(), 'report')) class="mm-show" @endif>
                        <li class="{{ \Request::is('test-category/*') || \Request::is('test-category') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Category
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('test-category/*') || \Request::is('test-category') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('test-category.index') }}" class="{{ \Request::is('test-category') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Categories
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('test-category.create') }}" class="{{ \Request::is('test-category/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ \Request::is('test/*') || \Request::is('test') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Tests
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('test/*') || \Request::is('test') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('test.index') }}" class="{{ \Request::is('test') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Tests
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('test.create') }}" class="{{ \Request::is('test/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="{{ \Request::is('report/*') ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-graph2"></i>
                        Reports
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('stock-adjustment.report.get') }}" class="{{ \Request::is('report/stock-adjustment') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Stock Adjustment
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('doctor.report.get') }}" class="{{ \Request::is('report/doctor') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Doctor
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('service.report.get') }}" class="{{ \Request::is('report/service') ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Income
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ \Request::is('setting/*') || \Request::is('setting') ? 'mm-active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-hammer"></i>
                        Settings
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul @if ((str_contains(url()->current(), 'supplier') || str_contains(url()->current(), 'doctor') || str_contains(url()->current(), 'user') || str_contains(url()->current(), 'patient')) && !str_contains(url()->current(), 'report')) class="mm-show" @endif>
                        <li class="{{ \Request::is('user/*') || \Request::is('user') ? 'mm-active' : '' }}">
                            <a href="{{ route('setting.index') }}">
                                <i class="metismenu-icon pe-7s-settings"></i>
                                Settings
                            </a>
                        </li>

                        <li class="{{ \Request::is('user/*') || \Request::is('user') ? 'mm-active' : '' }}">
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                User
                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                            </a>
                            <ul class="{{ \Request::is('user/*') || \Request::is('user') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('user.index') }}" class="{{ \Request::is('user') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Users
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.create') }}" class="{{ \Request::is('user/create') ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon">
                                        </i>Create
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endrole
            </ul>
        </div>
    </div>
</div>