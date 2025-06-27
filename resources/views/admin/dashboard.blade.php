@include('admin.componants.header')

<body data-theme="light">

    <div id="body" class="theme-cyan">
        <!-- Theme setting div -->
        <div class="themesetting">
        </div>
        <!-- Overlay For Sidebars -->
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid">
                    <div class="block-header text-center py-16">
                        <h1 class="text-4xl font-bold mb-4">🚧 Coming Soon</h1>
                        <p class="text-lg mb-6">We're working hard to launch something amazing. Stay tuned!</p>
                        <div id="countdown" class="text-2xl font-semibold space-x-4">
                            <span><span id="days">00</span> Days</span>
                            <span><span id="hours">00</span> Hours</span>
                            <span><span id="minutes">00</span> Minutes</span>
                            <span><span id="seconds">00</span> Seconds</span>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Set the target launch date (YYYY-MM-DD HH:MM:SS)
                const launchDate = new Date("2025-07-01T00:00:00").getTime();
                const countdown = () => {
                    const now = new Date().getTime();
                    const distance = launchDate - now;

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("days").innerHTML = String(days).padStart(2, '0');
                    document.getElementById("hours").innerHTML = String(hours).padStart(2, '0');
                    document.getElementById("minutes").innerHTML = String(minutes).padStart(2, '0');
                    document.getElementById("seconds").innerHTML = String(seconds).padStart(2, '0');

                    if (distance < 0) {
                        clearInterval(timer);
                        document.getElementById("countdown").innerHTML = "🎉 We're live now!";
                    }
                };
                const timer = setInterval(countdown, 1000);
            </script>
        </div>
    </div>

    @include('admin.componants.footer')