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

              @php
                // Get all assigned course IDs to ensure uniqueness
                $assignedCourseIds = $assessments->pluck('material_id')->filter()->toArray();
            @endphp

            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                <h2 class="text-2xl font-semibold mb-6">Assessment List</h2>
                <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-6 py-3">Sr. No.</th>
                                <th class="px-6 py-3">Assessment Title</th>
                                <th class="px-6 py-3">Questions</th>
                                <th class="px-6 py-3">Passing Percentage</th>
                                <th class="px-6 py-3">Level</th>
                                <th class="px-6 py-3">Assign Course</th>
                            </tr>
                        </thead>
                        <tbody id="assessmentTableBody">
                            @foreach($assessments as $index => $assessment)
                                <tr class="border-t assessment-row" data-index="{{ $index }}">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $assessment->assessment_title }}</td>
                                    <td class="px-6 py-4">{{ $assessment->total_questions }} Qs</td>
                                    <td class="px-6 py-4">
                                        {{ $assessment->passing_questions }} / {{ $assessment->total_questions }}
                                        ({{ round(($assessment->passing_questions / $assessment->total_questions) * 100) }}%)
                                    </td>
                                    <td class="px-6 py-4">{{ $assessment->assessment_level }}</td>
                                    <td class="px-6 py-4">
                                        <select class="assign-course-select text-xs px-4 py-1 rounded btn btn-primary"
                                                data-assessment-id="{{ $assessment->id }}">
                                            <option disabled class="text-xs text-dark bg-white"
                                                {{ is_null($assessment->material_id) ? 'selected' : '' }}>
                                                Assign Course
                                            </option>
                                            @foreach ($courses as $course)
                                                @php
                                                    // Check if the course is already assigned to another assessment
                                                    $isAssignedToOther = in_array($course->id, $assignedCourseIds) && $assessment->material_id != $course->id;
                                                @endphp
                                                <option value="{{ $course->id }}"
                                                    {{ $assessment->material_id == $course->id ? 'selected' : '' }}
                                                    {{ $isAssignedToOther ? 'disabled' : '' }}
                                                    class="text-xs text-dark bg-white">
                                                    {{ $course->training_title }}
                                                    @if($isAssignedToOther) (Assigned) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="course-message text-sm mt-2"></div> <!-- Inline message -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="flex justify-end items-center px-6 py-4 space-x-4">
                        <button id="prevBtn" class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">Previous</button>
                        <span class="text-sm text-gray-600">
                            Page <span id="currentPageText">1</span> of <span id="totalPagesText">1</span>
                        </span>
                        <button id="nextBtn" class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">Next</button>
                    </div>
                </div>
            </main>
            <script>
                $(document).ready(function () {
                    $(document).on('change', '.assign-course-select', function () {
                        const $select = $(this);
                        const courseId = $select.val();
                        const assessmentId = $select.data('assessment-id');
                        const $messageBox = $select.siblings('.course-message');

                        $.ajax({
                            url: '{{ route("trainer.assessment.assign.course") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                assessment_id: assessmentId,
                                course_id: courseId
                            },
                            success: function (response) {
                                $messageBox
                                    .html('<span class="text-green-600 font-medium">✔ Course assigned successfully!</span>')
                                    .fadeIn()
                                    .delay(2500)
                                    .fadeOut();

                                // Optionally reload or update UI to reflect changed course availability
                                location.reload();
                            },
                            error: function () {
                                $messageBox
                                    .html('<span class="text-red-600 font-medium">✖ Error assigning course</span>')
                                    .fadeIn()
                                    .delay(2500)
                                    .fadeOut();
                            }
                        });
                    });

                    // Pagination logic
                    const perPage = 10;
                    let currentPage = 1;
                    const allRows = Array.from(document.querySelectorAll(".assessment-row"));
                    const totalPages = Math.ceil(allRows.length / perPage);

                    const updateTable = () => {
                        allRows.forEach((row, index) => {
                            row.style.display = (index >= (currentPage - 1) * perPage && index < currentPage * perPage)
                                ? ''
                                : 'none';
                        });
                        document.getElementById("currentPageText").textContent = currentPage;
                        document.getElementById("totalPagesText").textContent = totalPages;
                        document.getElementById("prevBtn").disabled = currentPage === 1;
                        document.getElementById("nextBtn").disabled = currentPage === totalPages;
                    };

                    document.getElementById("prevBtn").addEventListener("click", () => {
                        if (currentPage > 1) {
                            currentPage--;
                            updateTable();
                        }
                    });

                    document.getElementById("nextBtn").addEventListener("click", () => {
                        if (currentPage < totalPages) {
                            currentPage++;
                            updateTable();
                        }
                    });

                    updateTable(); // Initial call
                });
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