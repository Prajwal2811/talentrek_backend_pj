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
             @include('site.mentor.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.mentor.componants.navbar')

                  <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                    <h2 class="text-2xl font-semibold mb-6">Admin support</h2>

                    <!-- Chat Card -->
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col h-[600px]">

                      <!-- Header -->
                      <div class="flex items-center space-x-4 border-b pb-4 mb-4">
                        <img src="https://i.pravatar.cc/100?img=3" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                        <div>
                          <h3 class="text-md font-semibold">Talentrek admin support</h3>
                          <p class="text-gray-500 text-sm">Ask anything about recruitment</p>
                        </div>
                      </div>

                      <!-- Chat Messages -->
                      <div id="chatMessages" class="flex-1 space-y-4 overflow-y-auto pr-2">
                        <!-- JS will insert messages here -->
                      </div>

                      <!-- Chat Input -->
                      <div class="pt-4 mt-4 border-t flex items-center space-x-2">
                        <input
                            type="text"
                            placeholder="Write your message here..."
                            class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
                        />

                        <!-- Paperclip as file input -->
                        <label for="fileInput" class="p-2 text-gray-500 hover:text-gray-700 rounded-full cursor-pointer">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" id="fileInput" class="hidden" />
                        </label>

                        <!-- Send button -->
                        <button class="p-2 text-white bg-blue-600 hover:bg-blue-700 rounded-full">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        </div>
                    </div>
                  </main>


                  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                  <script src="https://cdn.tailwindcss.com"></script>







            </div>
        </div>

      


    </div>
           



          
@include('site.mentor.componants.footer')
