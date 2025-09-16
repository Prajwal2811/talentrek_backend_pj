@php
    $email = session('email');
    $phone = session('phone_number');
@endphp

@include('site.componants.header')

<body>
    <!-- LOADING AREA START ===== -->
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

    @include('site.componants.navbar')

    <div class="page-content">
        <div class="section-full site-bg-white">
            <div class="container-fluid mt-3">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <!-- <div class="container d-flex justify-content-center align-items-center min-vh-100"> -->
                        <div class="max-w-4xl mx-auto p-8 mt-5">

                            <!-- Stepper -->
                            <div class="flex items-center justify-between mb-10">
                                <!-- Step Buttons -->
                                <template id="stepper-template">
                                    <div class="flex flex-col items-center cursor-pointer" onclick="showStep(STEP_NUM)">
                                        <div id="step-STEP_NUM-circle"
                                            class="w-8 h-8 rounded-full border-2 flex items-center justify-center">
                                            STEP_NUM</div>
                                        <span class="text-xs mt-1 text-center">STEP_LABEL</span>
                                    </div>
                                </template>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(1)">
                                    <div id="step-1-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 bg-blue-600 text-white flex items-center justify-center">
                                        1</div>
                                    <span class="text-xs mt-1 text-center">Personal<br />information</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(2)">
                                    <div id="step-2-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        2</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('educational') }}<br />details</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(3)">
                                    <div id="step-3-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        3</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('work') }}<br />experience</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(4)">
                                    <div id="step-4-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        4</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('skills') }} &<br />{{ langLabel('training') }}</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(5)">
                                    <div id="step-5-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        5</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('additional') }}<br />information</span>
                                </div>
                            </div>

                            <!-- Steps Content -->
                            <form class="space-y-6" id="multiStepForm" action="{{ route('jobseeker.registration.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Step 1: Personal Info -->
                                <div id="step-1" class="step">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">{{ langLabel('full_name') }} <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="name" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="{{ langLabel('enter_full_name') }}" value="{{ old('name') }}" />
                                        @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('email') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input placeholder="{{ langLabel('enter_email') }}" name="email" type="email"
                                                class="w-full border rounded-md p-2 mt-1" value="{{ old('email', $email) }}"
                                                readonly />
                                            @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('gender') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <select name="gender" id="gender" class="w-full border rounded-md p-2 mt-1">
                                                <option value="">{{ langLabel('select_gender') }}</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ langLabel('male') }}
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    {{ langLabel('female') }}</option>
                                            </select>
                                        @error('gender')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('phone_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <div class="flex">
                                                <select name="phone_code" class="w-1/3 border rounded-l-md p-2 mt-1">
                                                    <option value="+966">+966</option>
                                                    <option value="+971">+971</option>
                                                    <!-- Add more country codes if needed -->
                                                </select>
                                                <input name="phone_number" placeholder="Enter Phone number" type="tel"
                                                    class="w-2/3 border rounded-r-md p-2 mt-1"
                                                    value="{{ old('phone_number') }}"  />
                                            </div>
                                            @error('phone_number')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('dob') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="date" name="dob" id="dob" class="w-full border rounded-md p-2 mt-1"
                                                value="{{ old('dob') }}" max="{{ date('Y-m-d') }}"/>
                                            @error('dob')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                        <div class="col-span-2">
                                            <label class="block mb-1 text-sm font-medium">{{ langLabel('national_id_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <span class="text-xs text-blue-600">
                                                National ID should start with 1 for male and 2 for female.
                                            </span>
                                            <input 
                                                type="text" 
                                                name="national_id" 
                                                id="national_id" 
                                                class="w-full border rounded-md p-2 mt-1" 
                                                placeholder="Enter national id number" 
                                                value="{{ old('national_id') }}" 
                                                maxlength="15"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                            />
                                            @error('national_id')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">
                                            {{ langLabel('address') }} <span style="color: red; font-size: 17px;">*</span>
                                        </label>
                                        <textarea name="address" rows="3"
                                            class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Street, Area">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <!-- Country Dropdown -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('country') }} <span style="color: red;">*</span></label>
                                        <select id="country" name="country" class="w-full border rounded-md p-2" onchange="loadStates()">
                                            <option value="">{{ langLabel('select_country') }}</option>
                                            <option value="saudi">Saudi Arabia</option>
                                            <option value="india">India</option>
                                            <option value="usa">USA</option>
                                            <option value="canada">Canada</option>
                                            <option value="australia">Australia</option>
                                            <option value="uk">UK</option>
                                            <option value="germany">Germany</option>
                                            <option value="france">France</option>
                                            <option value="uae">UAE</option>
                                            <option value="japan">Japan</option>
                                        </select>
                                    </div>

                                    <!-- State Dropdown -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('state') }} <span style="color: red;">*</span></label>
                                        <select id="state" name="state" class="w-full border rounded-md p-2" onchange="loadCities()">
                                            <option value="">{{ langLabel('select_state') }}</option>
                                        </select>
                                    </div>

                                    <!-- City Dropdown -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('city') }} <span style="color: red;">*</span></label>
                                        <select id="city" name="city" class="w-full border rounded-md p-2">
                                            <option value="">{{ langLabel('select_city') }}</option>
                                        </select>
                                    </div>

                                    <script>
                                    const data = {
                                        saudi: {
                                            "Riyadh Province": [
                                                "Riyadh", "Al Kharj", "Wadi ad-Dawasir", "Shaqra", "Az Zulfi", "Al Majma'ah", "Al Muzahimiyah",
                                                "Durma", "Afif", "Al Ghat", "Hotat Bani Tamim", "Al Hariq", "As Sulayyil", "Thadiq", "Huraymila"
                                            ],
                                            "Makkah Province": [
                                                "Jeddah", "Mecca", "Taif", "Rabigh", "Al Lith", "Khulais", "Al Jumum", "Turubah", "Ranyah", "Al Khurma",
                                                "Maysan", "Al Bahrah", "Adham", "Masturah"
                                            ],
                                            "Medina Province": [
                                                "Medina", "Yanbu", "Badr", "Al-Ula", "Khaybar", "Al Mahd", "Al Hinakiyah", "Wadi Al-Fara", "Al Ais", "Al Ameq"
                                            ],
                                            "Eastern Province (Ash Sharqiyah)": [
                                                "Dammam", "Khobar", "Dhahran", "Qatif", "Hofuf", "Jubail", "Ras Tanura", "Abqaiq", "Al Hasa", "Al Khafji",
                                                "Safwa", "Tarout", "Al Nairyah", "Al Qaisumah", "Hafar Al-Batin", "Anak", "Al-Oyaynah", "Udhailiyah"
                                            ],
                                            "Asir Province": [
                                                "Abha", "Khamis Mushait", "Bisha", "Ahad Rafidah", "Tanumah", "Sarat Abidah", "Muhayil", "Dharan Al Janub",
                                                "Tathleeth", "Rijal Alma", "Al Namas", "Balqarn", "Al Majaridah", "Bariq"
                                            ],
                                            "Tabuk Province": [
                                                "Tabuk", "Duba", "Haql", "Tayma", "Al Wajh", "Umluj", "Al Muwaileh", "Sharma", "Al Bada"
                                            ],
                                            "Qassim Province": [
                                                "Buraidah", "Unaizah", "Ar Rass", "Al Mithnab", "Al Bukayriyah", "Al Badayea", "Uyun AlJiwa",
                                                "Riyadh Al Khabra", "Al Nabhaniyah", "Dhalm"
                                            ],
                                            "Hail Province": [
                                                "Hail", "Baqqa", "Al-Ghazalah", "Ash Shamli", "Turbah", "Al-Shinan", "Al Hait", "Al Hulayfah",
                                                "Al Uwayqilah", "Al Khuttah"
                                            ],
                                            "Najran Province": [
                                                "Najran", "Sharurah", "Habuna", "Badr Al Janoub", "Yadamah", "Thar", "Khubash", "Al Wadiah", "Bir Askar"
                                            ],
                                            "Jazan Province": [
                                                "Jazan", "Sabya", "Abu Arish", "Samtah", "Al Darb", "Baish", "Ahad Al Masarihah", "Farasan", "Al Aridhah",
                                                "Al Tuwal", "Fifa", "Dhamad", "Harub", "Al Harth", "Ar Rayth", "Damad"
                                            ],
                                            "Al Bahah Province": [
                                                "Al Bahah", "Baljurashi", "Al Mandaq", "Al Aqiq", "Qilwah", "Al Qura", "Al-Makhwah", "Al-Hajrah"
                                            ],
                                            "Northern Borders Province": [
                                                "Arar", "Rafha", "Turaif", "Al Uwayqilah", "Al Aujam"
                                            ],
                                            "Al Jawf Province": [
                                                "Sakakah", "Dumat Al-Jandal", "Tabarjal", "Qurayyat", "Al Hadithah"
                                            ]
                                        },
                                        india: {
                                            "Maharashtra": [
                                            "Mumbai", "Pune", "Nagpur", "Nashik", "Thane", "Aurangabad", "Solapur", "Kolhapur", "Amravati", "Nanded"
                                            ],
                                            "Gujarat": [
                                            "Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar", "Gandhinagar", "Anand", "Nadiad", "Morbi"
                                            ],
                                            "Delhi": [
                                            "New Delhi", "Dwarka", "Karol Bagh", "Rohini", "Lajpat Nagar", "Saket", "Mayur Vihar", "Vasant Kunj", "Janakpuri", "Connaught Place"
                                            ],
                                            "Karnataka": [
                                            "Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum", "Gulbarga", "Davangere", "Bellary", "Tumkur", "Udupi"
                                            ],
                                            "Tamil Nadu": [
                                            "Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem", "Tirunelveli", "Vellore", "Erode", "Thoothukudi", "Dindigul"
                                            ],
                                            "Uttar Pradesh": [
                                            "Lucknow", "Kanpur", "Varanasi", "Agra", "Meerut", "Allahabad", "Ghaziabad", "Noida", "Bareilly", "Aligarh"
                                            ],
                                            "West Bengal": [
                                            "Kolkata", "Howrah", "Durgapur", "Asansol", "Siliguri", "Malda", "Kharagpur", "Bardhaman", "Haldia", "Berhampore"
                                            ],
                                            "Rajasthan": [
                                            "Jaipur", "Udaipur", "Jodhpur", "Kota", "Bikaner", "Ajmer", "Alwar", "Sikar", "Bhilwara", "Chittorgarh"
                                            ],
                                            "Punjab": [
                                            "Amritsar", "Ludhiana", "Jalandhar", "Patiala", "Bathinda", "Mohali", "Hoshiarpur", "Moga", "Pathankot", "Firozpur"
                                            ],
                                            "Kerala": [
                                            "Kochi", "Thiruvananthapuram", "Kozhikode", "Kollam", "Thrissur", "Alappuzha", "Palakkad", "Kottayam", "Kannur", "Malappuram"
                                            ]
                                        },
                                        usa: {
                                            "California": [
                                            "Los Angeles", "San Diego", "San Jose", "San Francisco", "Fresno", "Sacramento", "Long Beach", "Oakland", "Bakersfield", "Anaheim"
                                            ],
                                            "Texas": [
                                            "Houston", "Dallas", "Austin", "San Antonio", "Fort Worth", "El Paso", "Arlington", "Plano", "Corpus Christi", "Lubbock"
                                            ],
                                            "Florida": [
                                            "Miami", "Orlando", "Tampa", "Jacksonville", "Tallahassee", "St. Petersburg", "Hialeah", "Fort Lauderdale", "Cape Coral", "Gainesville"
                                            ],
                                            "New York": [
                                            "New York City", "Buffalo", "Rochester", "Yonkers", "Syracuse", "Albany", "New Rochelle", "Mount Vernon", "Schenectady", "Utica"
                                            ],
                                            "Illinois": [
                                            "Chicago", "Springfield", "Naperville", "Peoria", "Rockford", "Joliet", "Aurora", "Elgin", "Waukegan", "Cicero"
                                            ],
                                            "Pennsylvania": [
                                            "Philadelphia", "Pittsburgh", "Allentown", "Erie", "Reading", "Scranton", "Bethlehem", "Lancaster", "Harrisburg", "York"
                                            ],
                                            "Ohio": [
                                            "Columbus", "Cleveland", "Cincinnati", "Toledo", "Akron", "Dayton", "Parma", "Canton", "Youngstown", "Lorain"
                                            ],
                                            "Georgia": [
                                            "Atlanta", "Savannah", "Augusta", "Columbus", "Macon", "Athens", "Sandy Springs", "Roswell", "Albany", "Johns Creek"
                                            ],
                                            "Michigan": [
                                            "Detroit", "Lansing", "Ann Arbor", "Grand Rapids", "Flint", "Warren", "Sterling Heights", "Dearborn", "Livonia", "Troy"
                                            ],
                                            "North Carolina": [
                                            "Charlotte", "Raleigh", "Durham", "Greensboro", "Winston-Salem", "Fayetteville", "Cary", "Wilmington", "High Point", "Chapel Hill"
                                            ]
                                        },
                                        canada: {
                                            "Ontario": [
                                            "Toronto", "Ottawa", "Hamilton", "Mississauga", "Brampton", "London", "Markham", "Kitchener", "Windsor", "Vaughan"
                                            ],
                                            "British Columbia": [
                                            "Vancouver", "Victoria", "Kelowna", "Surrey", "Burnaby", "Richmond", "Abbotsford", "Coquitlam", "Nanaimo", "Kamloops"
                                            ],
                                            "Alberta": [
                                            "Calgary", "Edmonton", "Red Deer", "Lethbridge", "St. Albert", "Medicine Hat", "Grande Prairie", "Airdrie", "Spruce Grove", "Leduc"
                                            ],
                                            "Quebec": [
                                            "Montreal", "Quebec City", "Laval", "Gatineau", "Longueuil", "Sherbrooke", "Saguenay", "Trois-Rivières", "Terrebonne", "Saint-Jean-sur-Richelieu"
                                            ],
                                            "Manitoba": [
                                            "Winnipeg", "Brandon", "Steinbach", "Thompson", "Portage la Prairie", "Selkirk", "Winkler", "Morden", "Dauphin", "Flin Flon"
                                            ],
                                            "Saskatchewan": [
                                            "Saskatoon", "Regina", "Moose Jaw", "Prince Albert", "Swift Current", "North Battleford", "Yorkton", "Estevan", "Weyburn", "Martensville"
                                            ],
                                            "Nova Scotia": [
                                            "Halifax", "Sydney", "Truro", "New Glasgow", "Bridgewater", "Kentville", "Amherst", "Yarmouth", "Windsor", "Antigonish"
                                            ],
                                            "New Brunswick": [
                                            "Moncton", "Fredericton", "Saint John", "Bathurst", "Miramichi", "Dieppe", "Edmundston", "Campbellton", "Shediac", "Quispamsis"
                                            ],
                                            "Newfoundland": [
                                            "St. John's", "Corner Brook", "Gander", "Mount Pearl", "Conception Bay South", "Paradise", "Grand Falls-Windsor", "Happy Valley-Goose Bay", "Clarenville", "Marystown"
                                            ],
                                            "Prince Edward Island": [
                                            "Charlottetown", "Summerside", "Stratford", "Cornwall", "Montague", "Kensington", "Souris", "Georgetown", "Alberton", "Tignish"
                                            ]
                                        },
                                        australia: {
                                            "New South Wales": [
                                            "Sydney", "Newcastle", "Wollongong", "Albury", "Maitland", "Wagga Wagga", "Port Macquarie", "Tamworth", "Orange", "Dubbo"
                                            ],
                                            "Victoria": [
                                            "Melbourne", "Geelong", "Ballarat", "Bendigo", "Shepparton", "Mildura", "Warrnambool", "Wodonga", "Traralgon", "Wangaratta"
                                            ],
                                            "Queensland": [
                                            "Brisbane", "Cairns", "Gold Coast", "Townsville", "Toowoomba", "Mackay", "Rockhampton", "Bundaberg", "Hervey Bay", "Gladstone"
                                            ],
                                            "Western Australia": [
                                            "Perth", "Bunbury", "Albany", "Geraldton", "Kalgoorlie", "Broome", "Busselton", "Esperance", "Port Hedland", "Karratha"
                                            ],
                                            "South Australia": [
                                            "Adelaide", "Mount Gambier", "Whyalla", "Murray Bridge", "Port Augusta", "Port Pirie", "Gawler", "Victor Harbor", "Port Lincoln", "Coober Pedy"
                                            ],
                                            "Tasmania": [
                                            "Hobart", "Launceston", "Devonport", "Burnie", "Ulverstone", "New Norfolk", "Queenstown", "Sorell", "George Town", "Wynyard"
                                            ],
                                            "ACT": [
                                            "Canberra", "Belconnen", "Gungahlin", "Tuggeranong", "Woden Valley", "Weston Creek", "Molonglo Valley", "Fyshwick", "Narrabundah", "Kingston"
                                            ],
                                            "Northern Territory": [
                                            "Darwin", "Alice Springs", "Palmerston", "Katherine", "Nhulunbuy", "Tennant Creek", "Jabiru", "Yulara", "Howard Springs", "Batchelor"
                                            ],
                                            "Central Australia": [
                                            "Yulara", "Tennant Creek", "Hermannsburg", "Kings Canyon", "Papunya", "Ti Tree", "Santa Teresa", "Docker River", "Areyonga", "Yuendumu"
                                            ],
                                            "Top End": [
                                            "Katherine", "Nhulunbuy", "Jabiru", "Maningrida", "Wadeye", "Gunbalanya", "Coomalie", "Ngukurr", "Ramingining", "Beswick"
                                            ]
                                        },
                                        uk: {
                                            "England": [
                                            "London", "Manchester", "Birmingham", "Liverpool", "Bristol", "Leicester", "Nottingham", "Coventry", "Southampton", "Reading"
                                            ],
                                            "Scotland": [
                                            "Edinburgh", "Glasgow", "Aberdeen", "Dundee", "Inverness", "Stirling", "Perth", "Paisley", "Ayr", "Motherwell"
                                            ],
                                            "Wales": [
                                            "Cardiff", "Swansea", "Newport", "Wrexham", "Bangor", "Llandudno", "Barry", "Merthyr Tydfil", "Pontypridd", "Aberystwyth"
                                            ],
                                            "Northern Ireland": [
                                            "Belfast", "Derry", "Lisburn", "Newry", "Armagh", "Craigavon", "Bangor", "Antrim", "Coleraine", "Enniskillen"
                                            ],
                                            "Kent": [
                                            "Canterbury", "Maidstone", "Ashford", "Dartford", "Tonbridge", "Folkestone", "Tunbridge Wells", "Sevenoaks", "Gravesend", "Margate"
                                            ],
                                            "Essex": [
                                            "Chelmsford", "Colchester", "Basildon", "Southend-on-Sea", "Harlow", "Brentwood", "Clacton-on-Sea", "Braintree", "Grays", "Maldon"
                                            ],
                                            "Surrey": [
                                            "Guildford", "Woking", "Epsom", "Redhill", "Farnham", "Walton-on-Thames", "Camberley", "Leatherhead", "Reigate", "Godalming"
                                            ],
                                            "Lancashire": [
                                            "Preston", "Blackpool", "Burnley", "Lancaster", "Blackburn", "Accrington", "Chorley", "Morecambe", "Nelson", "Fleetwood"
                                            ],
                                            "Devon": [
                                            "Exeter", "Plymouth", "Torquay", "Barnstaple", "Exmouth", "Newton Abbot", "Tiverton", "Bideford", "Paignton", "Honiton"
                                            ],
                                            "Yorkshire": [
                                            "Leeds", "Sheffield", "Bradford", "York", "Huddersfield", "Hull", "Wakefield", "Doncaster", "Halifax", "Harrogate"
                                            ]
                                        },
                                        germany: {
                                            "Bavaria": [
                                            "Munich", "Nuremberg", "Augsburg", "Regensburg", "Ingolstadt", "Würzburg", "Fürth", "Erlangen", "Bayreuth", "Bamberg"
                                            ],
                                            "Berlin": [
                                            "Berlin", "Charlottenburg", "Friedrichshain", "Kreuzberg", "Neukölln", "Prenzlauer Berg", "Spandau", "Steglitz", "Wedding", "Zehlendorf"
                                            ],
                                            "Hesse": [
                                            "Frankfurt", "Wiesbaden", "Darmstadt", "Kassel", "Offenbach", "Hanau", "Marburg", "Gießen", "Fulda", "Rüsselsheim"
                                            ],
                                            "Saxony": [
                                            "Dresden", "Leipzig", "Chemnitz", "Zwickau", "Plauen", "Görlitz", "Freiberg", "Bautzen", "Pirna", "Hoyerswerda"
                                            ],
                                            "Hamburg": [
                                            "Hamburg", "Altona", "Eimsbüttel", "Wandsbek", "Harburg", "Bergedorf", "Hamburg-Mitte", "Hamburg-Nord", "Lurup", "Niendorf"
                                            ],
                                            "Bremen": [
                                            "Bremen", "Bremerhaven", "Walle", "Vegesack", "Hemelingen", "Oberneuland", "Borgfeld", "Huchting", "Findorff", "Blumenthal"
                                            ],
                                            "Lower Saxony": [
                                            "Hannover", "Braunschweig", "Oldenburg", "Osnabrück", "Wolfsburg", "Göttingen", "Salzgitter", "Hildesheim", "Lüneburg", "Celle"
                                            ],
                                            "North Rhine": [
                                            "Cologne", "Düsseldorf", "Dortmund", "Essen", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mönchengladbach"
                                            ],
                                            "Saarland": [
                                            "Saarbrücken", "Neunkirchen", "Homburg", "Völklingen", "Sankt Ingbert", "Merzig", "Saarlouis", "Blieskastel", "St. Wendel", "Lebach"
                                            ],
                                            "Schleswig-Holstein": [
                                            "Kiel", "Lübeck", "Flensburg", "Neumünster", "Norderstedt", "Elmshorn", "Pinneberg", "Itzehoe", "Ahrensburg", "Bad Oldesloe"
                                            ]
                                        },
                                        france: {
                                            "Île-de-France": [
                                            "Paris", "Versailles", "Boulogne-Billancourt", "Saint-Denis", "Nanterre", "Créteil", "Courbevoie", "Argenteuil", "Montreuil", "Vitry-sur-Seine"
                                            ],
                                            "Provence": [
                                            "Marseille", "Nice", "Avignon", "Toulon", "Aix-en-Provence", "Cannes", "Antibes", "Grasse", "Hyères", "Fréjus"
                                            ],
                                            "Auvergne": [
                                            "Clermont-Ferrand", "Vichy", "Le Puy-en-Velay", "Montluçon", "Aurillac", "Issoire", "Moulins", "Cusset", "Riom", "Cournon-d'Auvergne"
                                            ],
                                            "Nouvelle-Aquitaine": [
                                            "Bordeaux", "Limoges", "Poitiers", "Pau", "La Rochelle", "Angoulême", "Périgueux", "Bergerac", "Bayonne", "Brive-la-Gaillarde"
                                            ],
                                            "Brittany": [
                                            "Rennes", "Brest", "Quimper", "Lorient", "Vannes", "Saint-Malo", "Saint-Brieuc", "Fougères", "Concarneau", "Morlaix"
                                            ],
                                            "Normandy": [
                                            "Rouen", "Caen", "Le Havre", "Cherbourg", "Évreux", "Dieppe", "Alençon", "Lisieux", "Fécamp", "Granville"
                                            ],
                                            "Occitanie": [
                                            "Toulouse", "Montpellier", "Nîmes", "Perpignan", "Béziers", "Carcassonne", "Albi", "Tarbes", "Montauban", "Narbonne"
                                            ],
                                            "Grand Est": [
                                            "Strasbourg", "Metz", "Nancy", "Reims", "Mulhouse", "Colmar", "Troyes", "Charleville-Mézières", "Épinal", "Haguenau"
                                            ],
                                            "Corsica": [
                                            "Ajaccio", "Bastia", "Corte", "Porto-Vecchio", "Calvi", "Sartène", "Bonifacio", "Propriano", "L'Île-Rousse", "Ghisonaccia"
                                            ],
                                            "Centre-Val de Loire": [
                                            "Orléans", "Tours", "Blois", "Châteauroux", "Bourges", "Chartres", "Vierzon", "Issoudun", "Dreux", "Loches"
                                            ]
                                        },
                                        uae: {
                                            "Abu Dhabi": [
                                            "Abu Dhabi", "Al Ain", "Madinat Zayed", "Baniyas", "Shahama", "Al Wathba", "Al Mafraq", "Al Dhafra", "Al Mirfa", "Ghayathi"
                                            ],
                                            "Dubai": [
                                            "Dubai", "Jebel Ali", "Hatta", "Deira", "Al Barsha", "Al Quoz", "Al Satwa", "Al Karama", "Bur Dubai", "Business Bay"
                                            ],
                                            "Sharjah": [
                                            "Sharjah", "Khor Fakkan", "Dibba Al-Hisn", "Kalba", "Al Dhaid", "Al Madam", "Mleiha", "Al Bataeh", "Hamriyah", "Wasit"
                                            ],
                                            "Ajman": [
                                            "Ajman", "Masfout", "Manama", "Al Nuaimia", "Al Rawda", "Al Zahra", "Al Jurf", "Al Hamidiya", "Al Rashidiya", "Helio"
                                            ],
                                            "Fujairah": [
                                            "Fujairah", "Dibba Al-Fujairah", "Masafi", "Mirbah", "Qidfa", "Al Bidya", "Al Fahlain", "Al Halah", "Al Aqah", "Dadna"
                                            ],
                                            "Ras Al Khaimah": [
                                            "RAK City", "Al Rams", "Khatt", "Digdaga", "Julphar", "Al Hamra", "Al Jazirah Al Hamra", "Masafi RAK", "Al Ghail", "Sha'am"
                                            ],
                                            "Umm Al Quwain": [
                                            "UAQ City", "Falaj Al Mualla", "Al Rafaah", "Al Salamah", "Al Haditha", "Al Abraq", "Al Raafa", "Al Khor", "Al Maidan", "Umm Al Quwain Free Zone"
                                            ],
                                            "Al Dhafra": [
                                            "Ghayathi", "Mirfa", "Liwa Oasis", "Ruwais", "Sila", "Al Sila", "Jebel Dhanna", "Al Hamra Airport", "Madinat Zayed", "Dalma Island"
                                            ],
                                            "Al Ain Region": [
                                            "Al Yahar", "Sweihan", "Al Saad", "Al Khazna", "Al Hayer", "Nahel", "Remah", "Mezyad", "Al Foah", "Al Quaa"
                                            ],
                                            "Deira": [
                                            "Deira", "Naif", "Al Rigga", "Al Muraqqabat", "Port Saeed", "Al Baraha", "Al Sabkha", "Hor Al Anz", "Abu Hail", "Al Khabaisi"
                                            ]
                                        },
                                        japan: {
                                            "Tokyo": ["Tokyo", "Shinjuku", "Shibuya", "Setagaya", "Toshima", "Meguro", "Koto", "Minato", "Nakano", "Bunkyo"],
                                            "Osaka": ["Osaka", "Sakai", "Hirakata", "Toyonaka", "Takatsuki", "Moriguchi", "Ibaraki", "Izumi", "Kadoma", "Suita"],
                                            "Kyoto": ["Kyoto", "Uji", "Kameoka", "Fukuchiyama", "Maizuru", "Nagaokakyo", "Yawata", "Muko", "Joyo", "Kizugawa"],
                                            "Hokkaido": ["Sapporo", "Hakodate", "Asahikawa", "Obihiro", "Kushiro", "Tomakomai", "Kitami", "Muroran", "Chitose", "Iwamizawa"],
                                            "Fukuoka": ["Fukuoka", "Kitakyushu", "Kurume", "Omuta", "Iizuka", "Tagawa", "Yukuhashi", "Nogata", "Chikushino", "Kasuga"],
                                            "Aichi": ["Nagoya", "Toyota", "Okazaki", "Ichinomiya", "Toyohashi", "Kasugai", "Anjo", "Kariya", "Komaki", "Inazawa"],
                                            "Hyogo": ["Kobe", "Himeji", "Amagasaki", "Nishinomiya", "Ashiya", "Akashi", "Kakogawa", "Takarazuka", "Sanda", "Itami"],
                                            "Hiroshima": ["Hiroshima", "Kure", "Fukuyama", "Onomichi", "Mihara", "Hatsukaichi", "Shobara", "Akitakata", "Takehara", "Higashihiroshima"],
                                            "Miyagi": ["Sendai", "Ishinomaki", "Tagajo", "Shiogama", "Kesennuma", "Natori", "Tome", "Kurihara", "Osaki", "Higashimatsushima"],
                                            "Shizuoka": ["Shizuoka", "Hamamatsu", "Fujinomiya", "Fujieda", "Mishima", "Numazu", "Izu", "Kakegawa", "Gotemba", "Yaizu"]
                                        }

                                    };

                                    function loadStates() {
                                        const country = document.getElementById('country').value;
                                        const stateSelect = document.getElementById('state');
                                        const citySelect = document.getElementById('city');

                                        stateSelect.innerHTML = '<option value="">Select State</option>';
                                        citySelect.innerHTML = '<option value="">Select City</option>';

                                        if (data[country]) {
                                            Object.keys(data[country]).forEach(state => {
                                                const option = document.createElement('option');
                                                option.value = state;
                                                option.textContent = state;
                                                stateSelect.appendChild(option);
                                            });
                                        }
                                    }

                                    function loadCities() {
                                        const country = document.getElementById('country').value;
                                        const state = document.getElementById('state').value;
                                        const citySelect = document.getElementById('city');

                                        citySelect.innerHTML = '<option value="">Select City</option>';

                                        if (data[country] && data[country][state]) {
                                            data[country][state].forEach(city => {
                                                const option = document.createElement('option');
                                                option.value = city;
                                                option.textContent = city;
                                                citySelect.appendChild(option);
                                            });
                                        }
                                    }
                                    </script>




                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Pin Code <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="pin_code"
                                            class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Enter pin code"
                                            value="{{ old('pin_code') }}"
                                            maxlength="5"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            inputmode="numeric" />


                                        @error('pin_code')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                  
                                    <div class="flex justify-end">
                                        <button type="button" onclick="showStep(2)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">Next</button>
                                    </div>

                                </div>

                                <!-- Step 2: Education -->
                                <div id="step-2" class="step hidden">

                                    <!-- Container for multiple education entries -->
                                    @php
                                        $educationCount = count(old('high_education', [null]));
                                    @endphp

                                    <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                                        @for ($i = 0; $i < $educationCount; $i++)
                                            <div
                                                class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">

                                                {{-- Highest Qualification --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest
                                                        qualification <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="high_education[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select highest qualification</option>
                                                        <option value="high_school" {{ old("high_education.$i") == 'high_school' ? 'selected' : '' }}>
                                                            High School</option>
                                                        <option value="diploma" {{ old("high_education.$i") == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                                        <option value="bachelor" {{ old("high_education.$i") == 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                                                        <option value="master" {{ old("high_education.$i") == 'master' ? 'selected' : '' }}>Master's Degree</option>
                                                        <option value="phd" {{ old("high_education.$i") == 'phd' ? 'selected' : '' }}>Ph.D.</option>
                                                    </select>
                                                    @error("high_education.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Field of Study --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Field of
                                                        study <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="field_of_study[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select field of study</option>
                                                        <option value="engineering" {{ old("field_of_study.$i") == 'engineering' ? 'selected' : '' }}>
                                                            Engineering</option>
                                                        <option value="science" {{ old("field_of_study.$i") == 'science' ? 'selected' : '' }}>Science</option>
                                                        <option value="commerce" {{ old("field_of_study.$i") == 'commerce' ? 'selected' : '' }}>Commerce</option>
                                                        <option value="arts" {{ old("field_of_study.$i") == 'arts' ? 'selected' : '' }}>Arts</option>
                                                        <option value="medicine" {{ old("field_of_study.$i") == 'medicine' ? 'selected' : '' }}>Medicine</option>
                                                        <option value="law" {{ old("field_of_study.$i") == 'law' ? 'selected' : '' }}>Law</option>
                                                        <option value="education" {{ old("field_of_study.$i") == 'education' ? 'selected' : '' }}>Education</option>
                                                        <option value="management" {{ old("field_of_study.$i") == 'management' ? 'selected' : '' }}>Management</option>
                                                        <option value="other" {{ old("field_of_study.$i") == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error("field_of_study.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Institution Name --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Institution
                                                        name <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input name="institution[]" type="text"
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("institution.$i") }}"
                                                        placeholder="Enter institution name" />
                                                    @error("institution.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Graduation Year --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Graduation
                                                        year <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="graduate_year[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select year of passing</option>
                                                        @foreach(range(date('Y'), 2010) as $year)
                                                            <option value="{{ $year }}" {{ old("graduate_year.$i") == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endforeach
                                                        <option value="2010-2014" {{ old("graduate_year.$i") == '2010-2014' ? 'selected' : '' }}>2010-2014</option>
                                                        <option value="2010" {{ old("graduate_year.$i") == '    2010' ? 'selected' : '' }}>
                                                            Before 2010</option>
                                                    </select>
                                                    @error("graduate_year.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="button"
                                                    class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg"
                                                    style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                                            </div>
                                        @endfor

                                    </div>

                                    <div class="col-span-2">
                                        <button type="button" id="add-education" class="text-green-600 text-sm mt-2 mb-2">Add
                                            education +</button>
                                    </div>

                                    <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(1)"
                                            class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(3)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>


                                </div>

                                <!-- Step 3: Work Experience -->
                                <div id="step-3" class="step hidden">

                                    <!-- Container for multiple work experience entries -->
                                    @php
                                        $workCount = count(old('job_role', [null]));
                                    @endphp

                                    <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                                        @for ($i = 0; $i < $workCount; $i++)
                                            @php
                                                $isWorking = old("currently_working.$i") == 'on';
                                            @endphp
                                            <div
                                                class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300"
                                                x-data="{
                                                    working: {{ $isWorking ? 'true' : 'false' }},
                                                    index: {{ $i }},
                                                    init() {
                                                        this.$watch('working', value => {
                                                            const entries = document.querySelectorAll('.work-entry');
                                                            entries.forEach((entry, idx) => {
                                                                const checkbox = entry.querySelector('.currently-working-checkbox');
                                                                const endInput = entry.querySelector('.datepicker-end');

                                                                if (value) {
                                                                    // If this checkbox is checked, disable all others
                                                                    if (idx !== this.index) {
                                                                        checkbox.disabled = true;
                                                                        checkbox.checked = false;
                                                                        if (entry.__x) entry.__x.$data.working = false;
                                                                    } else {
                                                                        endInput.value = '';
                                                                        endInput.readOnly = true;
                                                                        endInput.disabled = true;
                                                                    }
                                                                } else {
                                                                    // Enable all checkboxes and date inputs
                                                                    checkbox.disabled = false;
                                                                    endInput.readOnly = false;
                                                                    endInput.disabled = false;
                                                                }
                                                            });
                                                        });
                                                    }
                                                }"
                                                x-init="init()"
                                            >

                                                {{-- Job Role --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Title <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="job_role[]" class="w-full border rounded-md p-2"
                                                        placeholder="e.g. Software Engineer" value="{{ old("job_role.$i") }}" />
                                                    @error("job_role.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Organization --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Organization <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="organization[]" class="w-full border rounded-md p-2"
                                                        placeholder="e.g. ABC Corp" value="{{ old("organization.$i") }}" />
                                                    @error("organization.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Start Date --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Started From <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="date" name="starts_from[]" class="datepicker-start w-full border rounded-md p-2"
                                                        value="{{ old("starts_from.$i") }}" max="{{ date('Y-m-d') }}"/>
                                                    @error("starts_from.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- End Date & Checkbox --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">To <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="date" name="end_to[]" class="w-full border rounded-md p-2 datepicker-end"
                                                        :disabled="working" :readonly="working"
                                                        :value="working ? '' : '{{ old("end_to.$i") }}'" max="{{ date('Y-m-d') }}"/>
                                                    @error("end_to.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror

                                                    <label class="inline-flex items-center mt-2 space-x-2">
                                                        <input type="checkbox" class="currently-working-checkbox"
                                                            name="currently_working[{{ $i }}]" x-model="working"
                                                            {{ $isWorking ? 'checked' : '' }}>
                                                        <span>I currently work here</span>
                                                    </label>
                                                </div>

                                                {{-- Remove Button --}}
                                                <button type="button"
                                                    class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg"
                                                    style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                                            </div>
                                        @endfor
                                    </div>

                                    <div class="col-span-2">
                                        <button type="button" id="add-work" class="text-green-600 text-sm mt-2 mb-2">Add work experience +</button>
                                    </div>

                                    <!-- <script>
                                        const workContainer = document.getElementById('work-container');
                                        const addWorkBtn = document.getElementById('add-work');

                                        addWorkBtn.addEventListener('click', () => {
                                            const firstEntry = workContainer.querySelector('.work-entry');
                                            const clone = firstEntry.cloneNode(true);

                                            // Clear inputs and errors
                                            clone.querySelectorAll('input').forEach(input => {
                                                if (input.type === 'checkbox') {
                                                    input.checked = false;
                                                    input.disabled = false;
                                                } else {
                                                    input.value = '';
                                                    input.readOnly = false;
                                                    input.disabled = false;
                                                }
                                            });

                                            clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());
                                            clone.querySelector('.remove-work').style.display = 'block';

                                            workContainer.appendChild(clone);
                                            Alpine.initTree(clone);
                                        });

                                        workContainer.addEventListener('click', (e) => {
                                            if (e.target.classList.contains('remove-work')) {
                                                const entry = e.target.closest('.work-entry');
                                                entry.remove();
                                            }
                                        });
                                    </script> -->


                                    <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(2)"
                                            class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(4)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>

                                </div>


                                <!-- Step 4: Skills -->
                                <div id="step-4" class="step hidden">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Skills <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="skills" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="e.g. AWS Certified, Python, Project Management"
                                            value="{{ old('skills') }}" />
                                        @error('skills')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Area of Interests <span style="color: red; font-size: 17px;">*</span></label>
                                        <select class="w-full border rounded-md p-2 mt-1" name="interest">
                                            <option value="" disabled {{ old('interest') ? '' : 'selected' }}>Select an
                                                area</option>
                                            <option value="cloud-computing" {{ old('interest') == 'cloud-computing' ? 'selected' : '' }}>Cloud Computing</option>
                                            <option value="web-development" {{ old('interest') == 'web-development' ? 'selected' : '' }}>Web Development</option>
                                            <option value="data-science" {{ old('interest') == 'data-science' ? 'selected' : '' }}>Data Science</option>
                                            <option value="machine-learning" {{ old('interest') == 'machine-learning' ? 'selected' : '' }}>Machine Learning</option>
                                            <option value="cybersecurity" {{ old('interest') == 'cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                            <option value="digital-marketing" {{ old('interest') == 'digital-marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                        </select>

                                        @error('interest')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Job Categories <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="job_category" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="e.g. Software Engineer, Data Analyst"
                                            value="{{ old('job_category') }}" />
                                        @error('job_category')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Website Link </label>
                                        <input type="url" name="website_link" class="w-full border rounded-md p-2 mt-1" 
                                            placeholder="e.g. https://www.example.com"
                                            value="{{ old('website_link') }}" />
                                        @error('website_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Portfolio Link</label>
                                        <input type="url" name="portfolio_link" class="w-full border rounded-md p-2" mt-1
                                            placeholder="e.g. https://portfolio.example.com"
                                            value="{{ old('portfolio_link') }}" />
                                        @error('portfolio_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-between">
                                        <button type="button" onclick="showStep(3)" class="px-4 py-2 border rounded-md mt-3">
                                            Back
                                        </button>
                                        <button type="button" onclick="showStep(5)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                            Next
                                        </button>
                                    </div>


                                </div>

                                <!-- Step 5: Additional Information -->
                                <div id="step-5" class="step hidden">

                                    <!-- CV Template Download -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">CV template
                                            <span class="text-xs text-gray-500">(Download CV template and make sure the
                                                template you upload must follow the attached template)</span>
                                        </label>
                                        <button class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs btn mt-2">
                                            Download CV template
                                        </button>

                                    </div>
                                    <!-- Upload Resume -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">
                                            Upload resume <span style="color: red;">*</span>
                                        </label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>

                                        {{-- Show old selected file name here --}}
                                        @if(session('old_resume_name'))
                                            <p class="text-green-600 text-sm mt-1">
                                                Previously selected: {{ session('old_resume_name') }}
                                            </p>
                                        @endif

                                        {{-- Show validation error --}}
                                        @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <script>
                                        const resumeInput = document.getElementById('resumeFile');
                                        const resumeFilenameDisplay = document.getElementById('resumeFilename');

                                        // Show filename when user selects a file
                                        resumeInput.addEventListener('change', function () {
                                            if (this.files.length > 0) {
                                                const fileName = this.files[0].name;
                                                resumeFilenameDisplay.textContent = "Selected: " + fileName;
                                                sessionStorage.setItem('resumeFileName', fileName); // store temporarily
                                            }
                                        });

                                        // On page load, restore filename if available
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const savedFileName = sessionStorage.getItem('resumeFileName');
                                            if (savedFileName) {
                                                resumeFilenameDisplay.textContent = "Previously selected: " + savedFileName;
                                            }
                                        });
                                    </script>



                                    <!-- Upload Profile Picture -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">Upload profile picture <span style="color: red; font-size: 17px;">*</span></label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="profile_picture" accept="image/png, image/jpeg"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>
                                            @error('profile_picture')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                    </div>

                                    <div class="text-sm">
                                        <label class="flex items-start gap-2 mt-3">
                                            <input type="checkbox" id="termsCheckbox" name="terms" {{ old('terms') ? 'checked' : '' }}>

                                            <span>
                                                I have read and agreed to
                                                <a href="#" class="text-blue-600 underline">terms and conditions</a>
                                                <ul class="list-disc ml-5 mt-1 space-y-1 text-gray-700">
                                                    <li>Users must create an account to access full course materials and resources.</li>
                                                    <li>Course content is for personal learning use only and cannot be redistributed.</li>
                                                    <li>Progress and certification are based on course completion and assessment scores.</li>
                                                    <li>Platform may send notifications about new courses, updates, or promotions.</li>
                                                    <li>Refunds for paid courses are subject to platform’s refund policy.</li>
                                                </ul>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex justify-between mt-4">
                                        <button type="button" onclick="showStep(4)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button id="submitBtn" type="submit" disabled class="bg-blue-600 text-white px-6 py-2 rounded-md opacity-50 cursor-not-allowed">
                                            Submit
                                        </button>
                                    </div>

                                    <script>
                                        const checkbox = document.getElementById('termsCheckbox');
                                        const submitBtn = document.getElementById('submitBtn');

                                        checkbox.addEventListener('change', function () {
                                            if (this.checked) {
                                                submitBtn.disabled = false;
                                                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                            } else {
                                                submitBtn.disabled = true;
                                                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                            }
                                        });
                                    </script>


                                </div>
                            </form>
                            
                        </div>

                        


                        <!-- </div> -->
                    </div>
                </div>
            </div>


        </div>

        @include('site.jobseeker.componants.footer')

<!-- Multiple educations  -->
<!-- <script>
    // Function to handle education add/remove
    const educationContainer = document.getElementById('education-container');
    const addEducationBtn = document.getElementById('add-education');

    addEducationBtn.addEventListener('click', () => {
        const firstEntry = educationContainer.querySelector('.education-entry');
        const clone = firstEntry.cloneNode(true);

        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

        clone.querySelector('.remove-education').style.display = 'block';

        educationContainer.appendChild(clone);
    });

    educationContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-education')) {
            const entry = e.target.closest('.education-entry');
            entry.remove();
        }
    });
</script> -->     
<!-- <script>
    const educationContainer = document.getElementById('education-container');
    const addEducationBtn = document.getElementById('add-education');

    addEducationBtn.addEventListener('click', () => {
        const firstEntry = educationContainer.querySelector('.education-entry');
        const clone = firstEntry.cloneNode(true);

        // Clear inputs and selects
        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

        // Show remove button
        clone.querySelector('.remove-education').style.display = 'block';

        // Append clone
        educationContainer.appendChild(clone);

        // Wait a moment and apply validation rules to new inputs
        setTimeout(() => {
            const container = $(clone);

            container.find('select[name="high_education[]"]').rules('add', {
                required: true,
                messages: { required: "Please select qualification" }
            });
            container.find('select[name="field_of_study[]"]').rules('add', {
                required: true,
                messages: { required: "Please select field of study" }
            });
            container.find('input[name="institution[]"]').rules('add', {
                required: true,
                messages: { required: "Institution name is required" }
            });
            container.find('select[name="graduate_year[]"]').rules('add', {
                required: true,
                messages: { required: "Graduation year is required" }
            });
        }, 100); // ensure DOM render before applying rules
    });

    educationContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-education')) {
            const entry = e.target.closest('.education-entry');
            entry.remove();
        }
    });
</script> -->
<script>
    const educationContainer = document.getElementById('education-container');

    function updateQualificationOptions() {
        // Get all selected values
        const selectedValues = Array.from(educationContainer.querySelectorAll('select[name="high_education[]"]'))
            .map(select => select.value)
            .filter(val => val !== '');

        // Get all qualification selects
        const allSelects = educationContainer.querySelectorAll('select[name="high_education[]"]');

        allSelects.forEach(select => {
            const currentValue = select.value;

            // Show all options first
            Array.from(select.options).forEach(option => {
                if (option.value !== "") {
                    option.style.display = "block";
                }
            });

            // Now hide already selected values in other selects
            selectedValues.forEach(value => {
                if (value !== currentValue) {
                    const optionToHide = select.querySelector(`option[value="${value}"]`);
                    if (optionToHide) optionToHide.style.display = "none";
                }
            });
        });
    }

    // Initial call
    updateQualificationOptions();

    // Add change event listener to update dropdowns when any value is selected
    educationContainer.addEventListener('change', (e) => {
        if (e.target.name === "high_education[]") {
            updateQualificationOptions();
        }
    });

    // When you add new education entry, make sure to call updateQualificationOptions after adding it
    const addEducationBtn = document.getElementById('add-education');
    addEducationBtn.addEventListener('click', () => {
        const firstEntry = educationContainer.querySelector('.education-entry');
        const clone = firstEntry.cloneNode(true);

        // Clear values
        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

        // Show remove button
        clone.querySelector('.remove-education').style.display = 'block';

        // Append clone
        educationContainer.appendChild(clone);

        // Apply validation rules if needed
        setTimeout(() => {
            const container = $(clone);
            container.find('select[name="high_education[]"]').rules('add', {
                required: true,
                messages: { required: "Please select qualification" }
            });
            container.find('select[name="field_of_study[]"]').rules('add', {
                required: true,
                messages: { required: "Please select field of study" }
            });
            container.find('input[name="institution[]"]').rules('add', {
                required: true,
                messages: { required: "Institution name is required" }
            });
            container.find('select[name="graduate_year[]"]').rules('add', {
                required: true,
                messages: { required: "Graduation year is required" }
            });

            updateQualificationOptions(); // <=== Important
        }, 100);
    });

    // Remove entry
    educationContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-education')) {
            const entry = e.target.closest('.education-entry');
            entry.remove();
            updateQualificationOptions(); // <=== Important
        }
    });
</script>
<!-- Multiple exprience  -->    
<script>
    const workContainer = document.getElementById('work-container');
    const addWorkBtn = document.getElementById('add-work');

    addWorkBtn.addEventListener('click', () => {
        const firstEntry = workContainer.querySelector('.work-entry');
        const clone = firstEntry.cloneNode(true);

        // Reset fields
        clone.querySelectorAll('input').forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = false;
                input.disabled = false;
            } else {
                input.value = '';
                input.readOnly = false;
                input.disabled = false;
            }
        });

        // Remove old validation errors
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());
        clone.querySelector('.remove-work').style.display = 'block';

        workContainer.appendChild(clone);
        Alpine.initTree(clone);

        // Add validation rules
        setTimeout(() => {
            const container = $(clone);

            container.find('input[name="job_title[]"]').rules('add', {
                required: true,
                messages: { required: "Job title is required" }
            });
            container.find('input[name="company_name[]"]').rules('add', {
                required: true,
                messages: { required: "Company name is required" }
            });
            container.find('input[name="start_date[]"]').rules('add', {
                required: true,
                messages: { required: "Start date is required" }
            });
            container.find('input[name="end_date[]"]').rules('add', {
                required: true,
                messages: { required: "End date is required" }
            });
        }, 100);
    });

    workContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-work')) {
            const entry = e.target.closest('.work-entry');
            entry.remove();
        }
    });

</script>
<!-- multiple Tabs - steps   -->
<script>
    function showStep(step) {
        for (let i = 1; i <= 5; i++) {
            document.getElementById(`step-${i}`).classList.add('hidden');
            document.getElementById(`step-${i}-circle`).classList.remove('bg-blue-600', 'text-white');
        }
        document.getElementById(`step-${step}`).classList.remove('hidden');
        document.getElementById(`step-${step}-circle`).classList.add('bg-blue-600', 'text-white');
    }
</script>
<!-- Datapicker  -->
<script>
    $(document).ready(function () {
        // $('#dob').datepicker({
        //     format: 'yyyy-mm-dd',
        //     endDate: new Date(),
        //     autoclose: true,
        //     todayHighlight: true
        // });
        //     function initializeDatePickers() {
        //     $('.datepicker-start, .datepicker-end').datepicker({
        //         format: 'yyyy-mm-dd',
        //         endDate: new Date(),
        //         autoclose: true,
        //         todayHighlight: true
        //     });
        }

        initializeDatePickers();

        $('#add-work').on('click', function () {
            
            initializeDatePickers(); 
        });
    });

    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            setTimeout(() => {
                $('.datepicker-end:not(:disabled)').datepicker(); // or flatpickr()
            }, 100);
        });
    });

