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
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Privacy Privacy</h2>
                    </div>
                </div>
            </div>
        </div>


          

        <main class="w-11/12 mx-auto py-12">
            @include('admin.errors')
            @php
                $privacyPolicy = \App\Models\CMS::where('slug', 'privacy-policy')->first();
            @endphp
            @if ($privacyPolicy)
                {!! $privacyPolicy->description !!}
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