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

	
    <div class="page-wraper">
        <div class="flex h-screen">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                    <div class="text-xl font-bold text-blue-900 block lg:hidden">
                        Talent<span class="text-blue-500">rek</span>
                    </div>
                    <!-- <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                        <button aria-label="Notifications" class="text-gray-700 hover:text-blue-600 focus:outline-none relative">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-bell text-xl"></i>
                            </span>
                            <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
                        </button>
                        </div>
                        <div class="relative inline-block">
                        <select aria-label="Select Language" 
                                class="appearance-none border border-gray-300 rounded-md px-10 py-1 text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="en" selected>English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <!-- add more languages as needed -->
                        </select>
                        <span class="pointer-events-none absolute left-2 top-1/2 transform -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-globe"></i>
                        </span>
                        </div>
                    <div>
                        <a href="#" role="button"
                            class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span> Profile</span>
                        </a>
                    </div>
                    </div>
                </nav>

            <main class="p-6 " x-data="chatApp()">
                <h2 class="text-2xl font-semibold mb-6">Message</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[70vh]">
                    <!-- Contacts Sidebar -->
                    <div class="bg-white rounded-lg shadow overflow-y-auto col-span-1">
                    <template x-for="(contact, index) in contacts" :key="index">
                        <div 
                        class="border-b p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer"
                        :class="activeContact?.name === contact.name ? 'bg-gray-100' : ''"
                        @click="selectContact(contact)"
                        >
                        <img :src="contact.avatar" class="w-10 h-10 rounded-full object-cover" alt="User">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm" x-text="contact.name"></h4>
                            <p class="text-xs text-gray-500 truncate" x-text="contact.lastMessage"></p>
                        </div>
                        <div class="text-xs text-gray-400" x-text="contact.time"></div>
                        </div>
                    </template>
                    </div>

                    <!-- Right Panel (Chat or Framework Message) -->
                    <div class="bg-white rounded-lg shadow col-span-2 flex flex-col h-full">
                    <!-- IF contact is selected, show chat window -->
                    <template x-if="activeContact">
                        <div class="flex flex-col h-full">
                        <!-- Header -->
                        <div class="border-b p-4 flex items-center gap-3">
                            <img :src="activeContact.avatar" class="w-10 h-10 rounded-full object-cover" alt="User">
                            <div>
                            <h4 class="font-semibold text-sm" x-text="activeContact.name"></h4>
                            <p class="text-xs text-gray-500" x-text="activeContact.status"></p>
                            </div>
                        </div>

                        <!-- Messages -->
                    <!-- Messages -->
<div class="flex-1 p-4 space-y-3 overflow-y-auto h-[calc(100vh-280px)]" x-ref="chatContainer">

                            <template x-for="(message, i) in activeContact.messages" :key="i">
                                <div :class="message.sender === 'me' ? 'flex justify-end' : ''">
                                <div class="max-w-sm bg-gray-100 text-sm p-2 rounded-md" x-text="message.text"></div>
                                </div>
                            </template>
                        </div>
                        <!-- Message Input -->
                        <div class="p-4 border-t flex items-center gap-3">
    <!-- Message Input -->
    <input 
        type="text" 
        placeholder="Write your message here ....." 
        class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none"
        x-model="newMessage"
        @keyup.enter="sendMessage"
    />

    <!-- File Input Button -->
    <label class="cursor-pointer bg-gray-200 p-2 rounded-full hover:bg-gray-300">
        <i class="fas fa-paperclip"></i>
        <input 
            type="file" 
            class="hidden"
            @change="handleFileUpload"
        />
    </label>

    <!-- Send Button -->
    <button class="bg-blue-600 text-white p-2 rounded-full" @click="sendMessage">
        <i class="fas fa-paper-plane"></i>
    </button>
