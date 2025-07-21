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
                @include('site.trainer.componants.navbar')

                <main class="p-6 max-h-[900px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                    <h2 class="text-xl font-semibold mb-6">Recorded course</h2>
                    <form action="{{ route('trainer.training.recorded.save.data') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Course Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Title</label>
                            <input type="text" name="training_title" placeholder="Enter the Course Title" class="w-full border rounded-md p-2" />
                            @error('training_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Sub Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Sub Title</label>
                            <input type="text" name="training_sub_title" placeholder="Enter the Sub Title" class="w-full border rounded-md p-2" />
                            @error('training_sub_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Description</label>
                            <textarea name="training_descriptions" placeholder="Enter the Description" class="w-full border rounded-md p-2 h-24"></textarea>
                            @error('training_descriptions')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @php
                            $categories = App\Models\TrainingCategory::all();
                        @endphp
                        <!-- Category (Single selection with radio buttons) -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Category</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($categories as $category)
                                    <label>
                                        <input type="radio" name="training_category" value="{{ $category->category }}" class="mr-2" /> {{ $category->category }}
                                    </label>
                                @endforeach
                            </div>
                            @error('training_category')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Training Level -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block font-medium mb-1">Training Level</label>
                                <select name="training_level" class="w-full border rounded-md p-2">
                                    <option value="">Select Training Level</option>
                                    <option value="Beginner" {{ old('training_level', $training->training_level ?? '') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ old('training_level', $training->training_level ?? '') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ old('training_level', $training->training_level ?? '') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                @error('training_level')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>



                        <!-- Course Content Structure -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Course Content Structure</label>
                            <div class="mb-2">
                                <input id="sectionTitle" name="content_sections[0][title]" type="text" placeholder="Section title" class="w-full border rounded-md p-2" />
                            </div>
                            <div class="mb-2">
                                <textarea id="contentText" name="content_sections[0][description]" placeholder="Contents" class="w-full border rounded-md p-2"></textarea>
                            </div>
                            <button id="addContentBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Content</button>
                        </div>

                        <!-- Course Video Table (Dynamic row append) -->
                        <div class="bg-white p-4 rounded-md shadow mb-4">
                            <h3 class="text-md font-semibold mb-2">Course Videos</h3>
                            <div class="overflow-x-auto">
                                <table id="courseTable" class="w-full text-sm text-left border">
                                    <thead class="bg-gray-100">
                                        <tr class="text-gray-700">
                                            <th class="p-2 border">Sr. No.</th>
                                            <th class="p-2 border">Title</th>
                                            <th class="p-2 border">Description</th>
                                            <th class="p-2 border">Upload</th>
                                            <th class="p-2 border">File Duration</th>
                                            <th class="p-2 border">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--  rows dynamically JavaScript add  -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Thumbnail Upload -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Upload Thumbnail</label>
                            <div class="flex gap-4 items-center">
                                <input type="file" accept="image/*" name="thumbnail" class="border rounded-md p-2 flex-1" />
                            </div>
                        </div>

                        <!-- Course Price and Offer Price -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Course Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Price</label>
                                <input type="text" name="training_price" placeholder="Enter Course Price" class="w-full border rounded-md p-2" />
                                @error('training_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Offer Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Offer Price</label>
                                <input type="text" name="training_offer_price" placeholder="Enter Offer Price" class="w-full border rounded-md p-2" />
                                @error('training_offer_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="text-right">
                            <button type="submit" class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                                Submit
                            </button>
                        </div>
                    </form>
                </main>



            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script>
                const otherCheckbox = document.getElementById('other-checkbox');
                const otherTextInput = document.getElementById('other-text');
                const categoryCheckboxes = document.querySelectorAll('.category-checkbox');

                otherCheckbox.addEventListener('change', () => {
                    if (otherCheckbox.checked) {
                        // Uncheck all other checkboxes and hide their effect
                        categoryCheckboxes.forEach(cb => {
                            cb.checked = false;
                        });
                        otherTextInput.style.display = 'block';
                        otherTextInput.focus();
                    } else {
                        otherTextInput.style.display = 'none';
                        otherTextInput.value = '';
                    }
                });

                categoryCheckboxes.forEach(cb => {
                    cb.addEventListener('change', () => {
                        if (cb.checked) {
                            // If any other checkbox is checked, uncheck "Others" and hide text input
                            otherCheckbox.checked = false;
                            otherTextInput.style.display = 'none';
                            otherTextInput.value = '';
                        }
                    });
                });
            </script>
            <!-- <script>
                const addBtn = document.getElementById('addContentBtn');
                const titleInput = document.getElementById('sectionTitle');
                const contentInput = document.getElementById('contentText');
                const tableBody = document.querySelector('#courseTable tbody');

                addBtn.addEventListener('click', () => {
                    const title = titleInput.value.trim();
                    const content = contentInput.value.trim();

                    if (!title || !content) {
                        alert('Please enter both Section Title and Contents.');
                        return;
                    }

                    const rowCount = tableBody.rows.length + 1;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="p-2 border">${rowCount}</td>
                        <td class="p-2 border font-medium">${title}</td>
                        <td class="p-2 border text-sm text-gray-600">${content}</td>
                        <td class="p-2 border text-center">
                            <button class="upload-btn text-blue-600 px-2 py-1 border rounded-md cursor-pointer">
                                Upload File
                            </button>
                            <input type="file" style="display:none" />
                        </td>
                        <td class="p-2 border text-center">
                            <button class="text-red-600 delete-btn" aria-label="Delete row">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6 2a2 2 0 00-2 2v1H2v2h1v9a2 2 0 002 2h10a2 2 0 002-2V7h1V5h-2V4a2 2 0 00-2-2H6zm0 3h8v1H6V5zm1 3v7h2V8H7zm4 0v7h2V8h-2z" />
                                </svg>
                            </button>
                        </td>
                    `;

                    tableBody.appendChild(tr);

                    titleInput.value = '';
                    contentInput.value = '';

                    updateSerialNumbers();
                });

                tableBody.addEventListener('click', (e) => {
                    if (e.target.closest('.delete-btn')) {
                        e.target.closest('tr').remove();
                        updateSerialNumbers();
                    } else if (e.target.closest('.upload-btn')) {
                        const btn = e.target.closest('.upload-btn');
                        const fileInput = btn.nextElementSibling;
                        fileInput.click();
                    }
                });

                tableBody.addEventListener('change', (e) => {
                    if (e.target.type === 'file') {
                        const fileInput = e.target;
                        const fileName = fileInput.files.length ? fileInput.files[0].name : 'Upload File';
                        const btn = fileInput.previousElementSibling;
                        btn.textContent = fileName;
                    }
                });

                function updateSerialNumbers() {
                    [...tableBody.rows].forEach((row, i) => {
                        row.cells[0].textContent = i + 1;
                    });
                }
            </script> -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const addBtn = document.getElementById('addContentBtn');
                    const titleInput = document.getElementById('sectionTitle');
                    const contentInput = document.getElementById('contentText');
                    const tableBody = document.querySelector('#courseTable tbody');

                    let index = 0;

                    addBtn.addEventListener('click', (e) => {
                        e.preventDefault();

                        const title = titleInput.value.trim();
                        const content = contentInput.value.trim();

                        if (!title || !content) {
                            alert('Please enter both Section Title and Contents.');
                            return;
                        }

                        const rowCount = tableBody.rows.length + 1;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="p-2 border">${rowCount}</td>
                            <td class="p-2 border font-medium">
                                ${title}
                                <input type="hidden" name="content_sections[${index}][title]" value="${title}">
                            </td>
                            <td class="p-2 border text-sm text-gray-600">
                                ${content}
                                <input type="hidden" name="content_sections[${index}][description]" value="${content}">
                            </td>
                            <td class="p-2 border text-center">
                                <button type="button" class="upload-btn text-blue-600 px-2 py-1 border rounded-md cursor-pointer">Upload File</button>
                                <input accept="video/*" type="file" name="content_sections[${index}][file]" style="display:none" />
                            </td>
                            <td class="p-2 border text-center duration-cell">
                                --
                                <input type="hidden" name="content_sections[${index}][file_duration]" value="">
                            </td>
                            <td class="p-2 border text-center">
                                <button type="button" class="text-red-600 delete-btn" aria-label="Delete row">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a2 2 0 00-2 2v1H2v2h1v9a2 2 0 002 2h10a2 2 0 002-2V7h1V5h-2V4a2 2 0 00-2-2H6zm0 3h8v1H6V5zm1 3v7h2V8H7zm4 0v7h2V8h-2z" />
                                    </svg>
                                </button>
                            </td>
                        `;

                        tableBody.appendChild(tr);

                        titleInput.value = '';
                        contentInput.value = '';
                        index++;

                        updateSerialNumbers();
                    });

                    tableBody.addEventListener('click', (e) => {
                        if (e.target.closest('.delete-btn')) {
                            e.target.closest('tr').remove();
                            updateSerialNumbers();
                        } else if (e.target.closest('.upload-btn')) {
                            const btn = e.target.closest('.upload-btn');
                            const fileInput = btn.nextElementSibling;
                            fileInput.click();
                        }
                    });

                    tableBody.addEventListener('change', (e) => {
                        if (e.target.type === 'file') {
                            const fileInput = e.target;
                            const file = fileInput.files[0];
                            const btn = fileInput.previousElementSibling;

                            if (file) {
                                btn.textContent = file.name;

                                const video = document.createElement('video');
                                video.preload = 'metadata';

                                video.onloadedmetadata = function () {
                                    window.URL.revokeObjectURL(video.src);
                                    const durationInSeconds = video.duration;

                                    const minutes = Math.floor(durationInSeconds / 60);
                                    const seconds = Math.floor(durationInSeconds % 60).toString().padStart(2, '0');
                                    const formatted = `${minutes}:${seconds}`;

                                    const tr = fileInput.closest('tr');
                                    const durationCell = tr.querySelector('.duration-cell');
                                    const hiddenInput = durationCell.querySelector('input');

                                    durationCell.innerHTML = `
                                        ${formatted}
                                        <input type="hidden" name="${hiddenInput.name}" value="${formatted}">
                                    `;
                                };

                                video.src = URL.createObjectURL(file);
                            }
                        }
                    });

                    function updateSerialNumbers() {
                        [...tableBody.rows].forEach((row, i) => {
                            row.cells[0].textContent = i + 1;
                        });
                    }
                });
                </script>





            </div>
        </div>
    </div>
           



          @include('site.trainer.componants.footer')