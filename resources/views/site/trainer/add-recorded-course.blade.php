<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:17:45 GMT -->
<head>

	<!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />    
    <meta name="description" content="" />
    
    <!-- FAVICONS ICON -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    
    <!-- PAGE TITLE HERE -->
    <title>Talentrek</title>
    
    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"><!-- BOOTSTRAP STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"><!-- FONTAWESOME STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/feather.css"><!-- FEATHER ICON SHEET -->
    <link rel="stylesheet" type="text/css" href="css/owl.carousel.min.css"><!-- OWL CAROUSEL STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/magnific-popup.min.css"><!-- MAGNIFIC POPUP STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/lc_lightbox.css"><!-- Lc light box popup -->     
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css"><!-- BOOTSTRAP SLECT BOX STYLE SHEET  -->
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap5.min.css"><!-- DATA table STYLE SHEET  -->
    <link rel="stylesheet" type="text/css" href="css/select.bootstrap5.min.css"><!-- DASHBOARD select bootstrap  STYLE SHEET  -->     
    <link rel="stylesheet" type="text/css" href="css/dropzone.css"><!-- DROPZONE STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/scrollbar.css"><!-- CUSTOM SCROLL BAR STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/datepicker.css"><!-- DATEPICKER STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="css/flaticon.css"> <!-- Flaticon -->
    <link rel="stylesheet" type="text/css" href="css/swiper-bundle.min.css"><!-- Swiper Slider -->
    <link rel="stylesheet" type="text/css" href="css/style.css"><!-- MAIN STYLE SHEET -->

    <link rel="stylesheet" class="skin" type="text/css" href="css/skins-type/skin-6.css">
    <link rel="stylesheet" type="text/css" href="css/switcher.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


    <script src="https://cdn.tailwindcss.com"></script>

</head>

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
        <div class="flex ">
            <aside class="w-64 bg-blue-900 text-white flex flex-col py-8 px-4 ">
                <div class="text-2xl font-bold mb-10">
                    <span class="text-white">Talentre</span><span class="text-blue-400">k</span>
                </div>
                <nav class="flex flex-col gap-4">
                    <a href="dashboard.html" class="flex items-center px-4 py-2 bg-white text-blue-700 rounded-md">
                        <i data-feather="grid" class="mr-3"></i> Dashboard
                    </a>

                    <!-- My training with submenu -->
                    <div x-data="{ open: false }" class="flex flex-col">
                        <button 
                        @click="open = !open" 
                        class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200 focus:outline-none"
                        aria-expanded="false"
                        >
                        <i data-feather="book-open" class="mr-3"></i> My training
                        <svg 
                            :class="{'rotate-90': open}" 
                            class="ml-auto w-4 h-4 transition-transform duration-200" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        </button>

                        <div x-show="open" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                        <a href="training-list.html" class="px-4 py-2 text-white rounded-md hover:bg-blue-600 transition-colors duration-200">
                            Training list
                        </a>
                        <a href="add-training.html" class="px-4 py-2 text-white rounded-md hover:bg-blue-600 transition-colors duration-200">
                            Add Training
                        </a>
                        </div>
                    </div>

                    <a href="assessment.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="file-text" class="mr-3"></i> Assessment
                    </a>
                    <a href="batch.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="layers" class="mr-3"></i> Batch
                    </a>
                    <a href="trainees-jobseekers.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="users" class="mr-3"></i> Trainees / Jobseeker
                    </a>
                    <a href="chat-with-jobseeker.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="message-circle" class="mr-3"></i> Chat with jobseeker
                    </a>
                    <a href="reviews.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="star" class="mr-3"></i> Reviews
                    </a>
                    <a href="settings.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="settings" class="mr-3"></i> Settings
                    </a>
                    </nav>

                    <!-- Make sure to include Alpine.js for the toggle to work -->
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


                <script src="https://unpkg.com/feather-icons"></script>
                <script>
                feather.replace();
                </script>

                <style>
                    .no-hover:hover {
                    background-color: transparent !important;
                    color: inherit !important;
                    cursor: pointer; /* optional */
                    }
                </style>
            </aside>

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                    <div class="text-xl font-bold text-blue-900 block lg:hidden">
                        Talent<span class="text-blue-500">rek</span>
                    </div>
                    <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
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

                <main class="p-6 ">
                        <h2 class="text-xl font-semibold mb-6">Recorded course</h2>

                        <!-- Course Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Title</label>
                            <input type="text" placeholder="Enter the Course Title" class="w-full border rounded-md p-2" />
                        </div>

                        <!-- Course Sub Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Sub Title</label>
                            <input type="text" placeholder="Enter the Sub Title" class="w-full border rounded-md p-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Description</label>
                            <textarea placeholder="Enter the Description" class="w-full border rounded-md p-2 h-24"></textarea>
                        </div>

                        <!-- Category -->
                       <div class="mb-4">
                            <label class="block font-medium mb-2">Category</label>
                            <div class="flex flex-wrap gap-4">
                                <label><input type="checkbox" class="mr-2 category-checkbox" /> Business</label>
                                <label><input type="checkbox" class="mr-2 category-checkbox" /> Development</label>
                                <label><input type="checkbox" class="mr-2 category-checkbox" /> Design</label>
                                <label><input type="checkbox" class="mr-2 category-checkbox" /> Marketing</label>
                                <label><input type="checkbox" class="mr-2 category-checkbox" /> Health & Fitness</label>
                                <label>
                                    <input type="checkbox" id="other-checkbox" class="mr-2" /> Others...
                                </label>
                            </div>
                            <input
                                type="text"
                                id="other-text"
                                placeholder="Please specify"
                                class="mt-2 border rounded-md p-2 w-full"
                                style="display: none;"
                            />
                        </div>

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


                        <!-- Course Content Structure -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Course Content Structure</label>
                            <div class="mb-2">
                                <input id="sectionTitle" type="text" placeholder="Section title" class="w-full border rounded-md p-2" />
                            </div>
                            <div class="mb-2">
                                <textarea id="contentText" placeholder="Contents" class="w-full border rounded-md p-2"></textarea>
                            </div>
                            <button id="addContentBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Content</button>
                        </div>

                        <!-- Course Video Table -->
                        <div class="bg-white p-4 rounded-md shadow mb-4">
                            <h3 class="text-md font-semibold mb-2">Course Video</h3>
                            <div class="overflow-x-auto">
                                <table id="courseTable" class="w-full text-sm text-left border">
                                    <thead class="bg-gray-100">
                                        <tr class="text-gray-700">
                                            <th class="p-2 border">Sr. No.</th>
                                            <th class="p-2 border">Title</th>
                                            <th class="p-2 border">Description</th>
                                            <th class="p-2 border">Upload</th>
                                            <th class="p-2 border">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- No initial rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <script>
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
                        </script>



                        <!-- Upload Thumbnail -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Upload Thumbnail</label>
                            <div class="flex gap-4 items-center">
                            <input type="file" class="border rounded-md p-2 flex-1" />
                            <button class="bg-green-600 hover:bg-green-500 text-white rounded-md px-4 py-2">Upload</button>
                            </div>
                        </div>

                        <!-- Course Price -->
                        <div class="mb-6">
                            <label class="block font-medium mb-1">Course price</label>
                            <input type="text" placeholder="Enter Course Price" class="w-full border rounded-md p-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="text-right">
                            <button class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                            Submit
                            </button>
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