</script>
<!-- Alpine.js v3 CDN -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    $(document).ready(function () {
        var $submitBtn = $('#submitBtn');
        var $form = $('form');
        var $checkbox = $('#termsCheckbox'); // Make sure this is the actual ID of your checkbox

        function toggleSubmitButton() {
            if ($checkbox.is(':checked')) {
                $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
            } else {
                $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            }
        }

        // Always run this on page load (including error reloads)
        toggleSubmitButton();

        // On checkbox click, enable/disable submit
        $checkbox.on('change', function () {
            toggleSubmitButton();
        });

        // Prevent multiple submits
        $form.on('submit', function (e) {
            if ($submitBtn.prop('disabled')) {
                e.preventDefault();
                return;
            }

            $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        });
    });
</script>
<!-- Natioanl id and gender logic code -->
<script>
    $(document).ready(function () {
        function validateNationalIdInput() {
            const gender = $('#gender').val();
            const value = $('#national_id').val();

            if (gender === 'Male') {
                if (value && !value.startsWith('1')) {
                    $('#national_id').val('');
                }
            } else if (gender === 'Female') {
                if (value && !value.startsWith('2')) {
                    $('#national_id').val('');
                }
            }
        }

        $('#gender').on('change', function () {
            const selectedGender = $(this).val();

            // Clear National ID field when gender is changed
            $('#national_id').val('');

            // Attach input event for validation
            $('#national_id').off('input').on('input', function () {
                validateNationalIdInput();
            });
        });
    });
