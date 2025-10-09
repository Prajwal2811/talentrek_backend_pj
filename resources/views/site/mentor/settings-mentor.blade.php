<?php

$mentor = Auth()->user();
//  'trainerSkills',
// 'educationDetails',
// 'workExperiences'
// 'additonalDetails'
// echo "<pre>";
// print_r($additonalDetails );exit;
// echo "</pre>";
?>
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

	@if($mentorNeedsSubscription)
    @include('site.mentor.subscription.index')
@endif
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.mentor.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.mentor.componants.navbar')

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('settings') }}</h2>

                    <div class="flex"> 
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'"
                                    :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('profile') }}</a>
                                </li>
                                
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'notifications'"
                                    :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('notifications') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'payment'"
                                    :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('payment_history') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'subscription'; activeSubTab = 'subscription'"
                                    :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('subscription') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'privacy'"
                                    :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('privacy_policy') }}</a>
                                </li>
                                <li>
                                <!-- <a
                                    href="#"
                                    @click.prevent="activeSection = 'logout'"
                                    :class="activeSection === 'logout' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Log out</a> -->
                                </li>
                                  <li>
                                     <a
                                    href="#"
                                    @click.prevent="activeSection = 'delete'"
                                    :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'"
                                >{{ langLabel('delete_account') }}</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section -->
                                @include('site.mentor.setting.profile')
                                @include('site.mentor.setting.subscription')
                                @include('site.mentor.setting.delete')
                                @include('site.mentor.setting.notification')
                                @include('site.mentor.setting.payment')

                                


                                <!-- Privacy Policy Section -->
                              <div x-show="activeSection === 'privacy'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>
                                
                                <p class="mb-4">At XYZ Infotech, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information.</p>

                                <h4 class="text-lg font-semibold mb-2">1. Information We Collect</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Personal information such as name, email address, phone number, and address.</li>
                                    <li>Usage data including pages visited, time spent, and actions taken on our platform.</li>
                                    <li>Device and browser information for improving user experience.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">2. How We Use Your Information</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>To provide and maintain our services.</li>
                                    <li>To improve customer support and experience.</li>
                                    <li>To send transactional and promotional communications.</li>
                                    <li>To comply with legal obligations.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">3. Data Security</h4>
                                <p class="mb-4 text-sm text-gray-700">We implement a variety of security measures to maintain the safety of your personal information. However, no method of transmission over the Internet is 100% secure.</p>

                                <h4 class="text-lg font-semibold mb-2">4. Third-Party Services</h4>
                                <p class="mb-4 text-sm text-gray-700">We may use third-party services (e.g., analytics providers, payment gateways) that collect, monitor, and analyze data. These services have their own privacy policies.</p>

                                <h4 class="text-lg font-semibold mb-2">5. Your Rights</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Access and update your personal information.</li>
                                    <li>Request deletion of your data.</li>
                                    <li>Opt out of marketing communications.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">6. Changes to This Policy</h4>
                                <p class="mb-4 text-sm text-gray-700">We may update this Privacy Policy periodically. We will notify you of any significant changes via email or on our platform.</p>

                                <h4 class="text-lg font-semibold mb-2">7. Contact Us</h4>
                                <p class="text-sm text-gray-700">If you have any questions or concerns about this policy, please contact us at <a href="mailto:support@xyzinfotech.com" class="text-blue-600 underline">support@xyzinfotech.com</a>.</p>
                                </div>


                                <!-- Log Out Section -->
                                <div x-show="activeSection === 'logout'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Log Out</h3>
                                <p>Are you sure you want to log out?</p>
                                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log Out</button>
                                </div>

                                
                            </div>
                        </section>
                    </div>
                </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
         
            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>

    </div>
           


<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const workContainer = document.getElementById('work-container');
        const addWorkBtn = document.getElementById('add-work');

        // Add new work block
        addWorkBtn.addEventListener('click', function () {
            const firstEntry = workContainer.querySelector('.work-entry');
            const clone = firstEntry.cloneNode(true);

            // Clear input values
            clone.querySelectorAll('input').forEach(input => {
                if (input.type === 'hidden') {
                    input.remove();
                } else if (input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
                if (input.name === 'end_to[]') input.disabled = false;
            });

            // Show remove button
            const removeBtn = clone.querySelector('.remove-work');
            removeBtn.style.display = 'block';

            workContainer.appendChild(clone);
        });

        // Remove work block
        workContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-work')) {
                const allEntries = workContainer.querySelectorAll('.work-entry');
                if (allEntries.length > 1) {
                    e.target.closest('.work-entry').remove();
                }
            }
        });
    });

    // Toggle "currently working" checkbox
    function toggleEndDate(checkbox) {
        const input = checkbox.closest('div').querySelector('input[type="date"]');
        input.disabled = checkbox.checked;
        if (checkbox.checked) input.value = '';
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('deleteAccountBtn').addEventListener('click', function (e) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete your account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteAccountForm').submit();
        }
    });
});
</script>
          
@include('site.mentor.componants.footer')