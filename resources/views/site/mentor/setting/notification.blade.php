   <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                <h3 class="text-xl font-semibold mb-4 border-b pb-2">{{ langLabel('notifications') }}</h3>

                                <!-- Scrollable notification list -->
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <!-- Notification Items -->
                                    @php $notifications = notificationUsersSent('mentor'); @endphp
                                    @foreach($notifications as $notification)
                                    <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <a href="{{ route('recruiter.notifications_details',['id' => $notification->id,'user_type' => 'mentor']) }}">
                                        <div>
                                            <p><span class="font-semibold">{{ $notification->message }}</span></p>
                                            <p class="text-sm text-gray-500 mt-1">{{ date('d-m-y H:s A',strtotime($notification->created_at)) }}</p>
                                        </div>
                                        </a>
                                    </div>
                                    @endforeach
                                    @if($notifications->count() < 1) 
                                        <a href=""
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ langLabel('view_records_found') }}</a>
                                    @endif

                                    
                                </div>
                                </div>