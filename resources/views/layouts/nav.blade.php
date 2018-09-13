<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @if (config('app.name') == 'Laravel')
                Laravel
            @else 
                <img style="height:50px" x-enc="src" src="http://static.slickdealscdn.com/images/header/slickdealsLogo.png?6447">
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/threads">Front Page Deals</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbar-category-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Category
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbar-category-dropdown">
                        @foreach ($channels as $channel)
                            <a class="dropdown-item" href="/threads/{{$channel->slug}}">{{$channel->name}}</a>     
                        @endforeach
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbar-browse-ropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Browse
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbar-browse-dropdown">
                        <a class="dropdown-item" href="/threads/?uncommented=1">Uncommented Deals</a>                                 
                        <a class="dropdown-item" href="/threads/?popular=1" class="nav-link">Hot Deals</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/threads/create">Post a Deal</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @else
                    <user-notifications></user-notifications>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/profiles/{{ auth()->user()->name }}">My Profile</a>
                            
                            <a class="dropdown-item" href="/threads?createBy={{auth()->user()->name}}">My Posts</a>
                            
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>