</div>

                        </div>
                    </template>

                    <!-- ELSE show framework message -->
                    <!-- Default Chat Framework -->
                    <template x-if="!activeContact">
                        <div class="flex flex-col items-center justify-center flex-1 text-center text-gray-500 px-6">
                            <img src="https://cdn-icons-png.flaticon.com/512/2462/2462719.png" alt="Chat Illustration" class="w-24 h-24 mb-6 opacity-50" />
                            <h3 class="text-lg font-semibold mb-2">Welcome to the Chat Panel</h3>
                            <p class="text-sm text-gray-400">
                            Select a contact from the left panel to view and send messages.
                            </p>

                            <div class="mt-8 text-left w-full max-w-md">
                            <h4 class="text-sm font-semibold mb-3 text-gray-600">Chat Features:</h4>
                            <ul class="text-sm text-gray-500 list-disc pl-5 space-y-2">
                                <li>Real-time messaging</li>
                                <li>Smart contact selection</li>
                                <li>Message history preview</li>
                                <li>Responsive & clean layout</li>
                            </ul>
                            </div>
                        </div>
                    </template>

                    </div>
                </div>
            </main>
    <!-- Alpine Script -->
    <script>
        function chatApp() {
            return {
                contacts: [
                    {
                        name: 'Julia Maccarthy',
                        avatar: 'https://i.pravatar.cc/40?img=1',
                        status: 'Online',
                        lastMessage: 'Very easy to understand',
                        time: '07:00 AM',
                        messages: [
                            { sender: 'me', text: 'Hello, How was your lecture' },
                            { sender: 'them', text: 'Very easy to understand' },
                            { sender: 'me', text: 'Great! Was it on React or JavaScript?' },
                            { sender: 'them', text: 'It was on JavaScript ES6. We covered promises and async/await.' },
                            { sender: 'me', text: 'That’s important. Did you understand everything?' },
                            { sender: 'them', text: 'Yes, the examples were clear. The tutor explained step by step.' },
                            { sender: 'me', text: 'Awesome! Can you share your notes later?' },
                            { sender: 'them', text: 'Sure. I’ll send them after lunch.' }
                        ]
                    },
                    {
                        name: 'David Parker',
                        avatar: 'https://i.pravatar.cc/40?img=2',
                        status: 'Offline',
                        lastMessage: 'See you later',
                        time: '06:30 AM',
                        messages: [
                            { sender: 'me', text: 'Are you coming today?' },
                            { sender: 'them', text: 'No, I am busy. See you later.' },
                            { sender: 'me', text: 'Busy with work or something else?' },
                            { sender: 'them', text: 'I have a client meeting in the morning and a project deadline in the evening.' },
                            { sender: 'me', text: 'Got it. Let’s catch up tomorrow then?' },
                            { sender: 'them', text: 'Sounds good. Let me know what time works for you.' },
                            { sender: 'me', text: 'Anytime after 10 AM works.' },
                            { sender: 'them', text: 'Perfect. See you then!' }
                        ]
                    },
                    {
                        name: 'Anna Lee',
                        avatar: 'https://i.pravatar.cc/40?img=3',
                        status: 'Online',
                        lastMessage: 'Thanks!',
                        time: '06:00 AM',
                        messages: [
                            { sender: 'them', text: 'Can you send the file?' },
                            { sender: 'me', text: 'Sent. Check now.' },
                            { sender: 'them', text: 'Thanks!' },
                            { sender: 'me', text: 'No problem. Let me know if you face any issues.' },
                            { sender: 'them', text: 'Sure. Are these the final slides for tomorrow’s demo?' },
                            { sender: 'me', text: 'Yes, version 3.2. Includes updated charts and use cases.' },
                            { sender: 'them', text: 'Looks good. I’ll go through them tonight.' },
                            { sender: 'me', text: 'Perfect. Let me know if you need help with anything.' }
                        ]
                    }
                ],
                activeContact: null,
                newMessage: '',
                selectedFile: null,

                selectContact(contact) {
                    this.activeContact = contact;
                    this.newMessage = '';
                    this.selectedFile = null;
                },

                sendMessage() {
                    if (!this.activeContact) return;

                    // Send message
                    if (this.newMessage.trim() !== '') {
                        this.activeContact.messages.push({ sender: 'me', text: this.newMessage });
                        this.newMessage = '';
                    }

                    // Send file (if selected)
                    if (this.selectedFile) {
                        this.activeContact.messages.push({
                            sender: 'me',
                            text: `[File Attached: ${this.selectedFile.name}]`
                        });
                        this.selectedFile = null;
                    }

                    // Auto-scroll
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTo({
                                top: container.scrollHeight,
                                behavior: 'smooth'
                            });
                        }
                    });
                },

                handleFileUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.selectedFile = file;
                    }
                }
            };
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          

            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



          @include('site.trainer.componants.footer')