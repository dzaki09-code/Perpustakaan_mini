<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets') }}/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title', 'Dashboard | Perpustakaan')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <style>
      @media (min-width: 1200px) {
        .layout-menu,
        .layout-page {
          transition:
            transform 0.42s ease,
            opacity 0.42s ease,
            visibility 0.42s ease,
            padding-left 0.42s ease;
        }

        html.layout-sidebar-hidden .layout-menu {
          opacity: 0;
          pointer-events: none;
          transform: translateX(-100%);
          visibility: hidden;
        }

        html.layout-sidebar-hidden .layout-page {
          padding-left: 0 !important;
        }
      }
    </style>

    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('panel_control.components.sidebar')

        <div class="layout-page">
          @include('panel_control.components.header')

          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>

            @include('panel_control.components.footer')

            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>

      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const html = document.documentElement;
        const sidebarToggle = document.getElementById('sidebarToggle');
        const desktopBreakpoint = 1200;

        if (!sidebarToggle) {
          return;
        }

        const setExpandedState = function () {
          const isDesktop = window.innerWidth >= desktopBreakpoint;
          const isHidden = isDesktop
            ? html.classList.contains('layout-sidebar-hidden')
            : !html.classList.contains('layout-menu-expanded');

          sidebarToggle.setAttribute('aria-expanded', String(!isHidden));
        };

        sidebarToggle.addEventListener('click', function (event) {
          event.preventDefault();

          if (window.innerWidth >= desktopBreakpoint) {
            const shouldHideSidebar = !html.classList.contains('layout-sidebar-hidden');

            html.classList.toggle('layout-sidebar-hidden', shouldHideSidebar);
            html.classList.remove('layout-menu-expanded');

            if (!shouldHideSidebar) {
              html.classList.remove('layout-menu-collapsed', 'layout-menu-hover');
            }
          } else {
            html.classList.toggle('layout-menu-expanded');
            html.classList.remove('layout-sidebar-hidden');
          }

          setExpandedState();
          window.dispatchEvent(new Event('resize'));
        });

        window.addEventListener('resize', setExpandedState);
        setExpandedState();
      });
    </script>

    @stack('scripts')
  </body>
</html>
