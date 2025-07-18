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
                    @include('admin.errors')
                    <h2 class="text-xl font-semibold mb-4">Assessment</h2>

                    <form action="{{ route('trainer.assessment.store') }}" method="POST" onsubmit="return prepareSubmission()">
                            @csrf
                            <input type="hidden" name="trainer_id" value="{{ Auth()->user()->id }}" readonly >

                            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                                <!-- Create assessment section -->
                                <div>
                                    <h3 class="text-md font-semibold mb-4">Create assessment</h3>

                                    <!-- Assessment Title -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Assessment title</label>
                                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter assessment title"
                                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"  />
                                        @error('title')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Assessment Description -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Assessment description</label>
                                        <textarea name="description" class="w-full p-3 text-sm border rounded focus:outline-none" rows="4"
                                            placeholder="Enter description...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Assessment Level -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-1">Assessment level</label>
                                        <input type="text" name="level" value="{{ old('level') }}" placeholder="Select assessment level"
                                            class="w-full border rounded px-3 py-2"  />
                                        @error('level')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Total / Passing Question / Percentage -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <!-- Total Questions -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Total questions</label>
                                            <input type="text"
                                                id="total_questions"
                                                name="total_questions"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10); calculatePercentage();"
                                                value="{{ old('total_questions') }}"
                                                placeholder="Enter total questions"
                                                class="w-full border rounded px-3 py-2" />
                                            <p id="totalQuestionError" class="text-red-600 text-sm mt-1 hidden">Mismatch with added questions.</p>
                                            @error('total_questions')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>


                                        <!-- Passing Questions -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Passing questions</label>
                                            <input type="text"
                                                id="passing_questions"
                                                name="passing_questions"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10); calculatePercentage();"
                                                value="{{ old('passing_questions') }}"
                                                placeholder="Enter passing questions"
                                                class="w-full border rounded px-3 py-2" />
                                            @error('passing_questions')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                            <p id="questionError" class="text-red-600 text-sm mt-1 hidden">Passing questions cannot exceed total questions.</p>
                                        </div>

                                        <!-- Passing Percentage -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Passing percentage</label>
                                            <input type="text"
                                                id="passing_percentage"
                                                name="passing_percentage"
                                                readonly
                                                value="{{ old('passing_percentage') }}"
                                                class="w-full border rounded px-3 py-2 bg-gray-100" />
                                            @error('passing_percentage')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- JavaScript -->
                                    <script>
                                        function calculatePercentage() {
                                            const total = parseInt(document.getElementById('total_questions').value) || 0;
                                            const passing = parseInt(document.getElementById('passing_questions').value) || 0;
                                            const percentageField = document.getElementById('passing_percentage');
                                            const errorText = document.getElementById('questionError');

                                            if (passing > total) {
                                                percentageField.value = '';
                                                errorText.classList.remove('hidden');
                                            } else {
                                                errorText.classList.add('hidden');
                                                if (total > 0) {
                                                    const percentage = ((passing / total) * 100).toFixed(2);
                                                    percentageField.value = percentage + '%';
                                                } else {
                                                    percentageField.value = '';
                                                }
                                            }
                                        }
                                    </script>

                                </div>

                                <!-- Create questionnaire -->
                                <div>
                                    <h3 class="text-md font-semibold mb-4">Create questionnaire</h3>

                                    <div class="container bg-white p-4 rounded shadow-sm border" id="questionnaire">
                                        <h5 class="mb-3 fw-semibold">Create Questionnaire</h5>

                                        <!-- Hidden input for questions JSON -->
                                        <input type="hidden" name="questions" id="questionsInput" value="{{ old('questions') }}">
                                        @error('questions')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror

                                        <!-- Question Input -->
                                        <div class="d-flex mb-3 gap-2">
                                            <input type="text" id="questionText" class="form-control" placeholder="Write your question here ...">
                                        </div>

                                        <!-- Error Message -->
                                        <div id="errorContainer" class="text-danger mb-3" style="display: none;"></div>

                                        <!-- Options List -->
                                        <div id="optionsContainer" class="mb-3"></div>

                                        <!-- Add Option -->
                                        <div class="mb-2">
                                            <button type="button" id="addOptionBtn" class="btn btn-default">+ Add option</button>
                                        </div>

                                        <!-- Submit -->
                                        <div>
                                            <button type="button" id="submitBtn" class="btn btn-primary">+ Add question</button>
                                        </div>


                                        <!-- Submitted Preview -->
                                        <div class="mt-4" id="submittedContainer"></div>
                                    </div>
                                </div>

                                <!-- Final Create Button -->
                                <div>
                                    <button type="submit" class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800">
                                        Create assessment
                                    </button>
                                </div>
                            </div>
                    </form>


                    <script>
                        // Restore old questions if present
                        document.addEventListener('DOMContentLoaded', () => {
                            const oldQuestionsJson = document.getElementById('questionsInput').value;
                            if (oldQuestionsJson) {
                                try {
                                    const restoredQuestions = JSON.parse(oldQuestionsJson);
                                    if (Array.isArray(restoredQuestions)) {
                                        submittedQuestions = restoredQuestions;
                                        renderSubmitted();
                                    }
                                } catch (err) {
                                    console.error('Failed to parse old questions JSON:', err);
                                }
                            }
                        });
                    </script>
                    <script>
                        const questionText = document.getElementById('questionText');
                        const optionsContainer = document.getElementById('optionsContainer');
                        const addOptionBtn = document.getElementById('addOptionBtn');
                        const submitBtn = document.getElementById('submitBtn');
                        const submittedContainer = document.getElementById('submittedContainer');
                        const errorContainer = document.getElementById('errorContainer');
                        const questionsInput = document.getElementById('questionsInput');

                        let options = [];
                        let submittedQuestions = [];

                        // Show inline error message
                        function showError(message) {
                            errorContainer.innerText = message;
                            errorContainer.style.display = 'block';
                        }

                        // Clear error message
                        function clearError() {
                            errorContainer.innerText = '';
                            errorContainer.style.display = 'none';
                        }

                        // Create a new option input
                        function createOption(text = '', correct = false) {
                            const index = options.length;

                            const wrapper = document.createElement('div');
                            wrapper.className = 'd-flex align-items-center mb-2 gap-2';

                            const input = document.createElement('input');
                            input.type = 'text';
                            input.placeholder = `Option ${String.fromCharCode(65 + index)}`;
                            input.className = 'form-control';
                            input.value = text;
                            input.addEventListener('input', clearError);

                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = correct ? 'btn btn-success' : 'btn btn-outline-secondary';
                            button.innerText = correct ? 'Correct' : 'Mark correct option';
                            button.style.width = '150px'; // adjust as needed

                            button.onclick = () => {
                                options.forEach((opt, i) => opt.correct = i === index);
                                renderOptions();
                                clearError();
                            };

                            wrapper.appendChild(input);
                            wrapper.appendChild(button);

                            options.push({ wrapper, input, correct });
                            optionsContainer.appendChild(wrapper);
                        }

                        // Render all options to reflect correct answer status
                        function renderOptions() {
                            optionsContainer.innerHTML = '';
                            options.forEach((opt, i) => {
                                opt.input.placeholder = `Option ${String.fromCharCode(65 + i)}`;
                                const btn = opt.wrapper.querySelector('button');
                                const isCorrect = opt.correct;
                                btn.className = isCorrect ? 'btn btn-success' : 'btn btn-outline-secondary';
                                btn.innerText = isCorrect ? '✔ Correct' : 'Mark correct option';
                                optionsContainer.appendChild(opt.wrapper);
                            });
                        }

                        // Reset the form after submission
                        function resetForm() {
                            questionText.value = '';
                            options = [];
                            optionsContainer.innerHTML = '';
                            for (let i = 0; i < 3; i++) createOption();
                            renderOptions();
                            clearError();
                        }

                        // Render submitted questions in preview box
                        function renderSubmitted() {
                            submittedContainer.innerHTML = '';
                            submittedQuestions.forEach((q, index) => {
                                const div = document.createElement('div');
                                div.className = 'border rounded p-3 mb-2 bg-light';

                                const header = document.createElement('div');
                                header.className = 'd-flex justify-content-between mb-1';

                                const title = document.createElement('div');
                                title.innerHTML = `<strong>Q:</strong> ${q.text}`;

                                const removeBtn = document.createElement('button');
                                removeBtn.className = 'btn btn-sm btn-link text-danger p-0';
                                removeBtn.innerText = '✕ Remove';
                                removeBtn.onclick = () => {
                                    submittedQuestions.splice(index, 1);
                                    renderSubmitted();
                                };

                                header.appendChild(title);
                                header.appendChild(removeBtn);
                                div.appendChild(header);

                                const ul = document.createElement('ul');
                                ul.className = 'ps-4';
                                q.options.forEach(opt => {
                                    const li = document.createElement('li');
                                    li.innerHTML = `${opt.text} ${opt.correct ? '<span class="text-success fw-bold">(Correct)</span>' : ''}`;
                                    ul.appendChild(li);
                                });

                                div.appendChild(ul);
                                submittedContainer.appendChild(div);
                            });
                        }

                        // Add option button click
                        addOptionBtn.addEventListener('click', () => {
                            createOption();
                            renderOptions();
                            clearError();
                        });

                        // Submit question to local array
                        submitBtn.addEventListener('click', () => {
                            const question = questionText.value.trim();
                            if (!question) {
                                showError('Please enter a question.');
                                return;
                            }

                            const filledOptions = options.map(opt => ({
                                text: opt.input.value.trim(),
                                correct: opt.correct
                            })).filter(opt => opt.text !== '');

                            if (filledOptions.length < 2) {
                                showError('Please enter at least two options.');
                                return;
                            }

                            if (!filledOptions.some(opt => opt.correct)) {
                                showError('Please mark a correct option.');
                                return;
                            }

                            clearError();

                            submittedQuestions.push({ text: question, type: 'mcq', options: filledOptions });
                            renderSubmitted();
                            resetForm();
                        });

                        // Initial setup
                        resetForm();
                        questionText.addEventListener('input', clearError);

                        // Before final form submit - populate hidden input
                        function prepareSubmission() {
                            document.getElementById('total_questions').value = submittedQuestions.length;
                            const totalQuestionsInput = document.getElementById('total_questions');
                            const totalQuestions = parseInt(totalQuestionsInput.value) || 0;
                            const questionsInput = document.getElementById('questionsInput');
                            const totalQuestionError = document.getElementById('totalQuestionError');
                            const errorContainer = document.getElementById('errorContainer');

                            // Check if any question has been added
                            if (submittedQuestions.length === 0) {
                                showError('Please add at least one question to the assessment.');
                                totalQuestionError.classList.add('hidden');
                                return false;
                            }

                            // Check if total questions match
                            if (submittedQuestions.length !== totalQuestions) {
                                totalQuestionError.innerText = `Total questions (${totalQuestions}) must match number of created questions (${submittedQuestions.length}).`;
                                totalQuestionError.classList.remove('hidden');
                                showError('Fix mismatch in total questions before submitting.');
                                return false;
                            }

                            // All OK
                            totalQuestionError.classList.add('hidden');
                            clearError();
                            questionsInput.value = JSON.stringify(submittedQuestions);
                            return true;
                        }


                    </script>


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