@include('site.componants.header')
<body class="bg-black">

    <div class="bg-white max-w-5xl mx-auto p-6 mt-10 rounded-lg shadow-lg">
        <h3 class="text-2xl font-semibold mb-6 text-center">Available Subscription Plans</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Silver Plan -->
            <div class="border rounded-lg p-6 shadow-sm text-center bg-white">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 bg-gray-300 rounded-full mb-2"></div>
                    <h4 class="font-semibold text-lg">Silver</h4>
                    <p class="font-bold text-xl mt-1">AED 49</p>
                </div>
                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                <ul class="text-sm text-gray-700 space-y-1 text-left pl-6 mb-4">
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                </ul>
                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                    Buy subscription
                </button>
            </div>

            <!-- Gold Plan -->
            <div class="border rounded-lg p-6 shadow-sm text-center bg-white">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 bg-gray-300 rounded-full mb-2"></div>
                    <h4 class="font-semibold text-lg">Gold</h4>
                    <p class="font-bold text-xl mt-1">AED 99</p>
                </div>
                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                <ul class="text-sm text-gray-700 space-y-1 text-left pl-6 mb-4">
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                </ul>
                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                    Buy subscription
                </button>
            </div>

            <!-- Platinum Plan -->
            <div class="border rounded-lg p-6 shadow-sm text-center bg-white">
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 bg-gray-300 rounded-full mb-2"></div>
                    <h4 class="font-semibold text-lg">Platinum</h4>
                    <p class="font-bold text-xl mt-1">AED 149</p>
                </div>
                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                <ul class="text-sm text-gray-700 space-y-1 text-left pl-6 mb-4">
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem ipsum</li>
                    <li class="flex items-center"><i class="fa fa-check text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                </ul>
                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                    Buy subscription
                </button>
            </div>
        </div>
    </div>

</body>

</html>