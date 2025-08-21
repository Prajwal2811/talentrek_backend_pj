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

    @include('site.componants.navbar')	

   
        <div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset//images/banner/service page banner.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold text-white ml-[10%]">Profile</h2>
                    </div>
                </div>
            </div>
        </div>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <main class="w-11/12 mx-auto py-8">
                <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
                    <div class="flex-1">
                        <!-- Assessment Header -->
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->assessment_title }}</h1>
                            <p class="text-gray-500 text-sm mt-2">{{ $assessment->assessment_description }}</p>
                        </div>

                        <!-- Quiz Result App -->
                        <div x-data="quizApp()" x-init="init()" class="min-h-screen p-6">
                            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Questions Panel -->
                                <div class="lg:col-span-2 space-y-6">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800">Assessment Results</h2>
                                        <p class="text-gray-600 text-sm mt-2">Review your answers below. Correct answers are shown in green.</p>
                                    </div>

                                    <!-- Questions -->
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div x-show="current === index" class="bg-white rounded border p-6 shadow-sm">
                                            <h3 class="text-md font-semibold text-gray-800 mb-2" x-text="q.question"></h3>
                                            <p class="text-sm text-gray-500 mb-4">Your selected answer is highlighted below.</p>

                                            <div class="space-y-3">
                                                <template x-for="(opt, i) in q.options" :key="i">
                                                    <div
                                                        class="px-4 py-2 rounded border text-sm font-medium flex justify-between items-center"
                                                        :class="{
                                                            'bg-green-600 border-green-700 text-white': i === q.correct_index,
                                                            'bg-red-600 border-red-700 text-white': selectedAnswers[index] === i && i !== q.correct_index,
                                                            'bg-gray-100 border-gray-300 text-gray-600': selectedAnswers[index] !== i && i !== q.correct_index
                                                        }"
                                                    >
                                                        <span x-text="opt"></span>

                                                        <!-- Correct Icon -->
                                                        <i x-show="i === q.correct_index" class="fas fa-check-circle text-white text-lg"></i>

                                                        <!-- Wrong Icon -->
                                                        <i x-show="selectedAnswers[index] === i && i !== q.correct_index" class="fas fa-times-circle text-white text-lg"></i>
                                                    </div>
                                                </template>

                                            </div>
                                        </div>
                                    </template>

                                    <!-- Navigation -->
                                    <div class="flex justify-between items-center mt-4">
                                        <p class="text-sm text-gray-500" x-text="`${current + 1} of ${questions.length} questions`"></p>
                                        <button
                                            @click="current < questions.length - 1 ? current++ : current = 0"
                                            class="text-sm font-medium px-5 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 transition"
                                        >
                                            Next Question &gt;
                                        </button>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div class="bg-white rounded border p-6 shadow-sm space-y-4">
                                    <!-- Performance Progress Bar -->
                                @php
                                        $correctAnswers = $score; // Use actual correct count if available
                                        $rawPercentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
                                        $percentage = min(100, max(0, round($rawPercentage))); // ensure 0–100%

                                        $passingPercent = ($assessment->passing_questions * 100) / max(1, $totalQuestions);
                                        $progressColor = $percentage >= $passingPercent ? 'bg-green-600' : 'bg-red-600';
                                    @endphp

                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">Performance</h4>
                                        <div class="w-full bg-gray-200 rounded-full h-3 mb-1">
                                            <div class="{{ $progressColor }} h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <p class="text-sm text-gray-800 font-semibold">{{ $percentage }}%</p>
                                    </div>

                                    <!-- Jump to Question -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Jump to Question</h4>
                                        <div class="grid grid-cols-5 gap-2">
                                            <template x-for="(q, index) in questions" :key="index">
                                                <button
                                                    @click="current = index"
                                                    class="w-10 h-10 text-sm rounded border font-semibold"
                                                    :class="{
                                                        'bg-blue-700 text-white': current === index,
                                                        'bg-green-600 text-white border-green-700': selectedAnswers[index] === q.correct_index,
                                                        'bg-red-600 text-white border-red-700': selectedAnswers[index] !== null && selectedAnswers[index] !== q.correct_index
                                                    }"
                                                    x-text="index + 1"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alpine.js Script -->
                            <script>
                                function quizApp() {
                                    return {
                                        current: 0,
                                        questions: @json($quizQuestions),
                                        selectedAnswers: @json($quizQuestions->pluck('selected_index')),
                                        init() {
                                            // No setup required for read-only result
                                        },
                                        get progress() {
                                            return Math.round(((this.current + 1) / this.questions.length) * 100);
                                        }
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </main>




        </div>

    @include('site.jobseeker.componants.footer')
