<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <title>Dashboard</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Favicon -->
  <link href="{{ asset('images/logo.png') }}" rel="icon" type="image/png">
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Kit CSS -->
  <link href="{{ asset('dark-theme/css/material-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
</head>

<body class="dark-edition">
  <div class="wrapper ">
    <div class="sidebar" data-color="white" data-background-color="black" data-image="{{ asset('./dark-theme/img/sidebar-2.jpg') }}">
      <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
      <div class="logo">
        <a href="{{ url('/admin/dashboard') }}" class="simple-text logo-normal">
          <img src="{{ asset('images/logo.png') }}" class="img-fluid" style="max-width:120px;">
        </a>
      </div>
      <div class="sidebar-wrapper">
          <div style="color:#222; text-align:center; padding:10px; background:#fafafa;">
            {{ Auth::user()->name }}
          </div>
        <ul class="nav">
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/dashboard') }}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/payout_list') }}">
              <i class="material-icons">money</i>
              <p>Payout List</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/all_traders') }}">
              <i class="material-icons">person</i>
              <p>All Traders</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/payments') }}">
              <i class="material-icons">notifications</i>
              <p>Payment Notifications</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/register') }}">
              <i class="fa fa-user-secret"></i>
              <p>Registered Admins</p>
            </a>
          </li>{{--
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/') }}">
              <i class="material-icons">business</i>
              <p>Emailing</p>
            </a>
          </li>--}}
          <li class="nav-item ">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
              <i class="material-icons">login</i>
              <p>{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
          <!-- your sidebar here -->
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:void(0)">@yield('page-header')</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          </div>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <!--<li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                  <i class="material-icons" title="Logout">login</i>
                  <p class="d-lg-none d-md-block">
                    {{ __('Logout') }}
                  </p>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                </a>
              </li>-->
              <!--your navbar here-->
            </ul>
          </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <!-- your content here -->
        @yield('content')
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright float-right">
            &copy; {{ date('Y') }} Yellow Traders | Developed by
            <a href="https://locaatech.com" target="_blank">LocaaTech</a> <!--<i class="material-icons">military_tech</i>-->.
          </div>
          <!-- your footer here -->
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="{{ asset('./dark-theme/js/core/jquery.min.js') }}"></script>
  <script src="{{ asset('./dark-theme/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('./dark-theme/js/core/bootstrap-material-design.min.js') }}"></script>
  <script src="https://unpkg.com/default-passive-events"></script>
  <script src="{{ asset('./dark-theme/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="{{ asset('./dark-theme/js/plugins/chartist.min.js') }}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{ asset('./dark-theme/js/plugins/bootstrap-notify.js') }}"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('./dark-theme/js/material-dashboard.js?v=2.1.0') }}"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('./dark-theme/demo/demo.js') }}"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

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
    });
  </script>
</body>

</html>
