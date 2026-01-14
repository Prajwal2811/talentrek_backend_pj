@php
    use App\Models\JobseekerTrainingMaterialPurchase;

    $courses = JobseekerTrainingMaterialPurchase::select(
        'training_batches.*',
        'jobseeker_training_material_purchases.*',
        'jobseeker_training_material_purchases.id as purchase_id',
        'team_course_members.id as team_member_id'
    )
        ->with(['material.reviews'])
        ->join('training_batches', 'training_batches.training_material_id', '=', 'jobseeker_training_material_purchases.material_id')
        ->leftJoin('team_course_members', function ($join) {
            $join->on('team_course_members.training_material_purchases_id', '=', 'jobseeker_training_material_purchases.id');
        })
        ->where(function ($query) {
            $query->where('jobseeker_training_material_purchases.jobseeker_id', auth()->id())
                ->orWhere('team_course_members.jobseeker_id', auth()->id());
        })
        ->get();


    // Optional: fetch trainer image for main purchaser or member
    $trainerImage = App\Models\AdditionalInfo::where('doc_type', 'profile_picture')
        ->where('user_id', auth()->id())
        ->first();
@endphp


<!-- Training Tab -->
<div x-show="tab === 'training'" x-cloak>
    <h2 class="text-xl font-semibold mb-4">Training</h2>

    @forelse($courses->unique('material_id') as $index => $purchase)
    @php
        $material = $purchase->material;
        $reviews = $material->reviews ?? collect();
        $trainerName = App\Models\Trainers::where('id', $material->trainer_id)->value('name');
        $batchCount = App\Models\TrainingBatch::where('training_material_id', $material->id)->count();
        // $batchData = App\Models\TrainingBatch::where('training_material_id', $material->id)->where('batch_no',$purchase->batch_no)->first();
        $duration = App\Models\TrainingBatch::where('training_material_id', $material->id)->sum('duration');
        $lessons = $material->lesson_count;
        $duration = $material->duration;
        $level = $material->training_level;
        $rating = $material->rating;
        $img = $material->thumbnail_file_path;
        $batchData = App\Models\TrainingBatch::where('training_material_id', $material->id)
            ->where('batch_no', $purchase->batch_no)
            ->first();

        $isAssessmentAvailable = false;

        $expatId = auth()->guard('expat')->id();
        $assessment = App\Models\TrainerAssessment::where('material_id', $material->id)->first();

        $endDate = null;
        $isAssessmentAvailable = false;
        $assessmentTaken = false;

        if ($batchData && $batchData->start_date && $batchData->duration) {
            $endDate = \Carbon\Carbon::parse($batchData->start_date)->addDays($batchData->duration);
            $isAssessmentAvailable = now()->gt($endDate); // assessment is available after batch ends
        }

        if ($assessment) {
            $assessmentTaken = App\Models\JobseekerAssessmentStatus::where('jobseeker_id', $expatId)
                ->where('assessment_id', $assessment->id)
                ->where('submitted', 1)
                ->exists();
        }
    @endphp

    <div class="flex items-start border rounded-md shadow-sm overflow-hidden mb-4">
        <!-- Course Image -->
        <img src="{{ asset($img) }}" alt="{{ $material->training_title }}" class="w-48 h-48 object-cover" />

        <!-- Content -->
        <div class="p-4 flex-1">
            <!-- Title + Description -->
            <div class="flex items-center justify-between">
                <a href="{{ route('course.details', ['id' => $material->id]) }}">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $material->training_title }}</h2>
                </a>

                <!-- 👇 Button Display Logic -->
                <div class="dropdown">
                    @if($assessment)
                        <button class="btn " type="button" id="assessmentDropdown{{ $assessment->id }}"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="assessmentDropdown{{ $assessment->id }}">
                            @if ($assessmentTaken)
                                <li>
                                    <a class="dropdown-item text-green-600"
                                        href="{{ route('jobseeker.assessment.result', $assessment->id) }}">
                                        View Score
                                    </a>
                                </li>
                            @elseif ($isAssessmentAvailable)
                                <li>
                                    <a class="dropdown-item text-yellow-600" href="#" data-bs-toggle="modal"
                                        data-bs-target="#assessmentModal">
                                        Take Assessment
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item text-blue-600" href="">
                                        Join Training
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @elseif(!($assessment) && $material->training_type != 'classroom')
                        <button class="btn " type="button" id="assessmentDropdown{{ $material->id }}"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="assessmentDropdown{{ $material->id }}">
                            <li>
                                <a target="_blank" class="dropdown-item text-blue-600"
                                    href="{{ $batchData->zoom_join_url }}">
                                    Join Training
                                </a>
                            </li>
                        </ul>
                    @elseif($material->training_type === 'classroom')
                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            id="assessmentDropdown{{ $material->id }}">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="assessmentDropdown{{ $material->id }}">
                            <li>
                                <!-- Trigger modal on click -->
                                <a href="#" class="dropdown-item text-blue-600" data-bs-toggle="modal"
                                    data-bs-target="#trainerAddressModal{{ $material->id }}">
                                    Classroom Address
                                </a>
                            </li>

                        </ul>
                        <!-- Trainer Address Modal -->
                        <div class="modal fade" id="trainerAddressModal{{ $material->id }}" tabindex="-1"
                            aria-labelledby="trainerAddressLabel{{ $material->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                style="max-width: 500px;">
                                <div class="modal-content border-0 shadow">
                                    <!-- Modal Header -->
                                    <div class="modal-header bg-light text-dark">
                                        <h5 class="modal-title" id="trainerAddressLabel{{ $material->id }}">Trainer Address
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body text-sm">
                                        <p><strong>Name:</strong>
                                            {{ App\Models\Trainers::where('id', $material->trainer_id)->value('name') }}</p>
                                        <p><strong>Phone Number:</strong>
                                            {{ App\Models\Trainers::where('id', $material->trainer_id)->value('phone_number') ?? 'Not available' }}
                                        </p>
                                        <p><strong>Location:</strong>
                                            {{ App\Models\Trainers::where('id', $material->trainer_id)->value('city') ?? 'Not available' }}
                                        </p>
                                    </div>

                                    <!-- Modal Footer with Close Button -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    @endif

                </div>
                <!-- Assessment Modal -->
                <div class="modal fade" id="assessmentModal" tabindex="-1" aria-labelledby="assessmentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assessmentModalLabel">Assessment Instructions</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                    <li>Make sure you're in a quiet environment.</li>
                                    <li>Once started, the assessment must be completed in one go.</li>
                                    <li>No external help or switching tabs.</li>
                                    <li>Timer will start once you begin.</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('assessment.view', $material->id) }}" class="btn btn-primary">Start
                                    Assessment</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <p class="text-sm text-gray-600 mt-1">
                {{ $material->training_sub_title ?? 'No description available.' }}
            </p>

            @php
                $averageRating = $reviews->avg('ratings');
                $roundedRating = round($averageRating);
            @endphp
            <!-- Rating -->
            <div class="flex items-center text-sm mt-2 space-x-2">
                <div class="text-yellow-500 text-base">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $roundedRating)
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </div>
                <span class="text-gray-500">({{ number_format($averageRating, 1) }}/5 from {{ $reviews->count() }}
                    review{{ $reviews->count() === 1 ? '' : 's' }})</span>
                {{-- <span class="text-gray-700 font-medium">Rating</span> --}}
            </div>

            <!-- Metadata -->
            <div class="flex items-center justify-between mt-4 text-sm text-gray-700">
                <!-- Instructor -->
                <div class="flex items-center space-x-2">
                    <img src="{{ $trainerImage->document_path ?? asset('default-avatar.png') }}"
                        alt="{{ $trainerName }}" class="w-6 h-6 rounded-full">
                    <span>{{ $trainerName }}</span>
                </div>

                <!-- Lessons -->
                <div class="flex items-center space-x-1">
                    <i class="ph ph-file-text"></i>
                    <span>Enrolled in <strong> {{ $purchase->batch_no }}</strong> batch</span>
                </div>

                <!-- Time -->
                <div class="flex items-center space-x-1">
                    <i class="ph ph-clock"></i>
                    {{-- <span>{{ $duration }}</span> --}}
                </div>

                <!-- Level -->
                <div class="flex items-center space-x-1">
                    <i class="ph ph-activity"></i>
                    <span class="text-bold">{{ $level }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@php
    if (!function_exists('renderSessionCard')) {
        function renderSessionCard($user, $session, $index, $type)
        {
            $reviews = $user?->reviews ?? collect();
            $experiences = $user?->experiences ?? collect();
            $averageRating = $reviews->avg('ratings') ?? 0;
            $totalReviews = $reviews->count();
            $roundedRating = round($averageRating);
            $stars = str_repeat('★', $roundedRating) . str_repeat('☆', 5 - $roundedRating);
            $currentExp = $experiences->firstWhere('end_to', null) ?? $experiences->sortByDesc('end_to')->first();
            $designation = $currentExp?->job_role ?? 'No designation available';
            $zoomLink = $session->zoom_join_url ?? null;
            $slotMode = $session->slot_mode;
            $address = $user?->address ?? 'Address not available';

            echo '
                                        <div class="flex items-start border-b pb-4 mb-4 space-x-4">
                                            <img src="' . $user?->profilePicture?->document_path . '" alt="' . ucfirst($type) . '" class="w-24 h-24 rounded-full object-cover">
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-900">' . $user?->name . '</h3>
                                                        <p class="text-sm text-gray-500 mt-1">' . $designation . '</p>
                                                        <div class="flex items-center mt-2 text-sm space-x-2">
                                                            <div class="text-yellow-500 text-base">' . $stars . '</div>
                                                            <span class="text-gray-500">(' . number_format($averageRating, 1) . '/5 from ' . $totalReviews . ' reviews)</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 shrink-0">';
            if ($slotMode === 'online' && $zoomLink) {
                echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#zoomModal' . $type . $index . '">Join Meet</button>';
            } elseif ($slotMode === 'offline') {
                echo '<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal' . $type . $index . '">View Address</button>';
            } else {
                echo '<p class="text-red-500 text-sm">Link not available</p>';
            }
            echo '</div>
                                                </div>
                                            </div>
                                        </div>';

            // Zoom Modal
            if ($slotMode === 'online' && $zoomLink) {
                echo '
                                            <div class="modal fade" id="zoomModal' . $type . $index . '" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Join Zoom Meeting with ' . $user->name . '</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Click the link below to join the meeting:</p>
                                                            <a href="' . $zoomLink . '" target="_blank" class="text-blue-600 font-medium underline break-all">' . $zoomLink . '</a>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                            <a href="' . $zoomLink . '" target="_blank" class="btn btn-primary btn-sm">Join Now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
            }

            // Address Modal
            if ($slotMode === 'offline') {
                echo '
                                            <div class="modal fade" id="addressModal' . $type . $index . '" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">' . ucfirst($type) . '\'s Address</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-gray-800">' . $address . '</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
            }
        }
    }
@endphp