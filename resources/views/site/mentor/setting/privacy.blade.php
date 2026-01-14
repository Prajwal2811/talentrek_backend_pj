<div x-show="activeSection === 'privacy'" x-transition>
    <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>
    @php
        $data = App\Models\CMS::where('slug', 'privacy-policy')->first();
        echo $data ? strip_tags($data->description) : '';
    @endphp
</div>