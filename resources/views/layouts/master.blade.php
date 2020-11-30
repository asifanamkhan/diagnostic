<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.head')
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar" id="app">
        @include('layouts.nav')
        <div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show session-message" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-danger alert-dismissible fade show session-message" role="alert">
                    <strong>{{ session('warning') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif     
        </div>
        <div class="app-main">
            @include('layouts.sidebar')
            
            @yield('content')

        </div> 
        @include('layouts.footer')  
    </div>
    @yield('modal')
    @include('layouts.script')

</body>
</html>    