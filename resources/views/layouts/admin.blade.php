<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Pharmacy" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>

    <link rel="shortcut icon" href="{{ Storage::disk('public')->url('favicon/'.$web->web_favicon) }}">

    
    <!-- Custom Files -->
    <link href="{{asset('contents/admin')}}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('contents/admin')}}/assets/plugins/datatables/new/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('contents/admin')}}/assets/css/icons.css" rel="stylesheet" type="text/css" />
    @stack('css')
    <link href="{{asset('contents/admin')}}/assets/css/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('contents/admin')}}/assets/css/pace.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('contents/admin')}}/assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('contents/admin')}}/assets/css/custom.css" rel="stylesheet" type="text/css" />
    <script src="{{asset('contents/admin')}}/assets/js/modernizr.min.js"></script>

</head>
<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Starts -->

        @include('admin.includes.topbar')

        <!-- Topbar Ends -->

        <!-- Left Sidebar Start -->

        @include('admin.includes.sidebar')

        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Page-Title Breadcrumb -->
        
                    @yield('content')
        
                </div>
                <!-- container -->
            </div>
            <!-- content -->
            
            <footer class="footer">
                {{ $web->web_footer_text }}
            </footer>

        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <script src="{{asset('contents/admin')}}/assets/js/jquery.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/popper.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/detect.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/fastclick.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.slimscroll.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.blockUI.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/waves.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/wow.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.nicescroll.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.scrollTo.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.toast.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/pace.min.js"></script>

    <!-- jQuery -->
    <script src="{{asset('contents/admin')}}/assets/plugins/moment/moment.min.js"></script>

    <!-- Counter js  -->
    <script src="{{asset('contents/admin')}}/assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
    <script src="{{asset('contents/admin')}}/assets/plugins/counterup/jquery.counterup.min.js"></script>

    <script src="{{asset('contents/admin')}}/assets/js/sweetalert2.min.js"></script>

    @if(Session::has('success'))
        <script>
            $.toast({
                heading: 'Success',
                text: "{{ session('success') }}",
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'success',
                hideAfter: 3000, 
                stack: 6
            });
        </script>
    @endif
    @if(Session::has('error'))
        <script>
            $.toast({
                heading: 'Error',
                text: "{{ session('error') }}",
                position: 'top-right',
                loaderBg:'#ff6849',
                icon: 'error',
                hideAfter: 3000, 
                stack: 6
            });
        </script>
    @endif

    <!-- Dashboard js  -->
    <script src="{{asset('contents/admin')}}/assets/pages/jquery.dashboard.js"></script>
    <script src="{{asset('contents/admin')}}/assets/plugins/datatables/new/jquery.dataTables.min.js"></script> 
    <script src="{{asset('contents/admin')}}/assets/plugins/datatables/new/datatables.min.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/jquery.app.js"></script>

    
    @stack('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{asset('contents/admin')}}/assets/js/custom.js"></script>
    <script src="{{asset('contents/admin')}}/assets/js/ajax.js"></script>

    <script>
        // Counter Up
        jQuery(document).ready(function($) {
            $('.counter').counterUp({
                delay: 100,
                time: 1200
            });
        });
    </script>
</body>
</html>
