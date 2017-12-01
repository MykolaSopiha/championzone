    <!-- begin header -->
    <header class="header" role="banner">
        <div class="header-inner">
            <div class="logo">
            </div>
            <nav role="navigation">
                <ul class="nav nav--main-page">
<!--                     <li><a href="{{url('iframe')}}">iframe generator</a></li>
                    <li><a href="{{url('approver')}}">approver</a></li> -->
                    @if( Auth::check() )
                        <li><a href="{{url('/home')}}">Главная</a></li>
                        <li><a href="{{url('/logout')}}">Выход</a></li>
                    @else
                        <li><a href="{{url('login')}}">Вход</a></li>
                        <li><a href="{{url('register')}}">Регистрация</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    <!-- end header -->