</script>


<!-- Step 2: jQuery Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<!-- all step field click on next button-->
<script>
    $(document).ready(function () {
        const form = $('#multiStepForm');

        form.validate({
            ignore: [],
            rules: {
                // Step 1 - Personal Info
                name: "required",
                gender: "required",
                dob: "required",
                national_id: "required",
                address: "required",
                city: "required",
                state: "required",
                country: "required",
                pin_code: "required",

                // Step 2 - Education
                'high_education[]': { required: true },
                'field_of_study[]': { required: true },
                'institution[]': { required: true },
                'graduate_year[]': { required: true },

                // Step 3 - Work Experience
                'job_role[]': { required: true },
                'organization[]': { required: true },
                'starts_from[]': { required: true },
                'end_to[]': {
                    required: function (element) {
                        const parent = $(element).closest('.work-entry');
                        const isWorking = parent.find('.currently-working-checkbox').prop('checked');
                        return !isWorking;
                    }
                },

                // Step 4 - Skills & Interest
                skills: "required",
                interest: "required",
                job_category: "required",

                // Step 5 - Uploads & Terms
                resume: "required",
                profile_picture: "required",
               
            },

            messages: {
                // Step 1
                name: "Full name is required",
                gender: "Please select gender",
                dob: "Date of birth is required",
                national_id: "National ID is required",
                address: "Address is required",
                city: "City is required",
                state: "State is required",
                country: "Country is required",
                pin_code: "Pin code is required",

                // Step 2
                'high_education[]': "Please select qualification",
                'field_of_study[]': "Please select field of study",
                'institution[]': "Institution name is required",
                'graduate_year[]': "Graduation year is required",

                // Step 3
                'job_role[]': "Job title is required",
                'organization[]': "Organization is required",
                'starts_from[]': "Start date is required",
                'end_to[]': "End date is required unless currently working",

                // Step 4
                skills: "Please enter your skills",
                interest: "Please select an area of interest",
                job_category: "Job category is required",

                // Step 5
                resume: "Please upload your resume",
                profile_picture: "Please upload a profile picture",
               
            },

            errorElement: 'p',
            errorPlacement: function (error, element) {
                error.addClass('text-red-600 text-sm mt-1');
                error.insertAfter(element);
            }
        });

        // Step navigation with validation check
        window.showStep = function (step) {
            const currentStep = $('.step:visible');
            let valid = true;

            currentStep.find('input, select, textarea').each(function () {
                if (!$(this).valid()) {
                    valid = false;
                }
            });

            if (!valid) return;

            for (let i = 1; i <= 5; i++) {
                $(`#step-${i}`).addClass('hidden');
                $(`#step-${i}-circle`).removeClass('bg-blue-600 text-white');
            }

            $(`#step-${step}`).removeClass('hidden');
            $(`#step-${step}-circle`).addClass('bg-blue-600 text-white');
        };

    });
</script>


<script src="https://unpkg.com/feather-icons"></script>
<script>
  feather.replace(); 
</script>