    <header class="header header--merino" role="banner">
        <div class="header-inner header-inner--home">
            
            <div class="logo">
                <a href="{{url('/home')}}">
                    <svg class="icon icon-world_cup"><use xlink:href="{{url('/img')}}/sprite.svg#world_cup"></use></svg>
                </a>
            </div>

            <nav class="account-nav" role="navigation">
                <h2 class="account-nav__page">@yield('page-name')</h2>
                <ul class="account-nav__list">
                    @if ( Auth::user()->status === 'admin' )
                    <li><a href="{{url('home/cards')}}">Карты</a></li>
                    <li><a href="{{url('home/users')}}">Пользователи</a></li>
                    <li><a href="{{url('home/statistics')}}">Статистика</a></li>
                    @endif
                    <li><a href="{{url('home/tokens')}}">Токены</a></li>
                    @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'mediabuyer' )
                    <li><a href="{{url('home/costs')}}">Расходы</a></li>
                    <li><a href="{{url('home/wiki')}}">Wiki</a></li>
                    @endif
                </ul>
            </nav>

            <div class="user">
                <!-- <a href='{{ url("/home/myaccount") }}' class="user__name"> -->
                <a class="user__name" href="{{ url('/home/account') }}/{{ Auth::user()->id }}">
                    <svg class="icon icon-user"><use xlink:href="{{url('/img')}}/sprite.svg#user"></use></svg>
                    <span>
                        @if( Auth::check() )
                            {{ Auth::user()->name }}
                        @endif
                    </span>
                </a>
                <!-- </a> -->
                <p class="user__links">
                    <a href="{{url('logout')}}">
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        Выход
                    </a>
                </p>
            </div>

        </div>
    </header>