@include('site.componants.header')
<body>
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>
	<style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>
      @include('site.componants.navbar')

  <div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Assessment.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Assessment</h2>
                    </div>
                </div>
            </div>
        </div>


            <main class="w-11/12 mx-auto py-8 min-h-screen flex items-center justify-center">
                <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8 w-full">
                    <div class="flex-1">
                    <section class="flex flex-col items-center justify-center text-center">
                        <!-- Success Icon -->
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <!-- Success Message -->
                        <h2 class="text-xl font-medium mb-1">Your Appointment Booked Successfully</h2>
                        <p class="text-sm text-gray-600 mb-1">Booking ID: <span class="font-semibold">#2354677</span></p>

                        <!-- Date and Time -->
                        <div class="text-sm text-gray-600 mb-6">
                            <p>September 25</p>
                            <p>10:45 AM</p>
                        </div>

                        <!-- Zoom Link -->
                        <div class="flex items-center w-full max-w-md bg-gray-100 rounded-md px-4 py-2 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2H4m16 4V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2z" />
                            </svg>
                            <input type="text" readonly value="https://zoom.us/j/1234567890" class="flex-1 bg-transparent text-sm outline-none" />
                            <button class="bg-blue-700 text-white text-sm font-medium px-4 py-1 rounded hover:bg-blue-800 ml-2">
                            Copy
                            </button>
                        </div>
                        </section>


                


               
              </div>

            </div>
            </main>

        </div>

@include('site.componants.footer')