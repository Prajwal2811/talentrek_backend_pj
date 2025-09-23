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
                <h2 class="text-2xl font-semibold mb-6">{{ langLabel('notifications') }}</h2>
                <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <tr>
                                    <th class="px-6 py-3">Sr. No.</th>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Created Date/Time</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody id="assessmentTableBody">
                            
                             @php $i = 1; $notifications = notificationsAll('trainer'); @endphp
                            @foreach($notifications as $notification)
                                <tr class="border-t assessment-row">
                                    <td class="px-6 py-4">{{ $i++ }}</td>
                                    <td class="px-6 py-4">{{ $notification->sender_type }}</td>
                                    <td class="px-6 py-4">{{ $notification->message }}</td>
                                    
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = strtolower($notification->is_read_users);
                                            if($status == 1){
                                                $stu = 'Read';
                                            }else{
                                                    $stu = 'Unread';
                                            }
                                            $badgeClass = match($status) {
                                                '1' => 'success',
                                                '0' => 'danger',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} text-light">
                                            {{ ucfirst($stu) }}
                                        </span>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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