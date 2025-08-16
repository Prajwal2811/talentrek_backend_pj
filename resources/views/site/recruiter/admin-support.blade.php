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

	@if($recruiterNeedsSubscription)
        @include('site.recruiter.subscription.index')
    @endif
     @if($otherRecruiterSubscription)
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">

           <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	

            <div class="flex-1 flex flex-col">
                 @include('site.recruiter.componants.navbar')

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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const messages = [
            {
                sender: "admin",
                text: "Hi there! Welcome to Talentrek 👋 How can I assist you today?",
                time: "10:30 AM"
            },
            {
                sender: "user",
                text: "Hey! I’m trying to understand how the recruitment process works here.",
                time: "10:31 AM"
            },
            {
                sender: "admin",
                text: "Absolutely! First, complete your profile, then apply for jobs. Recruiters will review and may contact you.",
                time: "10:32 AM"
            },
            {
                sender: "user",
                text: "Got it. Do I get notified when I’m shortlisted?",
                time: "10:33 AM"
            },
            {
                sender: "admin",
                text: "Yes, you'll receive an email and in-app alert if you're shortlisted.",
                time: "10:34 AM"
            },
            {
                sender: "user",
                text: "Thanks! That was helpful. 😄",
                time: "10:35 AM"
            },
            {
                sender: "user",
                text: "Where can I upload my certificates?",
                time: "10:36 AM"
            },
            {
                sender: "admin",
                text: "You can upload them under the Profile section → Certifications tab.",
                time: "10:37 AM"
            },
            {
                sender: "user",
                text: "Okay. Is there a limit to the number of documents I can upload?",
                time: "10:38 AM"
            },
            {
                sender: "admin",
                text: "No hard limit, but we recommend keeping it under 10 for faster review.",
                time: "10:39 AM"
            },
            {
                sender: "user",
                text: "Alright. Also, how do I schedule an interview?",
                time: "10:40 AM"
            },
            {
                sender: "admin",
                text: "Once shortlisted, recruiters will provide available slots. You can book via the dashboard.",
                time: "10:41 AM"
            },
            {
                sender: "user",
                text: "Perfect. Thanks for the support!",
                time: "10:42 AM"
            },
            {
                sender: "admin",
                text: "Always happy to help 😊 Let us know if you need anything else.",
                time: "10:43 AM"
            }
            ];


      const chatBox = document.getElementById("chatMessages");
      let index = 0;
      const delay = 1500;

      function appendMessage(msg) {
        const div = document.createElement("div");
        div.className = msg.sender === "admin" ? "flex items-start" : "flex justify-end";

        div.innerHTML = `
          <div class="${msg.sender === "admin" ? 'bg-gray-200 rounded-bl-none' : 'bg-blue-100 rounded-br-none'} 
            text-sm px-4 py-2 rounded-lg max-w-xs shadow">
            ${msg.text}
            <p class="text-xs text-gray-500 ${msg.sender === 'admin' ? '' : 'text-right'} mt-1">
              ${msg.sender === "admin" ? "Admin" : "You"} • ${msg.time}
            </p>
          </div>
        `;

        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
      }

      function showNextMessage() {
        if (index < messages.length) {
          appendMessage(messages[index]);
          index++;
          setTimeout(showNextMessage, delay);
        }
      }

      showNextMessage();
    });
  </script>




                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                    <script src="https://cdn.tailwindcss.com"></script>







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
</html>
