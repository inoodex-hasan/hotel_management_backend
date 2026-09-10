<!-- start sidebar section -->
<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="{{ route('tyro-dashboard.index') }}" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none"
                        src="{{ get_setting('app_logo') ? asset('storage/' . get_setting('app_logo')) : asset('assets/images/logo.svg') }}"
                        alt="logo" />
                    <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">{{ get_setting('app_name', config('app.name')) }}</span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ 
                    activeDropdown: '{{ 
                        request()->routeIs('admin.hero-slides.*', 'admin.about.*', 'admin.dining.*', 'admin.facilities.*', 'admin.gallery.*', 'admin.testimonials.*') ? 'content' : 
                        (request()->routeIs('admin.hotels.*', 'admin.room-types.*') ? 'hotels' : 
                        (request()->routeIs('admin.countries.*', 'admin.cities.*') ? 'locations' : 
                        (request()->routeIs('admin.bookings.*') ? 'bookings' : 
                        (request()->routeIs('admin.inquiries.*', 'admin.newsletter.*') ? 'communications' : 
                        (request()->routeIs('tyro-dashboard.users.*', 'tyro-dashboard.roles.*', 'tyro-dashboard.privileges.*', 'admin.settings.*') ? 'administration' : 'dashboard'))))) 
                    }}' 
                }">

                <!-- Dashboard -->
                <li class="menu nav-item">
                    <a href="{{ route('tyro-dashboard.index') }}" class="nav-link group {{ request()->routeIs('tyro-dashboard.index') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    fill="currentColor" />
                                <path
                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                        </div>
                    </a>
                </li>

                <!-- Section: Hotel Management -->
                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Hotel Management</span>
                </h2>

                <!-- Hotels Dropdown -->
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'hotels'}"
                        @click="activeDropdown === 'hotels' ? activeDropdown = null : activeDropdown = 'hotels'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 22H22C22.5523 22 23 21.5523 23 21V9C23 8.44772 22.5523 8 22 8H2C1.44772 8 1 8.44772 1 9V21C1 21.5523 1.44772 22 2 22Z"
                                    fill="currentColor" />
                                <path
                                    d="M4 8V6C4 4.89543 4.89543 4 6 4H8M20 8V6C20 4.89543 19.1046 4 18 4H16M8 4V2M16 4V2M8 12H10M14 12H16M8 16H10M14 16H16"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Hotels</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300"
                            :class="{'rotate-90' : activeDropdown === 'hotels'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'hotels'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('admin.hotels.index') }}" class="{{ request()->routeIs('admin.hotels.index') ? 'active font-bold text-primary' : '' }}">Manage Hotels</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.room-types.all') }}" class="{{ request()->routeIs('admin.room-types.*') ? 'active font-bold text-primary' : '' }}">Room Types & Photos</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.hotels.create') }}" class="{{ request()->routeIs('admin.hotels.create') ? 'active font-bold text-primary' : '' }}">Add New Hotel</a>
                        </li>
                    </ul>
                </li>

                <!-- Locations Dropdown -->
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'locations'}"
                        @click="activeDropdown === 'locations' ? activeDropdown = null : activeDropdown = 'locations'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z"
                                    fill="currentColor" />
                                <path
                                    d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM12 21V3M3 12H21"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Locations</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300"
                            :class="{'rotate-90' : activeDropdown === 'locations'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'locations'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('admin.countries.index') }}" class="{{ request()->routeIs('admin.countries.*') ? 'active font-bold text-primary' : '' }}">Countries</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.cities.index') }}" class="{{ request()->routeIs('admin.cities.*') ? 'active font-bold text-primary' : '' }}">Cities</a>
                        </li>
                    </ul>
                </li>

                <!-- Section: Website & Content -->
                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Website & Content</span>
                </h2>

                <!-- Website Management Dropdown -->
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'content'}"
                        @click="activeDropdown === 'content' ? activeDropdown = null : activeDropdown = 'content'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M3 7C3 4.79086 4.79086 3 7 3H17C19.2091 3 21 4.79086 21 7V17C21 19.2091 19.2091 21 17 21H7C4.79086 21 3 19.2091 3 17V7Z" fill="currentColor"/>
                                <path d="M3 8H21M8 3V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">CMS Content</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300"
                            :class="{'rotate-90' : activeDropdown === 'content'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'content'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('admin.hero-slides.index') }}" class="{{ request()->routeIs('admin.hero-slides.*') ? 'active font-bold text-primary' : '' }}">Hero Sliders</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.about.edit') }}" class="{{ request()->routeIs('admin.about.*') ? 'active font-bold text-primary' : '' }}">About & Story</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dining.index') }}" class="{{ request()->routeIs('admin.dining.*') ? 'active font-bold text-primary' : '' }}">Dining & Restaurants</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.facilities.index') }}" class="{{ request()->routeIs('admin.facilities.*') ? 'active font-bold text-primary' : '' }}">Facilities & Spa</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active font-bold text-primary' : '' }}">Gallery Photos</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active font-bold text-primary' : '' }}">Guest Testimonials</a>
                        </li>
                    </ul>
                </li>

                <!-- Section: Bookings & Guests -->
                <h2
                    class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                    <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Bookings & Guests</span>
                </h2>

                <!-- Bookings Menu Item -->
                <li class="menu nav-item">
                    <a href="{{ route('admin.bookings.hotel.index') }}"
                        class="nav-link group {{ request()->routeIs('admin.bookings.hotel.*') ? 'active' : '' }}">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M16 2V6M8 2V6M3 10H21M5 4H19C20.1046 4 21 4.89543 21 6V18C21 19.1046 20.1046 20 19 20H5C3.89543 20 3 19.1046 3 18V6C3 4.89543 3.89543 4 5 4Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <span
                                class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Hotel Bookings</span>
                        </div>
                    </a>
                </li>

                <!-- Guest Communications Dropdown -->
                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'communications'}"
                        @click="activeDropdown === 'communications' ? activeDropdown = null : activeDropdown = 'communications'">
                        <div class="flex items-center">
                            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z" fill="currentColor"/>
                                <path d="M6 8L10.5528 11.0352C11.4343 11.6229 12.5657 11.6229 13.4472 11.0352L18 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Communications</span>
                        </div>
                        <div class="rtl:rotate-180 transition-transform duration-300"
                            :class="{'rotate-90' : activeDropdown === 'communications'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'communications'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('admin.inquiries.index') }}" class="{{ request()->routeIs('admin.inquiries.*') ? 'active font-bold text-primary' : '' }}">Guest Inquiries</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.newsletter.index') }}" class="{{ request()->routeIs('admin.newsletter.*') ? 'active font-bold text-primary' : '' }}">Newsletter Subscribers</a>
                        </li>
                    </ul>
                </li>

                @if(auth()->user()?->hasRole('super-admin'))
                    <!-- Section: Administration -->
                    <h2
                        class="-mx-4 mb-1 flex items-center bg-white-light/30 px-7 py-3 font-extrabold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Administration</span>
                    </h2>

                    <li class="nav-item">
                        <ul>
                            <li class="nav-item">
                                <a href="{{ route('tyro-dashboard.users.index') }}" class="group {{ request()->routeIs('tyro-dashboard.users.*') ? 'active' : '' }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                                fill="currentColor" />
                                        </svg>
                                        <span
                                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">User Management</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tyro-dashboard.roles.index') }}" class="group {{ request()->routeIs('tyro-dashboard.roles.*') ? 'active' : '' }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                fill="currentColor" />
                                        </svg>
                                        <span
                                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Roles & Permissions</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tyro-dashboard.privileges.index') }}" class="group {{ request()->routeIs('tyro-dashboard.privileges.*') ? 'active' : '' }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                                fill="currentColor" />
                                        </svg>
                                        <span
                                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Privileges</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}" class="group {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5" d="M12 15a3 3 0 100-6 3 3 0 000 6z" fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M18.121 17.659c.032.085.097.158.18.194l.003.002c.198.088.435-.004.529-.204.03-.065.062-.132.094-.197.105-.209.346-.312.569-.245.068.02.137.04.205.063.228.077.375.31.344.548-.009.071-.02.144-.032.215-.04.241.104.475.34.55a4.342 4.342 0 01.705.315c.218.122.316.395.231.624l-.025.07c-.085.23-.339.351-.568.27-.07-.024-.138-.05-.208-.072-.225-.073-.473.023-.585.228-.035.064-.07.13-.108.194-.123.212-.046.48.167.603.064.037.129.071.193.109.215.126.31.398.225.626-.145.394-.33.766-.554 1.111-.137.211-.407.284-.63.17l-.066-.034c-.218-.11-.49-.057-.643.125-.047.056-.093.113-.143.167-.163.178-.186.446-.057.653l.044.07c.143.232.083.535-.135.698a4.33 4.33 0 01-.84.484c-.233.1-.515-.004-.634-.233l-.037-.073c-.116-.226-.395-.316-.624-.213l-.208.094c-.22.1-.336.353-.274.587l.02.075c.063.242-.083.491-.324.557a4.343 4.343 0 01-.767.121c-.25.016-.474-.165-.52-.413l-.014-.076c-.042-.243-.278-.403-.523-.357l-.226.042c-.244.045-.42.274-.393.52l.008.077c.026.252-.15.484-.403.534a4.343 4.343 0 01-.775-.028c-.25-.034-.43-.257-.406-.508l.006-.077c.022-.247-.14-.475-.386-.54l-.22-.058c-.24-.065-.487.058-.584.29l-.022.054c-.114.283-.43.415-.705.298a4.333 4.333 0 01-.803-.45c-.244-.176-.328-.496-.188-.74l.03-.053c.125-.231.066-.52-.138-.684l-.167-.134c-.201-.161-.26-.445-.143-.675l.035-.069c.127-.249.034-.559-.21-.692a4.34 4.34 0 01-.697-.478c-.218-.184-.257-.5-.091-.73l.047-.066c.15-.21-.082-.52-.279-.652l-.183-.122c-.217-.145-.295-.436-.183-.67l.034-.07c.112-.236.4-.334.643-.22l.205.097c.228.106.505.01.62-.218l.094-.188c.114-.23.41-.303.626-.145l.056.04c.223.16.544.11.706-.112a4.337 4.337 0 01.32-.387c.189-.2.15-.52-.086-.685l-.066-.046c-.22-.153-.306-.449-.2-.686l.03-.067c.105-.239.387-.354.63-.259l.186.072c.23.09.5-.02.603-.245l.078-.17c.128-.278.44-.383.717-.245a4.33 4.33 0 01.605.353c.22.153.525.101.684-.117l.033-.044c.162-.216.444-.291.683-.133l.03.02z"
                                                fill="currentColor" />
                                        </svg>
                                        <span
                                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark transition-colors duration-300">Settings</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </nav>
</div>
