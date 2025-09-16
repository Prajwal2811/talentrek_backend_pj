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

	
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                    <div class="text-xl font-bold text-blue-900 block lg:hidden">
                        Talent<span class="text-blue-500">rek</span>
                    </div>
                    <!-- <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                        <button aria-label="Notifications" class="text-gray-700 hover:text-blue-600 focus:outline-none relative">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-bell text-xl"></i>
                            </span>
                            <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
                        </button>
                        </div>
                        <div class="relative inline-block">
                        <select aria-label="Select Language" 
                                class="appearance-none border border-gray-300 rounded-md px-10 py-1 text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="en" selected>English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <!-- add more languages as needed -->
                        </select>
                        <span class="pointer-events-none absolute left-2 top-1/2 transform -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-globe"></i>
                        </span>
                        </div>
                    <div>
                        <a href="#" role="button"
                            class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span> Profile</span>
                        </a>
                    </div>
                    </div>
                </nav>

            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                <h2 class="text-2xl font-semibold mb-6">Batch List</h2>

                <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">Sr. No.</th>
                                <th class="px-6 py-3">Session Type</th>
                                <th class="px-6 py-3">Batch Name</th>
                                <th class="px-6 py-3">Course Name</th>
                                <th class="px-6 py-3">Enrolled Students</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batches as $index => $batch)
                                <tr class="border-t batch-row">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $batch->trainingMaterial->session_type}}</td>
                                    <td class="px-6 py-4">{{ $batch->batch_no }}</td>
                                    <td class="px-6 py-4">{{ $batch->trainingMaterial->training_title}}</td>
                                    <td class="px-6 py-4">-</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($batch->start_date)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} -
                                        {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center">No batches found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                </div>
                <!-- Pagination Buttons -->
                <div id="batchPagination" class="flex justify-center mt-4 gap-2"></div>
            </main>


          




            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script>
                $(document).ready(function () {
                    const itemsPerPage = 10;
                    const $entries = $('.batch-row');
                    const totalItems = $entries.length;
                    const totalPages = Math.ceil(totalItems / itemsPerPage);
                    let currentPage = 1;

                    function showPage(page) {
                        $entries.hide();
                        const start = (page - 1) * itemsPerPage;
                        const end = start + itemsPerPage;
                        $entries.slice(start, end).fadeIn(200);
                        currentPage = page;
                        updatePagination();
                    }

                    function updatePagination() {
                        $('.page-btn').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-black');
                        $(`.page-btn[data-page="${currentPage}"]`).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-black');

                        $('#prev-btn').prop('disabled', currentPage === 1);
                        $('#next-btn').prop('disabled', currentPage === totalPages);
                    }

                    function createPagination() {
                        $('#batchPagination').empty();

                        // Prev Button
                        $('#batchPagination').append(`
                            <button id="prev-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&lt;</button>
                        `);

                        // Page Buttons
                        for (let i = 1; i <= totalPages; i++) {
                            $('#batchPagination').append(`
                                <button 
                                    class="page-btn px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition" 
                                    data-page="${i}"
                                >${i}</button>
                            `);
                        }

                        // Next Button
                        $('#batchPagination').append(`
                            <button id="next-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&gt;</button>
                        `);

                        // Events
                        $('.page-btn').on('click', function () {
                            const page = $(this).data('page');
                            showPage(page);
                        });

                        $('#prev-btn').on('click', function () {
                            if (currentPage > 1) showPage(currentPage - 1);
                        });

                        $('#next-btn').on('click', function () {
                            if (currentPage < totalPages) showPage(currentPage + 1);
                        });
                    }

                    if (totalItems > 0) {
                        createPagination();
                        showPage(1);
                    } else {
                        $('#batchPagination').html('<p class="text-center text-gray-500">No batches found.</p>');
                    }
                });
                </script>

          



            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



          
@include('site.trainer.componants.footer')