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

	 @if($trainerNeedsSubscription)
        @include('site.trainer.subscription.index')
    @endif
    
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                <h2 class="text-2xl font-semibold mb-6">Add Training</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl">
                    <!-- Online / Classroom Training -->
                    <a href="{{route('training.online.add')}}" class="bg-blue-800 text-white rounded-lg p-10 flex flex-col items-center justify-center hover:bg-blue-700 transition">
                    <svg class="w-8 h-8 mb-4" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M4 5h16v10H4V5zm0 12h6v2H4v-2zm10 0h6v2h-6v-2z" />
                    </svg>
                    <span class="text-center">Online / Classroom Training</span>
                    </a>

                    <!-- Recorded Courses -->
                    <a href="{{route('training.recorded.add')}}" class="bg-blue-800 text-white rounded-lg p-10 flex flex-col items-center justify-center hover:bg-blue-700 transition">
                    <svg class="w-8 h-8 mb-4" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                    <span class="text-center">Recorded Courses</span>
                    </a>
                </div>
            </main>


            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          



            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           


@include('site.trainer.componants.footer')