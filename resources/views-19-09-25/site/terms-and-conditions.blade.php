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
	<style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>
    
      @include('site.componants.navbar')


      <div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">{{ langLabel('terms_and_conditions') }}</h2>
                    </div>
                </div>
            </div>
        </div>


            <script>
                function toggleSection(id, iconId) {
                    const section = document.getElementById(id);
                    const icon = document.getElementById(iconId);
                    section.classList.toggle('hidden');
                    if (icon.classList.contains('rotate-180')) {
                        icon.classList.remove('rotate-180');
                    } else {
                        icon.classList.add('rotate-180');
                    }
                }
            </script>

             <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
              @if(session('success'))
                  <script>
                      document.addEventListener("DOMContentLoaded", function () {
                          Swal.fire({
                              icon: 'success',
                              title: '{{ session('success') }}',
                              text: 'Go to your profile to continue learning.',
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'Go to Profile'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  window.location.href = '{{ route('jobseeker.profile') }}';
                              }
                          });
                      });
                  </script>
              @endif

          

        <main class="w-11/12 mx-auto py-12">
            @include('admin.errors')
            @php
                $terms = \App\Models\CMS::where('slug', 'terms-and-conditions')->first();
            @endphp
            @if ($terms)
                {!! $terms->description !!}
            @endif
        </main>



    

         

         

          
          <style>
            .active-tab {
              border-bottom-color: #2563eb; /* Tailwind blue-600 */
              color: #2563eb;
            }
          </style>
        </div>
  

@include('site.componants.footer')