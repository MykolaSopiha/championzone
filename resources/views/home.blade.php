@extends('layouts.app')

@section('content')

    <!-- begin header -->
    @section('page-name') Главная @endsection
    @include('layouts.headers.home')
    <!-- end header -->



    <!-- begin main -->
    <main class="main" role="main">
        <div class="main-inner">
            
            <!-- begin benefits -->
            <div class="benefits">

                <h1 class="benefits__header">Мотивационная система:</h1>
                
                <div class="benefits__tables">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Профит за месяц</td>
                                <td>Процент с профита</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>до 2000$</td>
                                <td>5%</td>
                            </tr>
                            <tr>
                                <td>от 2000$ до 10000$</td>
                                <td>10%</td>
                            </tr>
                            <tr>
                                <td>от 10000$ до 15000$</td>
                                <td>12%</td>
                            </tr>
                            <tr>
                                <td>от 15000$ до 20000$</td>
                                <td>15%</td>
                            </tr>
                            <tr>
                                <td>от 20000$ до 25000$</td>
                                <td>17%</td>
                            </tr>
                            <tr>
                                <td>от 25000$ до 30000$</td>
                                <td>19%</td>
                            </tr>
                            <tr>
                                <td>от 30000$ до 40000$</td>
                                <td>21%</td>
                            </tr>
                            <tr>
                                <td>от 40000$ до 50000$</td>
                                <td>23%</td>
                            </tr>
                            <tr>
                                <td>от 50000$ до 70000$</td>
                                <td>25%</td>
                            </tr>
                            <tr>
                                <td>больше 70000$</td>
                                <td>30%</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                            <tr>
                                <td>Профит за квартал</td>
                                <td>Бонус</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>30000$</td>
                                <td>путевка на 2их в Буковель/Одессу/Львов на выбор</td>
                            </tr>
                            <tr>
                                <td>50000$</td>
                                <td>путевка на 2их Турция|Египет|Тунис на выбор</td>
                            </tr>
                            <tr>
                                <td>70000$</td>
                                <td>путевка на 2их Шри Ланка</td>
                            </tr>
                            <tr>
                                <td>100000$</td>
                                <td>Macbook Pro</td>
                            </tr>
                            <tr>
                                <td>150000$</td>
                                <td>путевка на 2их в Маями</td>
                            </tr>
                            <tr>
                                <td>200000$</td>
                                <td>мотоцикл класса Geon Benelli на выбор</td>
                            </tr>
                            <tr>
                                <td>350000$</td>
                                <td>автомобиль класса Mazda 3 на выбор</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end benefits -->

        </div>
    </main>
    <!-- end main -->

@endsection