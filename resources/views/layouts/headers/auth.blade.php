    <header class="header" role="banner">
        <div class="header-inner">
            <div class="logo">
                <a href="{{url('/')}}">
                    <svg class="icon icon-world_cup"><use xlink:href="{{url('/img')}}/sprite.svg#world_cup"></use></svg>
                </a>
            </div>
            <nav role="navigation">
                <ul class="nav">
                    <li><p>@yield('authQuest')</p></li>
                    <li><a class="nav__auth-link" href="@yield('authLink')">@yield('authBtn')</a></li>
                </ul>
            </nav>
        </div>
    </header>