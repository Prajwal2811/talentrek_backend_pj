
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
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ langLabel('explore') }}</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="italic hover:text-blue-600">{{ langLabel('home') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('courses') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('mentorship') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('training') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('about_us') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('contact_us') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ langLabel('top_courses') }}</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('full_stack_development') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('data_science_ai') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('ui_ux_design') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('digital_marketing') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('project_management') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ langLabel('mentorship') }}</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('business_leaders') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('tech_experts') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('design_mentors') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('startup') }} {{ langLabel('mentorship') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ langLabel('support') }}</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('faqs') }}</a></li>
                    <li><a href="#" class="hover:text-blue-600">{{ langLabel('help_center') }}</a></li>
                    <li><a href="{{ route('terms-and-conditions') }}" class="hover:text-blue-600">{{ langLabel('terms_of_use') }}</a></li>
                    <li><a href="{{ route('privacy-policy') }}" class="hover:text-blue-600">{{ langLabel('privacy_policy') }}</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ langLabel('get_in_touch') }}</h3>
                <p class="text-xs text-gray-500 mb-2 italic">{{ langLabel('subscribe_newsletter') }}</p>
                <form class="flex items-center mb-3">
                    <input type="email" placeholder="{{ langLabel('enter_your_email') }}" class="w-full px-3 py-2 text-sm border rounded-l-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-r-md hover:bg-blue-700">{{ langLabel('submit') }}</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    var url = "{{ route('changeLang') }}";
    $(".changeLang").change(function(){

        window.location.href = url + "?lang="+ $(this).val();

    });
</script>
</body>
</html>

