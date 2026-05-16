<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

    <!-- Custom styles for this template-->
    <link href="/css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/timeline.css')}}">

<!-- 1. Ensure you have the CSRF Token Meta Tag in your <head> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 2. The Push Notification Setup Script -->
    <script>
        // Safely pass the VAPID key from Laravel config to JavaScript
        const VAPID_PUBLIC_KEY = "{{ config('webpush.vapid.public_key') }}";

        // Register Service Worker and initiate subscription flow
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service Worker registered successfully');
                        // Automatically trigger subscription check once ready
                        initiatePushSubscription();
                    })
                    .catch(error => console.error('Service Worker registration failed:', error));
            });
        }

        async function initiatePushSubscription() {
            try {
                const registration = await navigator.serviceWorker.ready;
                
                // Prompt user for browser permission
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    console.warn('Push notification permission denied.');
                    return;
                }

                // Convert VAPID key to required format for browser security
                const applicationServerKey = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);

                // Generate browser subscription tokens
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                });

                // Post the subscription payload directly to your Laravel controller
                await fetch('/push-subscriptions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(subscription)
                });

                console.log('Successfully subscribed to Push Notifications.');
            } catch (error) {
                console.error('Failed to subscribe user:', error);
            }
        }

        // Helper function required to format VAPID string keys for the browser
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>    

</head>

<body id="page-top">

    <!-- Modal -->
    <div id="g-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" id="g-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="g-modal_title"></h3>
                    <button class="close" id="g-close_form_modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('includes.deleteDirector')
                    @include('includes.deleteUser')
                    @include('includes.deleteFacility')
                    @include('includes.changePassword')
                    @include('includes.limitReachedContent')
                </div>
            </div>
        </div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center " href="index.html">
                <div class="sidebar-brand-icon ">
                    <img class="img-fluid" style="width: 10vh;" src="{{asset('images/nrl_logo.png')}}" alt="">
                </div>
                <div class="sidebar-brand-text mx-3">NRL</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/home">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Heading 
            <div class="sidebar-heading">
                Interface
            </div>
        -->

            <!-- Nav Item - Pages Collapse Menu -->
            @role('admin')
            <!-- Divider -->
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="{{route('users.index')}}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('directors.index')}}">
                    <i class="fas fa-signature"></i>
                    <span>Final Signatories</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{route('facilities.index')}}">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Facilities</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('proficiency-testing.index')}}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Proficiency Testings</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('announcements.index')}}">
                    <i class="fa fa-bullhorn"></i>
                    <span>Announcements</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('settings.index')}}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span></a>
            </li>
            @else
            <!-- Divider -->
            <hr class="sidebar-divider">
            @can('create user')
            <li class="nav-item">
                <a class="nav-link" href="{{route('users.index')}}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('settings.index')}}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span></a>
            </li>
            @endcan
            @endrole
            @role('verifier|encoder2|head')
            <li class="nav-item">
                <a class="nav-link" href="/proficiency-testing">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Proficiency Testings</span></a>
            </li>
            @endrole




            @role('Facility')
            <li class="nav-item">
                <a class="nav-link" href="{{route('proficiency-testing.facility.index')}}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Proficiency Testings</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('facility.profile')}}">
                    <i class="fa fa-address-card" aria-hidden="true"></i>

                    <span>Profile</span></a>
            </li>
            @endrole
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-md-inline text-gray-600 small">{{Auth::user()->name}}</span>
                                <span class="mr-2 d-block d-md-none text-gray-600 small"><i class="fa fa-user"></i></span>

                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-item">Role: {{Auth::user()->roles->pluck('name')[0]}}</div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Powered by: </span>
                        <img src="{{asset('images/bobongMD.png')}}" alt="bobongmdLogo" srcset="" class="img img-fluid" width="70">
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src='{{asset("js/jquery.easing.min.js")}}'></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script>
        const gmodal = $('#g-modal');
        const gmodalDialog = $('#g-modal-dialog');
        const gmodalTitle = $('#g-modal_title');
        const gcloseFormModal = $('#g-close_form_modal')
        gcloseFormModal.click(function(e) {
            gmodalTitle.text('')
            gmodal.modal('toggle')
            $('.modal-backdrop').hide();
        })

        const handleCloseGModal = function() {
            gmodalTitle.text('')
            gmodal.modal('toggle')
            $('.modal-backdrop').hide();
        }
    </script>
    @yield('javascript')

</body>

</html>