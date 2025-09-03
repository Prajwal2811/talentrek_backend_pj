
<footer class="bg-white border-t pt-10 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <a href="#" class="inline-block">
                    <img src="{{ asset('asset/images/logo.png') }}" alt="JustDo Logo" class="h-8">
                </a>
            </div>
            @php
                $medias = App\Models\SocialMedia::get();
            @endphp

            <div class="flex space-x-4">
                @foreach ($medias as $media)
                    <a href="{{ $media->media_link }}" target="_blank" aria-label="{{ $media->media_name }}">
                        <i class="{{ $media->icon_class }} text-xl hover:text-blue-600"></i>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Explore</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="italic hover:text-blue-600">Home</a></li>
                    <li><a href="#" class="hover:text-blue-600">Courses</a></li>
                    <li><a href="#" class="hover:text-blue-600">Mentorship</a></li>
                    <li><a href="#" class="hover:text-blue-600">Training</a></li>
                    <li><a href="#" class="hover:text-blue-600">About Us</a></li>
                    <li><a href="#" class="hover:text-blue-600">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Top Courses</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">Full Stack Development</a></li>
                    <li><a href="#" class="hover:text-blue-600">Data Science & AI</a></li>
                    <li><a href="#" class="hover:text-blue-600">UI/UX Design</a></li>
                    <li><a href="#" class="hover:text-blue-600">Digital Marketing</a></li>
                    <li><a href="#" class="hover:text-blue-600">Project Management</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Mentorship</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">Business Leaders</a></li>
                    <li><a href="#" class="hover:text-blue-600">Tech Experts</a></li>
                    <li><a href="#" class="hover:text-blue-600">Design Mentors</a></li>
                    <li><a href="#" class="hover:text-blue-600">Startup Mentorship</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Support</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">FAQs</a></li>
                    <li><a href="#" class="hover:text-blue-600">Help Center</a></li>
                    <li><a href="https://talentrek.reviewdevelopment.net/uploads/Talentrek_Terms_and_Conditions.docx" class="hover:text-blue-600">Terms of Use</a></li>
                    <li><a href="https://talentrek.reviewdevelopment.net/uploads/Talentrek_privacy-policy (3).docx" class="hover:text-blue-600">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Get in Touch</h3>
                <p class="text-xs text-gray-500 mb-2 italic">Subscribe to our newsletter</p>
                <form class="flex items-center mb-3">
                    <input type="email" placeholder="Enter your email" class="w-full px-3 py-2 text-sm border rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-r-md hover:bg-blue-700">Submit</button>
                </form>
                <p class="text-sm text-gray-600">
                    📞 <a href="tel:+966558819908" class="hover:text-blue-600">+966 55 881 9908</a><br>
                    📧 <a href="mailto:info@gmqconsulting.com" class="hover:text-blue-600">info@gmqconsulting.com</a>
                </p>
            </div>
        </div>
    </div>
</footer>
</div>

<script src="{{ asset('asset/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('asset/js/popper.min.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('asset/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('asset/js/waypoints.min.js') }}"></script>
<script src="{{ asset('asset/js/counterup.min.js') }}"></script>
<script src="{{ asset('asset/js/waypoints-sticky.min.js') }}"></script>
<script src="{{ asset('asset/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('asset/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('asset/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('asset/js/theia-sticky-sidebar.js') }}"></script>
<script src="{{ asset('asset/js/lc_lightbox.lite.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('asset/js/dropzone.js') }}"></script>
<script src="{{ asset('asset/js/jquery.scrollbar.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('asset/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('asset/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('asset/js/chart.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('asset/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('asset/js/custom.js') }}"></script>
<script src="{{ asset('asset/js/switcher.js') }}"></script>

</body>
</html>

