<div x-data="{
    openMenu: null,
    isActive(path) {
        return window.location.pathname.startsWith(path);
    },
    initializeMenu() {
        const path = window.location.pathname;
        if (path.startsWith('/users')) this.openMenu = 'users';
        else if (path.startsWith('/customers')) this.openMenu = 'customer';
        else if (path.startsWith('/sales')) this.openMenu = 'sales';
        else if (path.startsWith('/marketing')) this.openMenu = 'marketing';
        else if (path.startsWith('/products')) this.openMenu = 'product';
        else if (path.startsWith('/projects')) this.openMenu = 'project';
        else if (path.startsWith('/reports')) this.openMenu = 'report';
        else if (path.startsWith('/vendors')) this.openMenu = 'vendor';
    }
}" x-init="initializeMenu()">
    <aside class="h-screen">
        <nav class="h-full flex flex-col bg-white border-r shadow-sm transition-all w-64" id="sidebar">
            <!-- Fixed Header Section -->
            <div class="flex-none">
                <!-- Logo/Header -->
                <div class="p-4 pb-2 flex justify-between items-center border-b">
                    <div id="logo" class="flex items-center transition-all">
                        <span class="self-center text-xl font-semibold whitespace-nowrap text-black dark:text-white">CRM
                            Vendor</span>
                    </div>
                    <button onclick="toggleSidebar()" class="p-1.5 rounded-lg bg-gray-50 hover:bg-gray-100">
                    </button>
                </div>

                <!-- Dashboard Link (Fixed) -->
                <a href="/dashboard"
                    class="flex justify-center relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                    :class="isActive('/dashboard') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605" />
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
                <div class="border-b"></div>
            </div>

            <!-- Scrollable Menu Section -->
            <div class="flex-1 overflow-y-auto px-3 mt-3">
                @if(Auth::user()->role === 'Admin')
                    <!-- Users Menu -->
                    <a @click="openMenu = (openMenu === 'users' ? null : 'users')"
                        class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                        :class="isActive('/users') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>Users</span>
                        </div>
                        <svg x-show="openMenu !== 'users'" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                        <svg x-show="openMenu === 'users'" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                        </svg>
                    </a>

                    <!-- Users Submenu -->
                    <div x-show="openMenu === 'users'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        class="flex flex-col gap-1 mt-1">
                        <a href="/users" class="p-4 rounded"
                            :class="isActive('/users') && !window.location.pathname.includes('/profile') && !window.location.pathname.includes('/access') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                            User Management
                        </a>
                       
                    </div>

                    <!-- Customers Menu -->
                    <a @click="openMenu = (openMenu === 'customer' ? null : 'customer')"
                        class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                        :class="isActive('/customers') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <span>Customers</span>
                        </div>
                        <svg x-show="openMenu !== 'customer'" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                        <svg x-show="openMenu === 'customer'" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                        </svg>
                    </a>

                    <!-- Customers Submenu -->
                    <div x-show="openMenu === 'customer'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-4"
                        class="flex flex-col gap-1 mt-1">
                        <a href="/customers" class="p-4 rounded"
                            :class="isActive('/customers') && !window.location.pathname.includes('/interaction') && !window.location.pathname.includes('/segmentation') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                            Customer Data
                        </a>
                        <a href="/customers/interaction" class="p-4 rounded"
                            :class="isActive('/customers/interaction') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                            Customer Interaction
                        </a>
                        <a href="/customers/segmentation" class="p-4 rounded"
                            :class="isActive('/customers/segmentation') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                            Customer Segmentation
                        </a>
                    </div>

                    <!-- Marketing Menu -->
                    <a @click="openMenu = (openMenu === 'marketing' ? null : 'marketing')"
                        class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                        :class="isActive('/marketing') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                            </svg>
                            <span>Marketing</span>
                        </div>
                        <svg x-show="openMenu !== 'marketing'" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                       </svg>
                       <svg x-show="openMenu === 'marketing'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                       </svg>
                   </a>

                   <!-- Marketing Submenu -->
                   <div x-show="openMenu === 'marketing'"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="opacity-0 transform -translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="opacity-100 transform translate-y-0"
                       x-transition:leave-end="opacity-0 transform -translate-y-4"
                       class="flex flex-col gap-1 mt-1">
                       <a href="/marketing/whatsapp" class="p-4 rounded"
                           :class="isActive('/marketing/whatsapp') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           WhatsApp Campaign
                       </a>
                       <a href="/leads" class="p-4 rounded"
                           :class="isActive('/leads') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Lead Management
                       </a>
                       <a href="/marketing/analysis" class="p-4 rounded"
                           :class="isActive('/marketing/analysis') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Campaign Analysis
                       </a>
                   </div>

                   <!-- Vendors Menu -->
                   <a @click="openMenu = (openMenu === 'vendor' ? null : 'vendor')"
                       class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                       :class="isActive('/vendors') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                       <div class="flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="size-5">
                               <path stroke-linecap="round" stroke-linejoin="round"
                                   d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                           </svg>
                           <span>Vendors</span>
                       </div>
                       <svg x-show="openMenu !== 'vendor'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                       </svg>
                       <svg x-show="openMenu === 'vendor'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                       </svg>
                   </a>

                   <!-- Vendors Submenu -->
                   <div x-show="openMenu === 'vendor'"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="opacity-0 transform -translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="opacity-100 transform translate-y-0"
                       x-transition:leave-end="opacity-0 transform -translate-y-4"
                       class="flex flex-col gap-1 mt-1">
                       <a href="/vendors" class="p-4 rounded"
                           :class="isActive('/vendors') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Vendor List
                       </a>
                   </div>
               @endif

               @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Vendor')
                   <!-- Sales Menu -->
                   <a @click="openMenu = (openMenu === 'sales' ? null : 'sales')"
                       class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                       :class="isActive('/sales') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                       <div class="flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="size-5">
                               <path stroke-linecap="round" stroke-linejoin="round"
                                   d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                           </svg>
                           <span>Sales</span>
                       </div>
                       <svg x-show="openMenu !== 'sales'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                       </svg>
                       <svg x-show="openMenu === 'sales'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                       </svg>
                   </a>

                   <!-- Sales Submenu -->
                   <div x-show="openMenu === 'sales'"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="opacity-0 transform -translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="opacity-100 transform translate-y-0"
                       x-transition:leave-end="opacity-0 transform -translate-y-4"
                       class="flex flex-col gap-1 mt-1">
                       <a href="/sales/quotation" class="p-4 rounded"
                           :class="isActive('/sales/quotation') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Price Quotation
                       </a>
                       <a href="/sales/orders" class="p-4 rounded"
                           :class="isActive('/sales/orders') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Sales Order
                       </a>
                       <a href="/sales/shipping" class="p-4 rounded"
                           :class="isActive('/sales/shipping') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Shipping Status
                       </a>
                   </div>
               @endif

               @if(Auth::user()->role === 'Admin' || Auth::user()->role === 'Vendor' || Auth::user()->role === 'Customers')
                   <!-- Products Menu -->
                   <a @click="openMenu = (openMenu === 'product' ? null : 'product')"
                       class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                       :class="isActive('/products') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                       <div class="flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                               stroke="currentColor" class="size-5">
                               <path stroke-linecap="round" stroke-linejoin="round"
                                   d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                           </svg>
                           <span>Product</span>
                       </div>
                       <svg x-show="openMenu !== 'product'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                       </svg>
                       <svg x-show="openMenu === 'product'" xmlns="http://www.w3.org/2000/svg" fill="none"
                           viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                       </svg>
                   </a>

                   <!-- Products Submenu -->
                   <div x-show="openMenu === 'product'"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="opacity-0 transform -translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="opacity-100 transform translate-y-0"
                       x-transition:leave-end="opacity-0 transform -translate-y-4"
                       class="flex flex-col gap-1 mt-1">
                       <a href="/products/catalog" class="p-4 rounded"
                           :class="isActive('/products/catalog') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Product Catalog
                       </a>
                       <a href="/products/categories" class="p-4 rounded"
                           :class="isActive('/products/categories') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                           Categories
                       </a>
                   </div>

                   <!-- Projects Menu -->
                   <a @click="openMenu = (openMenu === 'project' ? null : 'project')"
                       class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                       :class="isActive('/projects') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                       <div class="flex items-center gap-2">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                        </svg>
                        <span>Project</span>
                    </div>
                    <svg x-show="openMenu !== 'project'" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                    <svg x-show="openMenu === 'project'" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                    </svg>
                </a>

                <!-- Projects Submenu -->
                <div x-show="openMenu === 'project'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="flex flex-col gap-1 mt-1">
                    <a href="/projects" class="p-4 rounded"
                        :class="isActive('/projects') && !window.location.pathname.includes('/timeline') && !window.location.pathname.includes('/status') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                        Project List
                    </a>
                    <a href="/projects/timeline" class="p-4 rounded"
                        :class="isActive('/projects/timeline') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                        Project Timeline
                    </a>
                    <a href="/projects/status" class="p-4 rounded"
                        :class="isActive('/projects/status') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                        Project Status
                    </a>
                </div>
            @endif

            @if(Auth::user()->role === 'Admin')
                <!-- Reports Menu -->
                <a @click="openMenu = (openMenu === 'report' ? null : 'report')"
                    class="flex justify-between relative items-center py-2 px-3 my-1 font-medium rounded-md transition-colors"
                    :class="isActive('/reports') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-black'">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                        <span>Report</span>
                    </div>
                    <svg x-show="openMenu !== 'report'" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                    <svg x-show="openMenu === 'report'" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                    </svg>
                </a>

                <!-- Reports Submenu -->
                <div x-show="openMenu === 'report'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="flex flex-col gap-1 mt-1">
                    <a href="/reports/marketing" class="p-4 rounded"
                        :class="isActive('/reports/marketing') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 text-gray-600'">
                        Marketing Report
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer User Profile Section -->
        <div class="border-t p-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center w-full hover:bg-gray-50 rounded-lg p-2 transition-colors duration-150">
                    <div class="w-10 h-10 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                        {{ $userInitials }}
                    </div>
                    <div class="overflow-hidden ml-3 flex-1">
                        <h4 class="font-semibold text-sm">{{ $userName }}</h4>
                        <span class="text-xs text-gray-600">{{ $userEmail }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-180': open }"
                        class="h-5 w-5 text-gray-400 ml-2 transition-transform duration-200" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="open" @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute bottom-full left-0 w-full mb-1 bg-white rounded-lg shadow-lg border py-1">
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</aside>
</div>