<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #e0e4eb; color: #000;">
        <ul class="navbar-nav">
            <!-- Sidebar Toggle Button -->
            <li class="nav-item nav-icon-hover-bg rounded-circle">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                    <iconify-icon icon="solar:list-bold-duotone" class="fs-7">
                        <style data-style="data-style">
                            :host {
                                display: inline-block;
                                vertical-align: 0
                            }

                            span,
                            svg {
                                display: blocks
                            }
                        </style><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                            viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M3.25 7A.75.75 0 0 1 4 6.25h16a.75.75 0 0 1 0 1.5H4A.75.75 0 0 1 3.25 7"
                                clip-rule="evenodd"></path>
                            <path fill="currentColor"
                                d="M3.25 12a.75.75 0 0 1 .75-.75h11a.75.75 0 0 1 0 1.5H4a.75.75 0 0 1-.75-.75"
                                opacity=".7"></path>
                            <path fill="currentColor"
                                d="M3.25 17a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5H4a.75.75 0 0 1-.75-.75"
                                opacity=".4"></path>
                        </svg>
                    </iconify-icon>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover d-flex align-items-center gap-2" href="javascript:void(0)" id="drop2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="ms-2 d-none d-lg-inline text-capitalize">
                            {{ Auth::user()->name ?? 'Guest' }}
                        </span>                        
                        <img src="{{ asset('/images/profiles/user1.jpg')}}" alt="User" width="35" height="35" class="rounded-circle">

                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary mx-3 mt-2 d-block shadow-none"
                                    style="width: -webkit-fill-available">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        
    </nav>
</header>


<script>
document.getElementById('headerCollapse').addEventListener('click', function() {
    var mainWrapper = document.getElementById('main-wrapper');
    
    // Only toggle the sidebar visibility on screens smaller than 1200px
    if (window.innerWidth < 1200) {
        // Toggle the show-sidebar class to make the sidebar appear/disappear
        mainWrapper.classList.toggle('show-sidebar');
    }
});

// Optionally, ensure that the sidebar stays visible on small screens
window.addEventListener('resize', function() {
    var mainWrapper = document.getElementById('main-wrapper');
    if (window.innerWidth >= 1200) {
        mainWrapper.classList.remove('show-sidebar'); // Sidebar should be always visible on large screens
    }
});

</script>
