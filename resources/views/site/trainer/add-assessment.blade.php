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
        <div class="flex h-screen">
          
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

                <main class="p-6 bg-gray-50 min-h-screen">
                    <h2 class="text-xl font-semibold mb-4">Assessment</h2>

                    <div class="bg-white rounded-lg shadow p-6 space-y-6">
                        <!-- Create assessment section -->
                        <div>
                        <h3 class="text-md font-semibold mb-4">Create assessment</h3>

                        <!-- Assessment Title -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Assessment title</label>
                            <input type="text" placeholder="Enter assessment title"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        </div>

                        <!-- Assessment Description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Assessment description</label>
                            <div class="border rounded">
                            <textarea class="w-full p-3 text-sm focus:outline-none" rows="4"
                                placeholder="Enter description..."></textarea>
                            </div>
                        </div>

                        <!-- Assessment Level -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Assessment level</label>
                            <input type="text" placeholder="Select assessment level"
                            class="w-full border rounded px-3 py-2" />
                        </div>

                        <!-- Total / Passing Question / Percentage -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                            <label class="block text-sm font-medium mb-1">Total question</label>
                            <input type="number" placeholder="Select total question"
                                class="w-full border rounded px-3 py-2" />
                            </div>
                            <div>
                            <label class="block text-sm font-medium mb-1">Passing question</label>
                            <input type="number" placeholder="Select passing question"
                                class="w-full border rounded px-3 py-2" />
                            </div>
                            <div>
                            <label class="block text-sm font-medium mb-1">Passing percentage</label>
                            <input type="text" value="80%" class="w-full border rounded px-3 py-2" />
                            </div>
                        </div>
                        </div>
                        <!-- Create questionnaire -->
                        <div>
                        <h3 class="text-md font-semibold mb-4">Create questionnaire</h3>

                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                        <div x-data="questionBuilder()" class="p-6 bg-white rounded space-y-4">
                            <!-- Add Question -->
                            <div class="flex items-center space-x-2">
                                <input x-model="questionText" type="text" placeholder="Write your question here ..."
                                    class="flex-1 border rounded px-3 py-2" />
                                <select x-model="type" class="border rounded px-2 py-2 text-sm">
                                    <option value="">Select type</option>
                                    <option value="mcq">Multiple Choice</option>
                                    <option value="truefalse">True / False</option>
                                    <option value="short">Short Answer</option>
                                </select>
                            </div>

                            <!-- Question Type Based Content -->
                            <template x-if="type === 'mcq'">
                                <div class="space-y-2">
                                    <template x-for="(option, index) in options" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <input x-model="option.text" type="text" :placeholder="`Option ${String.fromCharCode(65+index)}`"
                                                class="flex-1 border rounded px-3 py-2" />
                                            <button @click="markCorrect(index)"
                                                :class="option.correct ? 'bg-green-600 text-white' : 'bg-gray-100 border'"
                                                class="text-sm px-3 py-2 rounded">
                                                <template x-if="option.correct">✓ Correct</template>
                                                <template x-if="!option.correct">Mark correct</template>
                                            </button>
                                            <button @click="removeOption(index)" class="text-red-500 text-sm">✕</button>
                                        </div>
                                    </template>
                                    <button @click="addOption()" class="text-blue-600 text-sm mt-2">+ Add option</button>
                                </div>
                            </template>

                            <!-- True / False -->
                            <template x-if="type === 'truefalse'">
                                <div class="space-y-2">
                                    <template x-for="(option, index) in trueFalseOptions" :key="index">
                                        <div class="flex items-center space-x-2">
                                            <span x-text="option.text" class="flex-1 border px-3 py-2 rounded bg-gray-50"></span>
                                            <button @click="markTF(index)"
                                                :class="option.correct ? 'bg-green-600 text-white' : 'bg-gray-100 border'"
                                                class="text-sm px-3 py-2 rounded">
                                                <template x-if="option.correct">✓ Correct</template>
                                                <template x-if="!option.correct">Mark correct</template>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Short Answer -->
                            <template x-if="type === 'short'">
                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="Short answer (no options)" disabled>
                            </template>

                            <!-- Submit Question -->
                            <button @click="submitQuestion()" class="mt-4 bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800">
                                + Add question
                            </button>

                            <!-- Preview Area -->
                            <div class="mt-6">
                                <h3 class="font-semibold text-lg mb-2">Submitted Questions:</h3>
                                <template x-for="(q, i) in submitted" :key="i">
                                    <div class="border rounded p-3 mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <p class="font-medium">Q: <span x-text="q.text"></span> (<span x-text="q.type"></span>)</p>
                                            <button @click="removeSubmitted(i)" class="text-red-600 text-sm hover:underline">✕ Remove</button>
                                        </div>
                                        <template x-if="q.type === 'mcq'">
                                            <ul class="list-disc pl-6">
                                                <template x-for="opt in q.options">
                                                    <li>
                                                        <span x-text="opt.text"></span>
                                                        <span x-show="opt.correct" class="text-green-600 font-bold"> (Correct)</span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                        <template x-if="q.type === 'truefalse'">
                                            <ul class="list-disc pl-6">
                                                <template x-for="opt in q.options">
                                                    <li>
                                                        <span x-text="opt.text"></span>
                                                        <span x-show="opt.correct" class="text-green-600 font-bold"> (Correct)</span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <script>
                            function questionBuilder() {
                                return {
                                    type: '',
                                    questionText: '',
                                    options: [
                                        { text: '', correct: false },
                                        { text: '', correct: false }
                                    ],
                                    trueFalseOptions: [
                                        { text: 'True', correct: false },
                                        { text: 'False', correct: false }
                                    ],
                                    submitted: [],
                                    addOption() {
                                        this.options.push({ text: '', correct: false });
                                    },
                                    removeOption(index) {
                                        this.options.splice(index, 1);
                                    },
                                    markCorrect(index) {
                                        this.options.forEach((opt, i) => opt.correct = i === index);
                                    },
                                    markTF(index) {
                                        this.trueFalseOptions.forEach((opt, i) => opt.correct = i === index);
                                    },
                                    submitQuestion() {
                                        if (!this.questionText || !this.type) {
                                            alert("Please enter a question and select a type.");
                                            return;
                                        }
                                        let question = {
                                            text: this.questionText,
                                            type: this.type,
                                            options: []
                                        };
                                        if (this.type === 'mcq') {
                                            question.options = JSON.parse(JSON.stringify(this.options));
                                        } else if (this.type === 'truefalse') {
                                            question.options = JSON.parse(JSON.stringify(this.trueFalseOptions));
                                        }
                                        this.submitted.push(question);
                                        this.reset();
                                    },
                                    removeSubmitted(index) {
                                        this.submitted.splice(index, 1);
                                    },
                                    reset() {
                                        this.questionText = '';
                                        this.type = '';
                                        this.options = [
                                            { text: '', correct: false },
                                            { text: '', correct: false }
                                        ];
                                        this.trueFalseOptions = [
                                            { text: 'True', correct: false },
                                            { text: 'False', correct: false }
                                        ];
                                    }
                                }
                            }
                        </script>


                        </div>

                        <!-- Final Create Button -->
                        <div>
                        <button class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800">
                            Create assessment
                        </button>
                        </div>
                    </div>
                    </main>




            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          



            </div>
        </div>
    </div>
           



          


