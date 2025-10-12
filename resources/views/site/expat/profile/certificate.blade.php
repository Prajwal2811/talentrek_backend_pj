<div x-data="{ showModal: false, selectedCertificate: null }">
                        <div x-show="tab === 'certificates'" x-cloak>
                            <div class="p-6">
                                <h2 class="text-xl font-semibold mb-4">Certificates</h2>

                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <!-- Header -->
                                    <div class="bg-gray-100 px-6 py-3 font-semibold text-sm text-gray-700 flex">
                                    <div class="w-1/12">Sr. No.</div>
                                    <div class="w-4/12">Certificate of</div>
                                    <div class="w-3/12">Date of certification</div>
                                    <div class="w-2/12">View</div>
                                    <div class="w-2/12">Delete</div>
                                    </div>

                                    <!-- Row 1 -->
                                    <div class="flex items-center px-6 py-4 text-sm text-gray-700 border-b">
                                    <div class="w-1/12">1.</div>
                                    <div class="w-4/12">Full Stack Deveopler Course</div>
                                    <div class="w-3/12">12/04/2025</div>
                                    <div class="w-2/12">
                                        <button 
                                        @click="showModal = true; selectedCertificate = 'https://udemy-certificate.s3.amazonaws.com/image/UC-c2013095-ec1b-4b2b-b77e-07a330160cb8.jpg?v=1719901769000'" 
                                        class="bg-[#2196F3] hover:bg-blue-600 text-white px-4 py-1 rounded text-xs">
                                        View doc
                                        </button>
                                    </div>
                                    <div class="w-2/12">
                                        <button class="bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-2">
                                        <!-- Trash Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 3v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5V3H9zm2 4h2v10h-2V7zm-4 0h2v10H7V7zm8 0h2v10h-2V7z"/>
                                        </svg>
                                        </button>
                                    </div>
                                </div>

                            <!-- Upload Button -->
                        </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <!-- <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white w-full max-w-3xl rounded shadow-lg overflow-hidden">
                            <div class="flex justify-between items-center px-4 py-2 border-b">
                                <h3 class="text-lg font-semibold">Certificate Document</h3>
                                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                            </div>
                            <div class="p-4 flex flex-col items-center">
                                <template x-if="selectedCertificate">
                                    <img :src="selectedCertificate" alt="Certificate" class="max-h-[500px] w-auto object-contain" />
                                </template>
                                
                                <template x-if="selectedCertificate">
                                    <a 
                                        :href="selectedCertificate" 
                                        download="certificate.jpg" 
                                        class="mt-4 p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center justify-center"
                                        aria-label="Download Certificate"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                        </svg>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div> -->
                </div>  