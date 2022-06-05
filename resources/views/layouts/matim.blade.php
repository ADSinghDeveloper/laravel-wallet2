<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle') :: {{ config('app.name', 'Wallet') }}</title>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{ url('matim/css/material-kit.css?v=2.0.4') }}" rel="stylesheet" />
    <link href="{{ url('matim/css/material-dashboard.css?v=2.1.1') }}" rel="stylesheet" />

    <link href="{{ url('css/wallet.css') }}" rel="stylesheet" />
    <script type="text/javascript">var notes = [];</script>
</head>
<body>
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag

    https://demos.creative-tim.com/material-kit/index.html
    https://demos.creative-tim.com/material-kit/docs/2.0/components/forms.html
    https://demos.creative-tim.com/bs3/material-dashboard/documentation/tutorial-components.html
    https://material.io/tools/icons/?style=baseline
-->
<div class="wrapper ">
  @if (!Auth::guest())
    <div class="sidebar" data-color="wallet" data-background-color="white" data-image="{{ url('matim/img/sidebar-1.jpg') }}">
      <div class="logo">
        <a href="{{ url('/') }}" class="simple-text logo-normal">
          Settings
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item @if(Route::currentRouteName() == 'home') active @endif">
            <a class="nav-link" href="{{ url('/') }}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('transaction_view', 'transaction_add', 'transaction_edit', 'transaction_save', 'transaction_del'))) active @endif">
            <a class="nav-link" href="{{ route('transaction_view') }}">
              <i class="material-icons">view_list</i>
              <p>Transactions</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('accounts', 'account_add', 'account_edit'))) active @endif">
            <a class="nav-link" href="{{ route('accounts') }}">
              <i class="material-icons">person</i>
              <p>Accounts</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('categories', 'category_add', 'category_edit'))) active @endif">
            <a class="nav-link" href="{{ route('categories') }}">
              <i class="material-icons">category</i>
              <p>Categories</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('filters', 'filter_add', 'filter_edit'))) active @endif">
            <a class="nav-link" href="{{ route('filters') }}">
              <i class="material-icons">filter_list</i>
              <p>Filters</p>
            </a>
          </li>
          @if('1' == \Auth::user()->id)
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('paymentmodes', 'paymentmode_add', 'paymentmode_edit'))) active @endif">
            <a class="nav-link" href="{{ route('paymentmodes') }}">
              <i class="material-icons">payment</i>
              <p>Payment Modes</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('currencies', 'currency_add', 'currency_edit'))) active @endif">
            <a class="nav-link" href="{{ route('currencies') }}">
              <i class="material-icons">attach_money</i>
              <p>Currencies</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('icons','icon_add'))) active @endif">
            <a class="nav-link" href="{{ route('icons') }}">
              <i class="material-icons">all_out</i>
              <p>Icons</p>
            </a>
          </li>
          <li class="nav-item @if(in_array(Route::currentRouteName(), array('colors','color_add'))) active @endif">
            <a class="nav-link" href="{{ route('colors') }}">
              <i class="material-icons">format_color_fill</i>
              <p>Colors</p>
            </a>
          </li>
          @endif
          <li class="nav-item @if(Route::currentRouteName() == 'import') active @endif">
            <a class="nav-link" href="{{ route('import') }}">
              <i class="material-icons">get_app</i>
              <p>Import</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.linkedin.com/in/amandeeps20/" target="_blank">
              &copy;
              <script>
                document.write(new Date().getFullYear())
              </script>, developed by AD Singh
            </a>
          </li>
        </ul>
      </div>
    </div>
    @endif
    <div class="@if(Auth::guest()) page-header header-filter @else main-panel @endif">
	  @if (!Auth::guest())
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a href="{{ url('/') }}" title="{{ Auth::user()->name }}'s Wallet"><h4>Wallet</h4></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form hide" action="{{ route('transaction_view') }}">
              <div class="input-group no-border">
                <input type="text" name="search" value="{{ old('search') }}" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdownProfile" title="{{ Auth::user()->name }} ({{ Auth::user()->email }})" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">My Profile</p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="{{ route('profile') }}">{{ Auth::user()->name }}</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('transactions_export') }}">Export</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#delallPopup">Delete All</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hide">
                      {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      @endif
      <!-- End Navbar -->
      <div class="content @if(Auth::guest()) col-lg-12 @endif">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              @include('layouts.alerts')
              @yield('content')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="delallPopup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card filter-block">
                <form action="{{ route('del_all') }}" method="post">
                    <div class="card-header card-header-danger">
                        <h4 class="card-title">Delete All User Data <span class="pull-right"><i class="material-icons">delete</i></span></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 filter-field">
                                <div class="input-group no-border">
                                    <input type="password" tabindex="1" required name="confirm_del" class="form-control" placeholder="Enter password to confirm delete all user dala">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary"><i class="material-icons">delete</i></button>
                                <button type="button" class="btn btn-primary btn-round btn-fab pull-right" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  <!--   Core JS Files   -->
  <script src="{{ url('matim/js/core/jquery.min.js') }}"></script>
  <script src="{{ url('matim/js/core/popper.min.js') }}"></script>
  <script src="{{ url('matim/js/core/bootstrap-material-design.min.js') }}"></script>
  <script src="{{ url('matim/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <!-- Plugin for the momentJs  -->
  <script src="{{ url('matim/js/plugins/moment.min.js') }}"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="{{ url('matim/js/plugins/sweetalert2.js') }}"></script>
  <!-- Forms Validations Plugin -->
  <script src="{{ url('matim/js/plugins/jquery.validate.min.js') }}"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <!-- <script src="{{ url('matim/js/plugins/jquery.bootstrap-wizard.js') }}"></script> -->
  <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <!-- <script src="{{ url('matim/js/plugins/bootstrap-selectpicker.js') }}"></script> -->
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="{{ url('matim/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <!-- <script src="{{ url('matim/js/plugins/jquery.dataTables.min.js') }}"></script> -->
  <!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <!-- <script src="{{ url('matim/js/plugins/bootstrap-tagsinput.js') }}"></script> -->
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="{{ url('matim/js/plugins/jasny-bootstrap.min.js') }}"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="{{ url('matim/js/plugins/fullcalendar.min.js') }}"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <!-- <script src="{{ url('matim/js/plugins/jquery-jvectormap.js') }}"></script> -->
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <!-- <script src="{{ url('matim/js/plugins/nouislider.min.js') }}"></script> -->
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <!-- <script src="{{ url('matim/js/plugins/arrive.min.js') }}"></script> -->
  <!--  Google Maps Plugin    
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->
  <!-- Chartist JS -->
  <!-- <script src="{{ url('matim/js/plugins/chartist.min.js') }}"></script> -->
  <!--  Notifications Plugin    -->
  <script src="{{ url('matim/js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ url('matim/js/material-dashboard.js?v=2.1.1') }}" type="text/javascript"></script>
  <script src="{{ url('matim/js/material-kit.js?v=2.0.4') }}" type="text/javascript"></script>
  <script src="{{ url('js/jquery.autocomplete.js') }}"></script>
  <script src="{{ url('js/wallet.js') }}"></script>
  <script>
    $(document).ready(function() {
      //init DateTimePickers
      materialKit.initFormExtendedDatetimepickers();

      // Sliders Init
      //materialKit.initSliders();

      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
      // Javascript method's body can be found in assets/js/demos.js
      // md.initDashboardPageCharts();
    });
    $(document).ready(function() {
      if(notes.length > 0){
        // Initialize autocomplete with custom appendTo:
        $('.autocomplete').autocomplete({
            lookup: notes
        });
      }
    });
  </script>
</body>
</html>
