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
        <div class="relative bg-center bg-cover h-[400px] flex items-center"
            style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-5xl font-bold text-white ml-[10%]">{{ langLabel('training') }}</h2>
                </div>
            </div>
        </div>
    </div>


    <script>
        function toggleSection(id, iconId) {
            const section = document.getElementById(id);
            const icon = document.getElementById(iconId);
            section.classList.toggle('hidden');
            if (icon.classList.contains('rotate-180')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.add('rotate-180');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    text: 'Go to your profile to continue learning.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Go to Profile'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('jobseeker.profile') }}';
                    }
                });
            });
        </script>
    @endif


    @php
use Carbon\Carbon;
use App\Models\TrainingBatch;
use App\Models\JobseekerTrainingMaterialPurchase;
use App\Models\JobseekerAssessmentStatus;

$existOrNot = false;
$enableJoin = false;
$showAssessment = false;
$batch = null;
$assessmentStatus = null;
$now = Carbon::now();

if(auth('jobseeker')->check()){
    $jobseekerId = auth('jobseeker')->id();

    // Check if the course is purchased
    $purchase = JobseekerTrainingMaterialPurchase::where('jobseeker_id', $jobseekerId)
        ->where('material_id', $material->id)
        ->first();

    if($purchase){
        $existOrNot = true;

        if($purchase->batch_id){
            $batch = TrainingBatch::find($purchase->batch_id);

            if($batch){
                $batchDays = json_decode($batch->days, true) ?? [];
                $dayName = $now->format('l');

                $startDate = Carbon::parse($batch->start_date)->startOfDay();
                $endDate = $batch->end_date ? Carbon::parse($batch->end_date)->endOfDay() : $startDate;

                $startTime = Carbon::parse($batch->start_timing)->subMinutes(10);
                $endTime = Carbon::parse($batch->end_timing);

                if($endTime->lessThan($startTime)){
                    $endTime->addDay();
                }

                $startDateTime = Carbon::parse($now->format('Y-m-d').' '.$startTime->format('H:i:s'));
                $endDateTime = Carbon::parse($now->format('Y-m-d').' '.$endTime->format('H:i:s'));

                if($now->between($startDate, $endDate) && in_array($dayName, $batchDays)){
                    if($now->between($startDateTime, $endDateTime)){
                        $enableJoin = true;
                    }
                }

                $isPastBatch = $now->greaterThan($endDate);
                $isEndDayAndTimeOver = $now->isSameDay($endDate) && $now->greaterThan($endDateTime);
                $showAssessment = $isPastBatch || $isEndDayAndTimeOver;
            }
        }

        // Fetch assessment status
        $assessmentStatus = JobseekerAssessmentStatus::where('jobseeker_id', $jobseekerId)
            ->where('material_id', $material->id)
            ->latest()
            ->first();
    }
}
@endphp



        <main class="w-11/12 mx-auto py-8">
            @include('admin.errors')
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">

                <!-- Left/Main Content -->
                <div class="flex-1">
                    <!-- Title -->
                    <h1 class="text-2xl font-semibold text-gray-900 mb-1">
                        {{ $material->training_title ?? 'N/A' }}
                    </h1>
                    <p class="text-sm text-gray-600 mb-3">{{ $material->training_sub_title ?? '' }}</p>

                    <!-- Ratings and Meta -->
                    <div class="flex items-center text-sm text-gray-600 mb-6 flex-wrap gap-2">
                        <div class="flex items-center text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= floor($average) ? '★' : '☆' }}
                            @endfor
                        </div>
                        <span>({{ $average }}/5)</span>
                        <span>{{ langLabel('rating') }}</span>
                        <span class="mx-2">|</span>

                        <!-- Trainer -->
                        <div class="flex items-center gap-2">
                            <img src="{{ $material->user_profile }}" alt="Trainer Image"
                                class="w-10 h-10 rounded-full object-cover">
                            <span class="font-semibold">{{ $material->user_name }}</span>
                        </div>

                        <span class="mx-2">|</span>

                        <!-- Lessons and Hours -->
                        @if(strtolower($material->training_type) === 'recorded' && isset($material->documents) && count($material->documents) > 0)
                            <span>📘 {{ count($material->documents) }} {{ langLabel('lessons') }}</span>
                            <span>⏱️
                                @php
                                    $totalHours = 0;
                                    foreach ($material->batches as $batch) {
                                        $start = strtotime($batch->start_timing);
                                        $end = strtotime($batch->end_timing);
                                        $totalHours += ($end - $start) / 3600;
                                    }
                                @endphp
                                {{ number_format($totalHours, 1) }} hrs
                            </span>
                        @endif

                        <span>📈 {{ ucfirst($material->training_level ?? langLabel('beginner')) }}</span>
                        <span>🎥 {{ ucfirst($material->session_type ?? langLabel('recorded')) }}</span>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-6 border-b mb-6 text-sm font-medium">
                        @if ($material->training_type !== 'online')
                            <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent"
                                data-tab="content">{{ langLabel('training_content') }}</button>
                        @endif
                        <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent"
                            data-tab="reviews">{{ langLabel('reviews') }}</button>
                    </div>

                    <!-- Tab Contents -->
                    <section class="mb-6 tab-content hidden" data-tab-content="content">
                        <h2 class="text-lg font-semibold mb-2">{{ langLabel('training_content') }}</h2>
                        <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
                            @foreach($material->documents as $index => $doc)
                                <li><strong>Lesson {{ $index + 1 }}:</strong> {{ $doc->description }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="mb-6 tab-content" data-tab-content="reviews">
                        <h2 class="text-lg font-semibold mb-2">{{ langLabel('reviews') }}</h2>
                        <p class="text-sm text-gray-600">{{ langLabel('user_reviews') }}</p>
                    </section>



                                    


                    <!-- Tab JS -->
                    <script>
                        document.querySelectorAll('.tab-link').forEach(button => {
                            button.addEventListener('click', () => {
                                const tab = button.dataset.tab;

                                document.querySelectorAll('.tab-link').forEach(btn => {
                                    btn.classList.remove('text-blue-600', 'border-blue-600', 'active-tab');
                                    btn.classList.add('text-gray-600');
                                });
                                button.classList.remove('text-gray-600');
                                button.classList.add('text-blue-600', 'border-blue-600', 'active-tab');

                                document.querySelectorAll('.tab-content').forEach(content => {
                                    content.classList.add('hidden');
                                });
                                document.querySelector(`[data-tab-content="${tab}"]`).classList.remove('hidden');
                            });
                        });
                    </script>
                </div>

                <!-- Sidebar -->
                <aside class="w-full lg:w-1/3 lg:sticky top-12 self-start">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border p-4">
                        <img src="{{ asset($material->thumbnail_file_path ?? 'asset/images/gallery/pic-4.png') }}"
                            alt="Course Thumbnail" class="rounded-lg w-full h-48 object-cover mb-4">

                        <ul class="text-sm text-gray-600 mb-4 space-y-2">
                            @if(strtolower($material->training_type) === 'recorded')
                                <li>📘 {{ count($material->documents) }} {{ langLabel('lessons') }}</li>
                                <li>⏱️ {{ number_format($totalHours, 1) }} hrs</li>
                            @endif
                            <li>📈 {{ ucfirst($material->training_level ?? langLabel('beginner')) }}</li>
                        </ul>

                        <!-- Price -->
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-sm text-gray-500 font-bold">{{ langLabel('price') }}</p>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-400 line-through">
                                    SAR {{ number_format($material->training_price ?? 0, 0) }}
                                </span>
                                <span class="text-xl font-bold text-black">
                                    SAR {{ number_format($material->training_offer_price ?? 0, 0) }}
                                </span>
                            </div>
                        </div>

                        {{-- Buy Button --}}
                        @if(!$existOrNot)
                            <a href="{{ route('buy-course', ['id' => $material->id]) }}">
                                <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium mt-3">
                                    {{ langLabel('buy_course') }}
                                </button>
                            </a>
                        @endif

                        {{-- Buy for Team --}}
                        <a href="{{ route('buy-course-for-team', ['id' => $material->id]) }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium">
                                {{ langLabel('buy_team') }}
                            </button>
                        </a>

                        {{-- Add to Cart --}}
                        @if(!$existOrNot)
                            @if(!in_array($material->id, $cartItems))
                                <button class="add-to-cart-btn border border-blue-600 text-blue-600 hover:bg-blue-50 w-full py-2 rounded font-medium mb-2"
                                    data-id="{{ $material->id }}">{{ langLabel('add_cart') }}</button>
                            @else
                                <a href="{{ route('jobseeker.profile') }}" onclick="localStorage.setItem('activeTab','cart')"
                                    class="bg-orange-500 text-white py-2 w-full block text-center rounded font-medium mb-2">
                                    {{ langLabel('go_cart') }}
                                </a>
                            @endif
                        @endif
                    </aside>
                </div>

                <!-- Batch Selection Modal -->
                <div id="batch-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl mx-auto">
                        <h3 class="text-lg font-semibold mb-4">{{ langLabel('select_batch') }}</h3>

                        <div class="grid grid-cols-1 gap-4 max-h-[70vh] overflow-y-auto">
                            @forelse($material->batches as $batch)
                                @php
                                    $start = \Carbon\Carbon::parse($batch->start_date);
                                    $end = isset($batch->end_date) ? \Carbon\Carbon::parse($batch->end_date) : $start;
                                    $ended = $end->isPast();
                                    $started = $start->isPast() && !$ended;
                                    $strength = $batch->strength;
                                    $enrolled = \App\Models\JobseekerTrainingMaterialPurchase::where('batch_id', $batch->id)
                                                    ->where('material_id', $material->id)
                                                    ->count();
                                    $availableSeats = $strength - $enrolled;
                                    $isFull = $availableSeats <= 0;
                                    $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days;
                                @endphp

                                <div class="border rounded-lg p-4 flex justify-between items-center cursor-pointer hover:shadow-lg transition relative {{ $ended || $isFull ? 'bg-gray-100 cursor-not-allowed opacity-60' : 'bg-white' }}"
                                    onclick="{{ ($ended || $isFull) ? '' : 'selectBatch('.$batch->id.')' }}">
                                    <div class="flex items-center space-x-4">
                                        <input type="radio" name="batch_id" value="{{ $batch->id }}" id="batch-radio-{{ $batch->id }}" class="form-radio h-5 w-5 text-blue-600" {{ ($ended || $isFull)?'disabled':'' }}>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ $batch->batch_no }}</h4>
                                            <p class="text-gray-500 text-sm">
                                                Start: {{ $start->format('d M Y') }} <br>
                                                End: {{ $end->format('d M Y') }} <br>
                                                Timing: {{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} - {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right space-y-1">
                                        @if($ended)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Batch Ended</span>
                                        @elseif($started)
                                            <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded">Batch Started</span>
                                        @elseif($isFull)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Full</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">{{ $availableSeats }} Seats Left</span>
                                        @endif
                                        <p class="text-gray-600 text-sm">Duration: {{ $batch->duration }}</p>
                                        <p class="text-gray-600 text-sm">Days: {{ $days }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center">No batches available</p>
                            @endforelse
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button id="cancel-batch" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                                {{ langLabel('cancel') }}
                            </button>
                            <button id="confirm-batch" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                                {{ langLabel('add_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
        </main>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let selectedMaterialId = null;

            function selectBatch(batchId) {
                $('#batch-radio-' + batchId).prop('checked', true);
            }

            $(document).ready(function () {
                $('.add-to-cart-btn').on('click', function () {
                    selectedMaterialId = $(this).data('id');
                    $('#batch-modal').fadeIn().css('display','flex');
                });

                $('#cancel-batch').on('click', function () {
                    $('#batch-modal').fadeOut();
                });

                $('#confirm-batch').on('click', function () {
                    const batchId = $('input[name="batch_id"]:checked').val();
                    if (!batchId) {
                        alert('{{ langLabel("please_select_batch") }}');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('jobseeker.addtocart', ['id' => '__id__']) }}".replace('__id__', selectedMaterialId),
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            batch_id: batchId
                        },
                        success: function (res) {
                            if(res.success){
                                $('#batch-modal').fadeOut();

                                const button = $('.add-to-cart-btn[data-id="'+selectedMaterialId+'"]');
                                button
                                    .removeClass('add-to-cart-btn border-blue-600 text-blue-600 hover:bg-blue-50')
                                    .addClass('bg-orange-500 text-white')
                                    .text('{{ langLabel("go_cart") }}')
                                    .off('click')
                                    .on('click', function () {
                                        window.location.href = "{{ route('jobseeker.profile') }}";
                                    });
                            }
                        },
                        error: function () {
                            alert('Something went wrong. Please try again!');
                        }
                    });
                });

            });
        </script>






    <style>
        .active-tab {
            border-bottom-color: #2563eb;
            /* Tailwind blue-600 */
            color: #2563eb;
        }

        .swal2-sm-popup {
            width: 470px !important;
            padding: 1rem !important;
            font-size: 14px !important;
        }
    </style>
    </div>


    @include('site.componants.footer')