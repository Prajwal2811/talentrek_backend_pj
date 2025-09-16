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

                <main class="p-6 bg-gray-100 min-h-screen">
                    <h2 class="text-2xl font-semibold mb-6">Training Programs List</h2>
                     @include('admin.errors')
                    <!-- Tab Buttons -->
                    <div class="mb-6 border-b border-gray-200">
                        <div class="flex space-x-6 text-sm font-medium">
                            <button onclick="switchTab('recorded')" class="tab-btn text-gray-600 pb-2" id="btn-recorded">Recorded Lecture</button>
                            <button onclick="switchTab('online')" class="tab-btn text-gray-600 pb-2" id="btn-online">Online Training</button>
                            <button onclick="switchTab('offline')" class="tab-btn text-gray-600 pb-2" id="btn-offline">Offline Training</button>
                        </div>
                    </div>

                    <!-- Tab Contents -->
                    <div id="tab-recorded" class="tab-content hidden">
                        <h3 class="text-xl font-semibold mb-4">Recorded Trainings</h3>

                        @if ($recordedTrainings->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow-md text-sm">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs leading-normal">
                                        <tr>
                                            <th class="px-6 py-3 text-left">Sr. No.</th>
                                            <th class="px-6 py-3 text-left">Title</th>
                                            <th class="px-6 py-3 text-left">Price</th>
                                            <th class="px-6 py-3 text-left">Offer Price</th>
                                            <th class="px-6 py-3 text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach ($recordedTrainings as $training)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-3">{{ $training->training_title }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_offer_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">
                                                    <a href="{{ route('trainer.training.recorded.edit', $training->id) }}"
                                                    class="bg-blue-500 text-white px-4 py-1.5 rounded-md text-xs font-medium hover:bg-blue-600 transition">
                                                    Edit
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No recorded trainings found.</p>
                        @endif
                    </div>



                    <div id="tab-online" class="tab-content hidden">
                        <h3 class="text-xl font-semibold mb-4">Online Trainings</h3>

                        @if ($onlineTrainings->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow-md text-sm">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs leading-normal">
                                        <tr>
                                            <th class="px-6 py-3 text-left">Sr. No.</th>
                                            <th class="px-6 py-3 text-left">Title</th>
                                            <th class="px-6 py-3 text-left">Price</th>
                                            <th class="px-6 py-3 text-left">Offer Price</th>
                                            <th class="px-6 py-3 text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach ($onlineTrainings as $training)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-3">{{ $training->training_title }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_offer_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">
                                                    <a href="{{ route('trainer.training.online.edit', $training->id) }}"
                                                    class="bg-blue-500 text-white px-4 py-1.5 rounded-md text-xs font-medium hover:bg-blue-600 transition">
                                                    Edit
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No online trainings found.</p>
                        @endif
                    </div>


                    <div id="tab-offline" class="tab-content hidden">
                        <h3 class="text-xl font-semibold mb-4">Offline Trainings</h3>

                        @if ($offlineTrainings->count())
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white rounded-lg shadow-md text-sm">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs leading-normal">
                                        <tr>
                                            <th class="px-6 py-3 text-left">Sr. No.</th>
                                            <th class="px-6 py-3 text-left">Title</th>
                                            <th class="px-6 py-3 text-left">Price</th>
                                            <th class="px-6 py-3 text-left">Offer Price</th>
                                            <th class="px-6 py-3 text-left">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach ($offlineTrainings as $training)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-6 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-3">{{ $training->training_title }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">{{ number_format($training->training_offer_price ?? 0, 2) }}</td>
                                                <td class="px-6 py-3">
                                                    <a href="{{ route('trainer.training.online.edit', $training->id) }}"
                                                    class="bg-blue-500 text-white px-4 py-1.5 rounded-md text-xs font-medium hover:bg-blue-600 transition">
                                                    Edit
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No offline trainings found.</p>
                        @endif
                    </div>

                </main>

                <!-- Minimal JavaScript for tab switch -->
                <script>
                    function switchTab(tab) {
                        const tabs = ['recorded', 'online', 'offline'];

                        tabs.forEach(name => {
                            // Hide all tabs
                            document.getElementById(`tab-${name}`).classList.add('hidden');
                            document.getElementById(`btn-${name}`).classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                            document.getElementById(`btn-${name}`).classList.add('text-gray-600');
                        });

                        // Show selected tab
                        document.getElementById(`tab-${tab}`).classList.remove('hidden');
                        document.getElementById(`btn-${tab}`).classList.remove('text-gray-600');
                        document.getElementById(`btn-${tab}`).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    }

                    // Show recorded tab by default
                    switchTab('recorded');
                </script>




            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          



            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           


@include('site.trainer.componants.footer')