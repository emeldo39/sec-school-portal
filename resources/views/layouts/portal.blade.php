<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') — {{ \App\Models\SchoolSetting::get('school_name', 'School Portal') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/school-theme.css') }}">
    @stack('styles')
</head>

<body>

    <div class="body-overlay"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="sidebar">
        <button type="button" class="sidebar-close-btn">
            <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
        </button>


        <!-- <div>
            <div class="sidebar-logo d-flex align-items-center justify-content-between">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('teacher.dashboard') }}">
                    @if(\App\Models\SchoolSetting::get('school_logo'))
                    <img src="{{ asset('storage/' . \App\Models\SchoolSetting::get('school_logo')) }}" alt="Logo"
                        style="height:36px;" class="light-logo dark-logo">
                    @else
                    <span class="fw-bold text-primary-600 text-md">
                        {{ \App\Models\SchoolSetting::get('school_name', 'School Portal') }}
                    </span>
                    @endif

                </a>
                <button type="button" class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500"
                    aria-label="Collapse Sidebar">
                    <i class="ri-contract-left-line"></i>
                </button>
            </div>
        </div> -->

        {{-- Logo --}}
        <div>
            <!-- <div class="sidebar-logo" style="padding: 14px 16px 10px;">
                <div class="d-flex align-items-center justify-content-between mb-1"> -->
            <div class="sidebar-logo d-flex align-items-center justify-content-between">
                <a href="{{ auth()->user()->hasAdminAccess() ? route('admin.dashboard') : route('teacher.dashboard') }}"
                    class="d-flex align-items-center gap-10 text-decoration-none overflow-hidden">
                    @if(\App\Models\SchoolSetting::get('school_logo'))
                    <img src="{{ asset('storage/' . \App\Models\SchoolSetting::get('school_logo')) }}" alt="School Logo"
                        style="height:44px; width:44px; object-fit:contain; flex-shrink:0; border-radius:6px;"
                        class="light-logo dark-logo">
                    @else
                    <span style="width:44px; height:44px; border-radius:6px; background:#2A2567; flex-shrink:0;
                                     display:flex; align-items:center; justify-content:center;">
                        <i class="ri-school-line" style="color:#fff; font-size:20px;"></i>
                    </span>
                    @endif
                    <div class="overflow-hidden sidebar-name-block">
                        <p class="fw-bold mb-0 text-truncate" style="font-size:11.5px; color:#2A2567; line-height:1.3;">
                            {{ \App\Models\SchoolSetting::get('school_name', 'School Portal') }}
                        </p>
                        <p class="mb-0 text-truncate" style="font-size:9.5px; color:#7B79A0; letter-spacing:.03em;">
                            School Portal
                        </p>
                    </div>
                </a>
                <button type="button"
                    class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500 ms-8"
                    aria-label="Collapse Sidebar" style="flex-shrink:0;">
                    <i class="ri-contract-left-line"></i>
                </button>
            </div>
            <!-- </div> -->
        </div>

        {{-- User info --}}
        <div class="mx-16 py-12">
            <div class="dropdown profile-dropdown">
                <button type="button"
                    class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12"
                    data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <span class="d-flex align-items-start gap-10">
                        @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo"
                            class="w-40-px h-40-px rounded-circle object-fit-cover flex-shrink-0">
                        @else
                        <span
                            class="w-40-px h-40-px rounded-circle bg-primary-600 text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        @endif
                        <span class="profile-dropdown__contents">
                            <span class="h6 mb-0 text-md d-block text-primary-light">{{ auth()->user()->name }}</span>
                            <span class="text-secondary-light text-sm mb-0 d-block">
                                @if(auth()->user()->isPrincipal()) Principal
                                @elseif(auth()->user()->isAdmin()) IT Administrator
                                @elseif(auth()->user()->is_form_teacher) Form Teacher
                                @else Teacher
                                @endif
                            </span>
                        </span>
                    </span>
                    <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
                        <i class="ri-arrow-right-s-line"></i>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                    <li>
                        <a href="{{ auth()->user()->hasAdminAccess() ? route('admin.profile') : route('teacher.profile') }}"
                            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                            <i class="ri-user-3-line"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6 w-100 text-start border-0 bg-transparent">
                                <i class="ri-shut-down-line"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <div class="sidebar-menu-area">
            <ul class="sidebar-menu" id="sidebar-menu">

                {{-- ADMIN / PRINCIPAL MENU --}}
                @if(auth()->user()->hasAdminAccess())

                <li class="{{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="ri-home-4-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Academic</li>

                <li class="dropdown {{ request()->routeIs('admin.students.*') ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-graduation-cap-line"></i>
                        <span>Students</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.students.index') }}"
                                class="{{ request()->routeIs('admin.students.index') ? 'active-page' : '' }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Student List
                            </a></li>
                        <li><a href="{{ route('admin.students.create') }}"
                                class="{{ request()->routeIs('admin.students.create') ? 'active-page' : '' }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Add Student
                            </a></li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->routeIs('admin.users.*') ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-user-follow-line"></i>
                        <span>Staff Accounts</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.users.index') }}"
                                class="{{ request()->routeIs('admin.users.index') ? 'active-page' : '' }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Staff List
                            </a></li>
                        <li><a href="{{ route('admin.users.create') }}"
                                class="{{ request()->routeIs('admin.users.create') ? 'active-page' : '' }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Add Staff
                            </a></li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">Scores & Results</li>

                <li class="{{ request()->routeIs('admin.scores.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.scores.index') }}">
                        <i class="ri-file-edit-line"></i>
                        <span>Score Approval</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.reports.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.reports.index') }}">
                        <i class="ri-bar-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>

                @if(auth()->user()->isPrincipal())
                <li class="{{ request()->routeIs('admin.publications.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.publications.index') }}">
                        <i class="ri-quill-pen-line"></i>
                        <span>Principal's Remarks</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-menu-group-title">Configuration</li>

                <li
                    class="dropdown {{ request()->routeIs('admin.classes.*') || request()->routeIs('admin.subjects.*') || request()->routeIs('admin.terms.*') ? 'open' : '' }}">
                    <a href="javascript:void(0)">
                        <i class="ri-settings-3-line"></i>
                        <span>Academic Setup</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.classes.index') }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Classes
                            </a></li>
                        <li><a href="{{ route('admin.subjects.index') }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Subjects
                            </a></li>
                        <li><a href="{{ route('admin.terms.index') }}">
                                <i class="ri-circle-fill circle-icon w-auto"></i> Academic Terms
                            </a></li>
                    </ul>
                </li>


                <li class="{{ request()->routeIs('admin.settings') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.settings') }}">
                        <i class="ri-tools-line"></i>
                        <span>School Settings</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.activity-logs') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.activity-logs') }}">
                        <i class="ri-history-line"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <li class="{{ request()->routeIs('admin.backup*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.backup.index') }}">
                        <i class="ri-database-2-line"></i>
                        <span>Backup & Reset</span>
                    </a>
                </li>
                @endif

                {{-- Announcements, Gallery, Messages --}}
                <li class="sidebar-menu-group-title">Communication</li>

                <li class="{{ request()->routeIs('admin.announcements.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.announcements.index') }}">
                        <i class="ri-notification-line"></i>
                        <span>Announcements</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.news-posts.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.news-posts.index') }}">
                        <i class="ri-article-line"></i>
                        <span>News Posts</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.popup-notice*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.popup-notice.index') }}">
                        <i class="ri-notification-2-line"></i>
                        <span>Popup Notice</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.gallery.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.gallery.index') }}">
                        <i class="ri-image-line"></i>
                        <span>Gallery</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.hero-slides.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.hero-slides.index') }}">
                        <i class="ri-slideshow-line"></i>
                        <span>Hero Slides</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.messages.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.messages.index') }}">
                        <i class="ri-mail-line"></i>
                        <span>Messages</span>
                        @php $unread = \App\Models\ContactMessage::where('is_read',false)->count(); @endphp
                        @if($unread > 0)
                        <span class="badge bg-danger text-white ms-1" style="font-size:.65rem;">{{ $unread }}</span>
                        @endif
                    </a>
                </li>

                {{-- TEACHER MENU --}}
                @else

                <li class="{{ request()->routeIs('teacher.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.dashboard') }}">
                        <i class="ri-home-4-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('teacher.students') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.students') }}">
                        <i class="ri-graduation-cap-line"></i>
                        <span>My Students</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('teacher.attendance.*') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.attendance.index') }}">
                        <i class="ri-calendar-check-line"></i>
                        <span>Attendance</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('teacher.scores.*') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.scores.index') }}">
                        <i class="ri-file-edit-line"></i>
                        <span>Score Entry</span>
                    </a>
                </li>

                @if(auth()->user()->is_form_teacher)
                <li class="{{ request()->routeIs('teacher.results.*') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.results.index') }}">
                        <i class="ri-file-pdf-line"></i>
                        <span>Result Sheets</span>
                    </a>
                </li>
                @endif

                <li class="{{ request()->routeIs('teacher.announcements') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.announcements') }}">
                        <i class="ri-notification-line"></i>
                        <span>Announcements</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('teacher.profile') ? 'active-page' : '' }}">
                    <a href="{{ route('teacher.profile') }}">
                        <i class="ri-user-3-line"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                @endif

            </ul>
        </div>
    </aside>

    {{-- ===================== TOPBAR + MAIN CONTENT ===================== --}}
    <main class="dashboard-main">
        <div class="navbar-header shadow-1">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <button type="button" class="sidebar-mobile-toggle" aria-label="Sidebar Mobile Toggler">
                            <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <button type="button" data-theme-toggle
                            class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            aria-label="Dark & Light Mode"></button>

                        {{-- Announcements bell --}}
                        <div class="dropdown">
                            <button
                                class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                                type="button" data-bs-toggle="dropdown" aria-label="Notifications">
                                <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                            </button>
                            <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                                <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16">
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Announcements</h6>
                                </div>
                                <div class="max-h-300-px overflow-y-auto scroll-sm px-16 pb-12">
                                    @php
                                    $notices = \App\Models\Announcement::where(function($q){
                                    $q->where('target','all')
                                    ->orWhere('class_id', auth()->user()->form_class_id);
                                    })->latest()->take(5)->get();
                                    @endphp
                                    @forelse($notices as $notice)
                                    <div class="py-8 border-bottom">
                                        <p class="text-sm fw-semibold mb-2">{{ $notice->title }}</p>
                                        <p class="text-xs text-secondary-light mb-0">
                                            {{ $notice->created_at->diffForHumans() }}</p>
                                    </div>
                                    @empty
                                    <p class="text-sm text-secondary-light py-8">No announcements yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- User avatar + logout --}}
                        <div class="dropdown">
                            <button class="d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown"
                                aria-label="User Menu">
                                @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                    class="w-40-px h-40-px rounded-circle object-fit-cover" alt="User">
                                @else
                                <span
                                    class="w-40-px h-40-px rounded-circle bg-primary-600 text-white d-flex align-items-center justify-content-center fw-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu to-top dropdown-menu-sm border p-12">
                                <li class="px-12 py-8 border-bottom mb-8">
                                    <p class="text-sm fw-semibold mb-0">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-secondary-light mb-0">{{ auth()->user()->email }}</p>
                                </li>
                                <li>
                                    <a href="{{ auth()->user()->hasAdminAccess() ? route('admin.profile') : route('teacher.profile') }}"
                                        class="dropdown-item rounded text-secondary-light d-flex align-items-center gap-2 py-6">
                                        <i class="ri-user-3-line"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="dropdown-item rounded text-danger d-flex align-items-center gap-2 py-6 w-100 text-start border-0 bg-transparent">
                                            <i class="ri-shut-down-line"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-main-body">

            {{-- Breadcrumb --}}
            <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <div>
                    <h6 class="fw-semibold mb-0">@yield('page-title', 'Dashboard')</h6>
                    <p class="text-neutral-600 mt-4 mb-0">@yield('page-subtitle', '')</p>
                </div>
                @yield('breadcrumb-actions')
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-24" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-24" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Page content --}}
            @yield('content')

        </div>
    </main>

    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>