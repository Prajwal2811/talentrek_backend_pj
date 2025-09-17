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
                        <h2 class="text-3xl font-bold text-white ml-[10%]">{{ langLabel('profile') }}</h2>
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

                        <!-- Quiz App -->
                        <div x-data="quizApp()" x-init="init()" class="min-h-screen p-6">
                            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Questions Panel -->
                                <div class="lg:col-span-2 space-y-6">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-800">{{ $assessment->questions_title }}</h2>
                                        <p class="text-gray-600 text-sm mt-2">{{ langLabel('answer_the_following_question') }}</p>
                                    </div>

                                    <!-- Questions -->
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div x-show="current === index" class="bg-white rounded border p-6 shadow-sm">
                                            <h3 class="text-md font-semibold text-gray-800 mb-2" x-text="q.question"></h3>
                                            <p class="text-sm text-gray-500 mb-4">{{ langLabel('select_one_option') }}</p>

                                            <div class="space-y-3">
                                                <template x-for="(opt, i) in q.options" :key="i">
                                                    <div
                                                        class="px-4 py-2 rounded border cursor-pointer text-sm"
                                                        :class="{
                                                            'bg-blue-100 border-blue-500 text-blue-800': selectedAnswers[current] === i && !quizSubmitted,
                                                            'bg-blue-50 hover:bg-blue-100': selectedAnswers[current] !== i && !quizSubmitted
                                                        }"
                                                        @click="selectAnswer(i)"
                                                    >
                                                        <span x-text="opt"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Navigation -->
                                    <div class="flex justify-between items-center">
                                        <p class="text-sm text-gray-500" x-text="`${current + 1} of ${questions.length} questions`"></p>
                                        <button
                                            @click="nextQuestion"
                                            :disabled="selectedAnswers[current] === null || quizSubmitted"
                                            :class="{
                                                'bg-blue-600 text-white hover:bg-blue-700': selectedAnswers[current] !== null,
                                                'bg-gray-300 text-gray-500 cursor-not-allowed': selectedAnswers[current] === null || quizSubmitted
                                            }"
                                            class="text-sm font-medium px-5 py-2 rounded transition"
                                        >
                                            {{ langLabel('next_question') }} &gt;
                                        </button>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div class="bg-white rounded border p-6 shadow-sm space-y-4">
                                    <!-- Progress -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">{{ langLabel('quiz_tracking') }}</h4>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                            <div class="bg-orange-500 h-1.5 rounded-full" :style="`width: ${progress}%`"></div>
                                        </div>
                                        <p class="text-sm text-gray-800 font-semibold" x-text="`${progress}%`"></p>
                                    </div>

                                   <!-- Timer -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">{{ langLabel('time_remaining') }}</h4>
                                        <p class="text-lg font-bold text-red-600" x-text="formattedTime"></p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ langLabel('total_quiz_time') }}: {{ $assessment->time_per_question * $assessment->total_questions }} minutes
                                        </p>
                                    </div>


                                    <!-- Navigation Buttons -->
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ langLabel('quiz_navigation') }}</h4>
                                        <div class="grid grid-cols-5 gap-2">
                                            <template x-for="(q, index) in questions" :key="index">
                                                <button
                                                    @click="current = index"
                                                    class="w-10 h-10 text-sm rounded border font-semibold"
                                                    :class="{
                                                        'bg-blue-600 text-white': current === index,
                                                        'bg-green-100 border-green-500 text-green-800': savedAnswers.includes(index),
                                                        'bg-red-100 border-red-500 text-red-800': selectedAnswers[index] !== null && !savedAnswers.includes(index),
                                                        'bg-yellow-100 border-yellow-500 text-yellow-800': selectedAnswers[index] === null
                                                    }"
                                                    x-text="index + 1"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Error Message -->
                                    <div x-show="errorMessage" x-transition class="text-red-600 text-sm text-center font-medium">
                                        <p x-text="errorMessage"></p>
                                    </div>

                                    <!-- Finish Button (opens modal) -->
                                    <button
                                        @click="confirmSubmit = true"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded mt-2"
                                    >
                                        {{ langLabel('finish_quiz') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Submit Modal -->
                            <div x-show="confirmSubmit" x-transition class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 text-center">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ langLabel('are_you_sure_quiz') }}</h2>
                                    <div class="flex justify-center gap-4 mt-6">
                                        <button @click="confirmSubmit = false"
                                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                            {{ langLabel('cancel') }}
                                        </button>
                                        <button @click="finalSubmit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            {{ langLabel('yes') }}, {{ langLabel('submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alpine.js Script -->
                        <script>
                            function quizApp() {
                                return {
                                    current: 0,
                                    quizSubmitted: false,
                                    confirmSubmit: false,
                                    questions: @json($quizQuestions),
                                    selectedAnswers: @json($quizQuestions->pluck('selected_index')),
                                    savedAnswers: [],
                                    errorMessage: '',
                                    remainingTime: {{ $remainingTime }},
                                    timerInterval: null,

                                    init() {
                                        // Prevent multiple intervals
                                        if (this.timerInterval) clearInterval(this.timerInterval);

                                        this.startTimer();

                                        // Saved answers check
                                        this.questions.forEach((q, i) => {
                                            if (this.selectedAnswers[i] !== null) {
                                                this.savedAnswers.push(i);
                                            }
                                        });

                                        // Agar tab close/logout ho to last time save ho jaye
                                        window.addEventListener('beforeunload', () => {
                                            if (!this.quizSubmitted) {
                                                navigator.sendBeacon("{{ route('jobseeker.updateRemainingTime') }}", new URLSearchParams({
                                                    _token: '{{ csrf_token() }}',
                                                    material_id: this.questions[0]?.material_id,
                                                    remaining_time: this.remainingTime
                                                }));
                                            }
                                        });
                                    },

                                    get formattedTime() {
                                        const h = String(Math.floor(this.remainingTime / 3600)).padStart(2, '0');
                                        const m = String(Math.floor((this.remainingTime % 3600) / 60)).padStart(2, '0');
                                        const s = String(this.remainingTime % 60).padStart(2, '0');
                                        return `${h}:${m}:${s}`;
                                    },

                                    startTimer() {
                                        this.timerInterval = setInterval(() => {
                                            // ⏱️ Smooth countdown (1 sec decrement)
                                            this.remainingTime = Math.max(this.remainingTime - 1, 0);

                                            // API call every (60 sec)
                                            if (this.remainingTime > 0 && this.remainingTime % 60 === 0) {
                                                $.post("{{ route('jobseeker.updateRemainingTime') }}", {
                                                    _token: '{{ csrf_token() }}',
                                                    material_id: this.questions[0]?.material_id,
                                                    remaining_time: this.remainingTime
                                                });
                                            }

                                            
                                            if (this.remainingTime <= 0) {
                                                clearInterval(this.timerInterval);
                                                this.finalSubmit();
                                            }
                                        }, 1000); 
                                    },

                                    selectAnswer(index) {
                                        if (!this.quizSubmitted) {
                                            this.selectedAnswers[this.current] = index;
                                        }
                                    },

                                    nextQuestion() {
                                        const q = this.questions[this.current];
                                        const selectedIndex = this.selectedAnswers[this.current];
                                        if (selectedIndex === null || this.quizSubmitted) return;

                                        $.post("{{ route('jobseeker.saveAnswer') }}", {
                                            _token: '{{ csrf_token() }}',
                                            trainer_id: q.trainer_id,
                                            material_id: q.material_id,
                                            assessment_id: q.assessment_id,
                                            question_id: q.id,
                                            selected_answer: q.options[selectedIndex],
                                            correct_answer: q.correct_option,
                                        }, () => {
                                            if (!this.savedAnswers.includes(this.current)) {
                                                this.savedAnswers.push(this.current);
                                            }
                                            if (this.current < this.questions.length - 1) {
                                                this.current++;
                                            } else {
                                                this.errorMessage = "Answer saved. You may finish the quiz.";
                                                setTimeout(() => this.errorMessage = '', 3000);
                                            }
                                        });
                                    },

                                    finalSubmit() {
                                        this.confirmSubmit = false;
                                        const attempted = this.selectedAnswers.filter(a => a !== null).length;
                                        const required = this.questions[0]?.passingQuestions || 0;

                                        if (attempted < required) {
                                            this.errorMessage = `You must attempt at least ${required} questions. You have attempted ${attempted}.`;
                                            return;
                                        }

                                        const allAnswers = this.questions.map((q, i) => ({
                                            trainer_id: q.trainer_id,
                                            material_id: q.material_id,
                                            assessment_id: q.assessment_id,
                                            question_id: q.id,
                                            selected_answer: q.options[this.selectedAnswers[i]],
                                            correct_answer: q.correct_option
                                        }));

                                        $.post("{{ route('jobseeker.submitQuiz') }}", {
                                            _token: '{{ csrf_token() }}',
                                            answers: allAnswers
                                        }, () => {
                                            this.quizSubmitted = true;
                                            clearInterval(this.timerInterval);
                                            this.errorMessage = `Quiz Submitted!`;
                                            setTimeout(() => window.location.href = '{{ route("jobseeker.profile") }}', 2000);
                                        });
                                    }
                                }
                            }
                            </script>


                    </div>
                </div>
            </main>

            <script>
                function disableRefresh() {
                    // Disable F5 and Ctrl+R
                    window.addEventListener('keydown', function (e) {
                        if ((e.key === 'F5') || (e.ctrlKey && e.key === 'r')) {
                            e.preventDefault();
                        }
                    });

                    // Disable right-click reload option (context menu)
                    window.addEventListener('contextmenu', function (e) {
                        // optional: allow context menu but prevent reload
                        // e.preventDefault();
                    });

                    // Disable browser refresh via beforeunload
                    window.addEventListener('beforeunload', function (e) {
                        e.preventDefault();
                        e.returnValue = ''; // standard for most browsers
                    });
                }

                // Call this inside your Alpine.js init
                document.addEventListener('alpine:init', () => {
                    disableRefresh();
                });
            </script>

        {{-- @endif --}}



    









        







    @include('site.jobseeker.componants.footer')






