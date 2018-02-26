<header class="header header--merino" role="banner">
    <div class="header-inner header-inner--home">

        <div class="logo">
            <a href="{{url('/home')}}">
                <svg class="icon icon-world_cup">
                    <use xlink:href="{{url('/img')}}/sprite.svg#world_cup"></use>
                </svg>
            </a>
        </div>

        <nav class="account-nav" role="navigation">
            <div class="account-nav__page" id="menu_btn">
                <div class="menu_icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                @yield('page-name')
            </div>
            <ul class="account-nav__list" id="menu">
                @if ( Auth::user()->status != 'accountant' && Auth::user()->status != 'farmer' )
                    <li><a href="{{route('home')}}">Главная</a></li>
                @endif

                <li><a href="{{route('home.cards.index')}}">Карты</a></li>

                @if ( Auth::user()->status === 'admin' )
                    <li><a href="{{route('home.users.index')}}">Пользователи</a></li>
                    <li><a href="{{route('home.teams.index')}}">Команды</a></li>
                @endif

                @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant')
                    <li><a href="{{url('home/costs')}}">Расходы</a></li>
                @endif

                @if ( Auth::user()->status !== 'farmer' )
                    <li><a href="{{url('home/statistics')}}">Статистика</a></li>
                    <li><a href="{{url('home/tokens')}}">Токены</a></li>
                @endif

                @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'mediabuyer' )
                    <li><a href="{{url('home/accounts')}}">Аккаунты</a></li>
                @endif

                @if ( Auth::user()->status === 'admin' || Auth::user()->status === 'accountant' )
                    <li><a href="{{route('home.bookkeepings.index')}}">Бухгалтерия</a></li>
                @endif

            </ul>
        </nav>

        <div class="user">
        <!-- <a href='{{ url("/home/myaccount") }}' class="user__name"> -->
            <a class="user__name" href="{{ url('/home/users') }}/{{ Auth::user()->id }}">
                <svg class="icon icon-user">
                    <use xlink:href="{{url('/img')}}/sprite.svg#user"></use>
                </svg>
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