<script  src="js/jquery-3.6.0.min.js"></script><!-- JQUERY.MIN JS -->
<script  src="js/popper.min.js"></script><!-- POPPER.MIN JS -->
<script  src="js/bootstrap.min.js"></script><!-- BOOTSTRAP.MIN JS -->
<script  src="js/magnific-popup.min.js"></script><!-- MAGNIFIC-POPUP JS -->
<script  src="js/waypoints.min.js"></script><!-- WAYPOINTS JS -->
<script  src="js/counterup.min.js"></script><!-- COUNTERUP JS -->
<script  src="js/waypoints-sticky.min.js"></script><!-- STICKY HEADER -->
<script  src="js/isotope.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/imagesloaded.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/owl.carousel.min.js"></script><!-- OWL  SLIDER  -->
<script  src="js/theia-sticky-sidebar.js"></script><!-- STICKY SIDEBAR  -->
<script  src="js/lc_lightbox.lite.js" ></script><!-- IMAGE POPUP -->
<script  src="js/bootstrap-select.min.js"></script><!-- Form js -->
<script  src="js/dropzone.js"></script><!-- IMAGE UPLOAD  -->
<script  src="js/jquery.scrollbar.js"></script><!-- scroller -->
<script  src="js/bootstrap-datepicker.js"></script><!-- scroller -->
<script  src="js/jquery.dataTables.min.js"></script><!-- Datatable -->
<script  src="js/dataTables.bootstrap5.min.js"></script><!-- Datatable -->
<script  src="js/chart.js"></script><!-- Chart -->
<script  src="js/bootstrap-slider.min.js"></script><!-- Price range slider -->
<script  src="js/swiper-bundle.min.js"></script><!-- Swiper JS -->
<script  src="js/custom.js"></script><!-- CUSTOM FUCTIONS  -->
<script  src="js/switcher.js"></script><!-- SHORTCODE FUCTIONS  -->


</body>


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:18:30 GMT -->
</html>
