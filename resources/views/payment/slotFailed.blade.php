<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-dark-blue': '#0B2447',
              'brand-mid-blue': '#19376D',
              'brand-light-blue': '#576CBC',
              'brand-orange': '#F48423',
            },
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
          }
        }
      }
    </script>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        @keyframes blob {
          0% { transform: translate(0px, 0px) scale(1); }
          33% { transform: translate(30px, -50px) scale(1.1); }
          66% { transform: translate(-20px, 20px) scale(0.9); }
          100% { transform: translate(0px, 0px) scale(1); }
        }
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .animate-blob {
          animation: blob 7s infinite;
        }
        .animation-delay-2000 {
          animation-delay: 2s;
        }
        .animation-delay-4000 {
          animation-delay: 4s;
        }
    </style>
<script type="importmap">
{
  "imports": {
    "react": "https://aistudiocdn.com/react@^19.1.1",
    "react-dom/": "https://aistudiocdn.com/react-dom@^19.1.1/",
    "react/": "https://aistudiocdn.com/react@^19.1.1/"
  }
}
</script>
</head>
<body class="bg-brand-dark-blue">
    <main class="relative min-h-screen w-full bg-brand-dark-blue flex items-center justify-center p-4 sm:p-6 overflow-hidden font-sans">
      <!-- Decorative background circles inspired by the provided image -->
      <div class="absolute -top-24 -left-32 w-72 h-72 md:w-96 md:h-96 bg-brand-mid-blue rounded-full opacity-40 mix-blend-screen filter blur-xl animate-blob"></div>
      <div class="absolute bottom-0 -right-40 w-96 h-96 bg-brand-light-blue rounded-full opacity-20 mix-blend-screen filter blur-2xl animate-blob animation-delay-2000"></div>
      <div class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 md:w-[500px] md:h-[500px] bg-brand-mid-blue rounded-full opacity-20 mix-blend-screen filter blur-3xl animate-blob animation-delay-4000"></div>

      <!-- Main content card with Glassmorphism effect -->
      <div class="relative z-10 text-center text-white max-w-md w-full bg-black bg-opacity-20 backdrop-blur-lg rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl">
        <div class="flex justify-center mb-6">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-20 h-20 sm:w-24 sm:h-24 text-red-400"
            aria-hidden="true"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        
        <!-- Main message -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5)">
          Payment Failed!
        </h1>
        <p class="text-base sm:text-lg text-gray-300 mb-8">
          {{ $description }}
        </p>

        <!-- Transaction Details -->
        <div class="text-left bg-white/5 rounded-lg p-4 mb-8 space-y-3 text-sm sm:text-base">
          <div class="flex justify-between items-center">
            <span class="text-gray-400">Amount Paid:</span>
            <span class="font-semibold">{{ $amount }} SAR</span>
          </div>
          <!-- <div class="flex justify-between items-center">
            <span class="text-gray-400">Transaction ID:</span>
            <span class="font-semibold tracking-tighter">#{{ $transaction_id }}</span>
          </div>
           <div class="flex justify-between items-center">
            <span class="text-gray-400">Payment Date:</span>
            <span id="transaction-date" class="font-semibold">{{ $date }}</span>
          </div> -->
        </div>
        
        <!-- Call to action button -->
         <a 
            href="/backToDashboard"
            class="w-full block text-center bg-brand-orange text-white font-bold py-3 px-6 rounded-lg text-lg transition-all duration-300 ease-in-out hover:bg-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-300/50 transform hover:-translate-y-1 shadow-lg hover:shadow-orange-500/40"
          >
            Return to Dashboard
          </a>

        <!-- <button
          class="w-full bg-brand-orange text-white font-bold py-3 px-6 rounded-lg text-lg transition-all duration-300 ease-in-out hover:bg-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-300/50 transform hover:-translate-y-1 shadow-lg hover:shadow-orange-500/40"
        >
          Return to Dashboard
        </button> -->
      </div>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('transaction-datess');
        if (dateElement) {
          dateElement.textContent = new Date().toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
          });
        }
      });
    </script>
</body>
</html>
