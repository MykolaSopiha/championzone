<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить кошельки</title>
</head>
<body>

    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">

            <!-- begin items -->
            <div class="items">

                <!-- begin items__add -->
                <div>
                    <form class="form" method="POST" action="{{ url('/wallets') }}">

                        {{ csrf_field() }}

                        <textarea name="text" id="" cols="80" rows="10"></textarea><br>
                        <input type="submit">

                    </form>
                </div>
                <!-- end items__add -->

            </div>
            <!-- end items -->

        </div>
    </main>
    <!-- end main -->
    
</body>
</html>
