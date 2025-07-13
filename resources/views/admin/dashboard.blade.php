@include('admin.componants.header')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body data-theme="light">

    <div id="body" class="theme-cyan">
        <!-- Theme setting div -->
        <div class="themesetting">
        </div>
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
                <div id="main-content" class="">
                    <div class="container mx-auto">
                        <h1 class="text-4xl font-bold text-gray-800 mb-4">Talentrek Admin Dashboard</h1>
                        <p class="text-gray-600 mb-8">Monitor platform-wide user activity and growth.</p>

                        <!-- User Type Cards -->
                       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Jobseekers</p>
                                <h2 class="text-3xl font-bold text-blue-600">{{ $jobseekerCount }}</h2>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Recruiters</p>
                                <h2 class="text-3xl font-bold text-green-600">{{ $recruiterCount }}</h2>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Trainers</p>
                                <h2 class="text-3xl font-bold text-purple-600">{{ $trainerCount }}</h2>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Expats</p>
                                {{-- <h2 class="text-3xl font-bold text-blue-500">{{ $expatCount }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Coaches</p>
                                {{-- <h2 class="text-3xl font-bold text-red-500">{{ $coachCount }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Mentors</p>
                                <h2 class="text-3xl font-bold text-yellow-500">{{ $mentorCount }}</h2>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Assessors</p>
                                {{-- <h2 class="text-3xl font-bold text-red-500">{{ $assessorCount }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Training Material Sales</p>
                                {{-- <h2 class="text-3xl font-bold text-indigo-600">₹{{ number_format($materialSales) }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Mentor Sessions Booked</p>
                                {{-- <h2 class="text-3xl font-bold text-purple-600">{{ $mentorSessionCount }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Coach Sessions Booked</p>
                                {{-- <h2 class="text-3xl font-bold text-blue-600">{{ $coachSessionCount }}</h2> --}}
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <p class="text-gray-500">Assessor Sessions Booked</p>
                                {{-- <h2 class="text-3xl font-bold text-pink-600">{{ $assessorSessionCount }}</h2> --}}
                            </div>
                        </div>


                        <!-- Charts Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <h3 class="text-xl font-semibold mb-4 text-dark">User Role Distribution</h3>
                                <canvas id="rolePieChart"></canvas>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <h3 class="text-xl font-semibold mb-4 text-dark">Monthly Registrations by Role</h3>
                                <canvas id="registrationBarChart"></canvas>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <h3 class="text-xl font-semibold mb-4 text-dark">Session Bookings by Role</h3>
                                <canvas id="bookingBarChart"></canvas>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md">
                                <h3 class="text-xl font-semibold mb-4 text-dark">Training Material Sales</h3>
                                <canvas id="salesLineChart"></canvas>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md col-span-1 lg:col-span-2">
                                <h3 class="text-xl font-semibold mb-4 text-dark">Total Sessions Booking Trend</h3>
                                <canvas id="sessionLineChart"></canvas>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-md col-span-1 lg:col-span-2">
                                <h3 class="text-xl font-semibold mb-4 text-dark">Platform Visits Over Time</h3>
                                <canvas id="visitsLineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart.js CDN -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    // Pie Chart: Role Distribution
                    new Chart(document.getElementById('rolePieChart'), {
                        type: 'pie',
                        data: {
                            labels: ['Jobseeker', 'Recruiter', 'Trainer', 'Expat', 'Coach', 'Mentor', 'Assessor'],
                            datasets: [{
                                data: [1250, 320, 150, 90, 70, 85, 45],
                                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f97316', '#f43f5e', '#facc15', '#14b8a6']
                            }]
                        }
                    });

                    // Bar Chart: Monthly Registrations by Role
                    new Chart(document.getElementById('registrationBarChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [
                                {
                                    label: 'Jobseekers',
                                    data: [200, 220, 250, 300, 320, 310],
                                    backgroundColor: '#3b82f6'
                                },
                                {
                                    label: 'Recruiters',
                                    data: [50, 60, 55, 65, 70, 68],
                                    backgroundColor: '#10b981'
                                },
                                {
                                    label: 'Trainers',
                                    data: [20, 25, 30, 28, 32, 35],
                                    backgroundColor: '#8b5cf6'
                                }
                            ]
                        },
                        options: {
                            responsive: true
                        }
                    });

                    // Bar Chart: Session Bookings by Role
                    new Chart(document.getElementById('bookingBarChart'), {
                        type: 'bar',
                        data: {
                            labels: ['Mentor', 'Coach', 'Assessor'],
                            datasets: [{
                                label: 'Sessions Booked',
                                data: [120, 90, 60],
                                backgroundColor: ['#facc15', '#f97316', '#f43f5e']
                            }]
                        }
                    });

                    // Line Chart: Material Sales Over Time
                    new Chart(document.getElementById('salesLineChart'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Sales (₹)',
                                data: [5000, 7000, 6500, 8000, 9000, 10000],
                                borderColor: '#6366f1',
                                backgroundColor: '#a5b4fc',
                                fill: true,
                                tension: 0.4
                            }]
                        }
                    });

                    // Line Chart: Total Sessions Booked Over Time
                    new Chart(document.getElementById('sessionLineChart'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Sessions Booked',
                                data: [50, 80, 120, 100, 150, 170],
                                borderColor: '#0ea5e9',
                                backgroundColor: '#bae6fd',
                                fill: true,
                                tension: 0.4
                            }]
                        }
                    });

                    // Line Chart: Platform Visits
                    new Chart(document.getElementById('visitsLineChart'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                            datasets: [{
                                label: 'Visits',
                                data: [500, 650, 800, 720, 950, 1100, 1050],
                                borderColor: '#3b82f6',
                                backgroundColor: '#93c5fd',
                                fill: true,
                                tension: 0.4
                            }]
                        }
                    });
                </script>




        </div>
    </div>

    @include('admin.componants.footer')