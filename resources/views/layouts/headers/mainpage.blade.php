    <!-- begin header -->
    <header class="header" role="banner">
        <div class="header-inner">
            <div class="logo">
                <a href="{{url('/')}}">
                    <svg class="icon icon-world_cup"><use xlink:href="{{url('/img')}}/sprite.svg#world_cup"></use></svg>
                </a>
            </div>
            <nav role="navigation">
                <ul class="nav nav--main-page">
                    <li><a href="{{url('iframe')}}">iframe generator</a></li>
                    <li><a href="{{url('approver')}}">approver</a></li>
                    @if( Auth::check() )
                        <li><a href="{{url('/home')}}">home</a></li>
                        <li><a href="{{url('/logout')}}">logout</a></li>
                    @else
                        <li><a href="{{url('login')}}">login</a></li>
                        <li><a href="{{url('register')}}">registration</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    <!-- end header -->