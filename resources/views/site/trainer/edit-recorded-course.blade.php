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
                <main class="p-6 max-h-[900px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                    <h2 class="text-xl font-semibold mb-6">Recorded course</h2>

                    
                    <form action="{{ route('trainer.training.recorded.update.data', $training->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Course Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Title</label>
                            <input type="text" name="training_title" placeholder="Enter the Course Title"
                                value="{{ old('training_title', $training->training_title) }}"
                                class="w-full border rounded-md p-2" />
                            @error('training_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Sub Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Sub Title</label>
                            <input type="text" name="training_sub_title" placeholder="Enter the Sub Title"
                                value="{{ old('training_sub_title', $training->training_sub_title) }}"
                                class="w-full border rounded-md p-2" />
                            @error('training_sub_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Description</label>
                            <textarea name="training_descriptions" placeholder="Enter the Description"
                                    class="w-full border rounded-md p-2 h-24">{{ old('training_descriptions', $training->training_descriptions) }}</textarea>
                            @error('training_descriptions')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category (Radio buttons) -->
                        @php
                            use App\Models\TrainingCategory;

                            $categories = TrainingCategory::all();
                            $selectedCategory = old('training_category', $training->training_category ?? '');
                        @endphp

                        <!-- Category (Single selection with radio buttons) -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Category</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($categories as $category)
                                    <label>
                                        <input type="radio" name="training_category" value="{{ $category->category }}" class="mr-2"
                                            {{ $selectedCategory === $category->category ? 'checked' : '' }} />
                                        {{ $category->category }}
                                    </label>
                                @endforeach
                            </div>
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
                                        @foreach($contentSections as $i => $section)
                                            <tr>
                                                <td class="p-2 border">{{ $loop->iteration }}</td>

                                                <td class="p-2 border font-medium">
                                                    {{ $section['title'] ?? '' }}
                                                    <input type="hidden" name="content_sections[{{ $i }}][title]" value="{{ $section['title'] ?? '' }}">
                                                </td>

                                                <td class="p-2 border text-sm text-gray-600">
                                                    {{ $section['description'] ?? '' }}
                                                    <input type="hidden" name="content_sections[{{ $i }}][description]" value="{{ $section['description'] ?? '' }}">
                                                </td>

                                                <td class="p-2 border text-center">
                                                    <button type="button" class="upload-btn text-blue-600 px-2 py-1 border rounded-md cursor-pointer">
                                                        {{ $section['file_name'] ?? 'Upload File' }}
                                                    </button>

                                                    <input type="file" name="content_sections[{{ $i }}][file]" style="display:none" />

                                                    {{--  Preserve old file --}}
                                                    @if(!empty($section['file_name']))
                                                        <input type="hidden" name="content_sections[{{ $i }}][existing_file_name]" value="{{ $section['file_name'] }}">
                                                        <input type="hidden" name="content_sections[{{ $i }}][existing_file_path]" value="{{ $section['file_path'] }}">
                                                    @endif


                                                    @if(!empty($section['file_path']))
                                                        <div class="mt-1 text-xs text-green-600">
                                                            <a href="{{ $section['file_path'] }}" target="_blank">View File</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="p-2 border text-sm text-gray-600 text-center align-middle">
                                                {{ $section['file_duration'] ?? '' }}
                                                <input type="hidden" name="content_sections[{{ $i }}][file_duration]" value="{{ $section['file_duration'] ?? '' }}">
                                            </td>

                                                <td class="p-2 border text-center">
                                                    <input type="hidden" name="content_sections[{{ $i }}][document_id]" value="{{ $section['document_id'] ?? '' }}">
                                                    <button type="button" class="text-red-600 delete-btn" aria-label="Delete row">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M6 2a2 2 0 00-2 2v1H2v2h1v9a2 2 0 002 2h10a2 2 0 002-2V7h1V5h-2V4a2 2 0 00-2-2H6zm0 3h8v1H6V5zm1 3v7h2V8H7zm4 0v7h2V8h-2z" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>



                                </table>
                            </div>
                        </div>

                        <!-- Thumbnail Upload -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Upload Thumbnail</label>

                            <div class="flex gap-4 items-center">
                                <input type="file" name="thumbnail" accept="image/*" class="border rounded-md p-2 flex-1" />
                            </div>

                            @if(!empty($training->thumbnail_file_path))
                                <div class="mt-2">
                                    <img src="{{ $training->thumbnail_file_path }}" alt="Current Thumbnail" class="h-24 rounded border" />
                                </div>
                            @endif
                        </div>


                        <!-- Course Price and Offer Price -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Course Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Price</label>
                                <input type="text" name="training_price" placeholder="Enter Course Price"
                                value="{{ old('training_price', $training->training_price) }}"
                                class="w-full border rounded-md p-2" />
                                @error('training_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Offer Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Offer Price</label>
                                <input type="text" name="training_offer_price" placeholder="Enter Offer Price"
                                value="{{ old('training_offer_price', $training->training_offer_price) }}"
                                class="w-full border rounded-md p-2" />
                                @error('training_offer_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="text-right">
                            <button type="submit" class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                                Update
                            </button>
                        </div>
                    </form>

                </main>




                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const addBtn = document.getElementById('addContentBtn');
                        const titleInput = document.getElementById('sectionTitle');
                        const contentInput = document.getElementById('contentText');
                        const tableBody = document.querySelector('#courseTable tbody');

                        let index = tableBody.querySelectorAll('tr').length;

                        // Add new content section row
                        addBtn.addEventListener('click', function (e) {
                            e.preventDefault();

                            const title = titleInput.value.trim();
                            const content = contentInput.value.trim();

                            if (!title || !content) {
                                alert('Please enter both Section Title and Contents.');
                                return;
                            }

                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="p-2 border">${tableBody.rows.length + 1}</td>
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
                                    <input type="file" name="content_sections[${index}][file]" style="display:none" />
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

                        // Delegate click events for delete and upload buttons
                        tableBody.addEventListener('click', (e) => {
                            if (e.target.closest('.delete-btn')) {
                                e.target.closest('tr').remove();
                                updateSerialNumbers();
                            } else if (e.target.closest('.upload-btn')) {
                                const btn = e.target.closest('.upload-btn');
                                const fileInput = btn.nextElementSibling;
                                if (fileInput && fileInput.type === 'file') {
                                    fileInput.click();
                                }
                            }
                        });

                        // Handle file input change event for duration extraction
                        tableBody.addEventListener('change', (e) => {
                            if (e.target.type === 'file') {
                                const fileInput = e.target;
                                const file = fileInput.files[0];
                                const btn = fileInput.previousElementSibling;

                                if (!file) return;

                                if (file.size > 20 * 1024 * 1024) {
                                    alert("File size must be less than or equal to 20MB.");
                                    fileInput.value = '';
                                    btn.textContent = 'Upload File';
                                    return;
                                }

                                btn.textContent = file.name;

                                // Calculate video duration
                                const video = document.createElement('video');
                                video.preload = 'metadata';

                                video.onloadedmetadata = function () {
                                    window.URL.revokeObjectURL(video.src);
                                    const durationInSeconds = video.duration;
                                    const minutes = Math.floor(durationInSeconds / 60);
                                    const seconds = Math.floor(durationInSeconds % 60).toString().padStart(2, '0');
                                    const formattedDuration = `${minutes}:${seconds}`;

                                    const tr = fileInput.closest('tr');
                                    const durationCell = tr.querySelector('td:nth-child(5)'); // More reliable selector

                                    if (!durationCell) {
                                        console.error('Duration cell not found in row');
                                        return;
                                    }

                                    // Update the duration display
                                    const hiddenInput = durationCell.querySelector('input[type="hidden"]');
                                    if (hiddenInput) {
                                        hiddenInput.value = formattedDuration;
                                    } else {
                                        // Create hidden input if it doesn't exist
                                        const newInput = document.createElement('input');
                                        newInput.type = 'hidden';
                                        newInput.name = fileInput.name.replace('[file]', '[file_duration]');
                                        newInput.value = formattedDuration;
                                        durationCell.appendChild(newInput);
                                    }
                                    
                                    // Update the visible text
                                    const textNodes = [...durationCell.childNodes].filter(node => node.nodeType === Node.TEXT_NODE);
                                    if (textNodes.length > 0) {
                                        textNodes[0].nodeValue = formattedDuration;
                                    } else {
                                        durationCell.insertBefore(document.createTextNode(formattedDuration), durationCell.firstChild);
                                    }
                                };

                                video.src = URL.createObjectURL(file);
                            }
                        });

                        // Update Sr. No column after add/delete row
                        function updateSerialNumbers() {
                            [...tableBody.rows].forEach((row, i) => {
                                row.cells[0].textContent = i + 1;
                            });
                        }
                    });
                </script>




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
                    document.addEventListener('DOMContentLoaded', function () {
                        document.querySelectorAll('.upload-btn').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                const input = btn.nextElementSibling;
                                if (input && input.type === 'file') {
                                    input.click();
                                }
                            });
                        });
                    });
                </script> -->

            </div>
        </div>
    </div>



            @include('site.trainer.componants.footer')


           



          

