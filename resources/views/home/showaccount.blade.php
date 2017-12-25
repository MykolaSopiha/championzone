@extends('layouts.app')


@section('styles')
<style>
    .chosen-container {
        font-size: 18px !important
    }
    .chosen-single {
        height: 70px !important;
        line-height: 70px !important;
        background: none !important;
        text-align: center
    }
</style>
@endsection


@section('content')

	<!-- begin header -->
	@section('page-name') Аккаунт id: {{ $account->id }} @endsection
	@include('layouts.headers.home')
	<!-- end header -->



	<!-- begin main -->
	<main class="main">
		<div class="main-inner">

			<form class="form" method="POST" action="{{ url('/home/accounts/') }}/{{ $account->id }}">

				{{ csrf_field() }}

				<input type="hidden" name="_method" value="put" />

				<header class="form__header">
					<h2>Пользователь аккаунта</h2>
				</header>

				<input type="hidden" name="_method" value="put">

                <div class="form__item{{ $errors->has('info') ? ' form__item--error' : '' }}">
                    <label for="info">Информация</label>
                    <textarea id="info" cols="80" rows="10" name="info" placeholder="Описание аккаунта">{{$account->info}}</textarea>
                    @if ($errors->has('info'))
                        <p>{{ $errors->first('info') }}</p>
                    @endif
                </div>

				<div class="form__item">
					<select name="user_id" class="chosen-js-select" id="user_id">
						@foreach ($users as $user)
							@if ( $user->id === $account->user_id )
								<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
							@else
								<option value="{{ $user->id }}">{{ $user->name }}</option>
							@endif
						@endforeach
					</select>
					@if ($errors->has('date'))
					<p>{{ $errors->first('user') }}</p>
					@endif
				</div>

                <div class="form__item{{ $errors->has('value') ? ' form__item--error' : '' }}">
                    <label for="value">Стоимость</label>
                    <input id="value" class="money_input" type="text" step="0.01" value="{{$account->price/100}}" name="price">
                    @if ($errors->has('value'))
                        <p>{{ $errors->first('value') }}</p>
                    @endif
                </div>

		        <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}">
                    <label for="acc_currency">Валюта</label>
                    <select name="currency" id="acc_currency" class="chosen-js-select">
						@if ( $account->currency == 'USD')
                        	<option value="USD" selected>USD</option>
						@else
							<option value="USD">USD</option>
						@endif
						@if ( $account->currency == 'EUR')
                        	<option value="EUR" selected>EUR</option>
						@else
							<option value="EUR">EUR</option>
						@endif
                        @if ( $account->currency == 'RUB')
                        	<option value="RUB" selected>RUB</option>
						@else
							<option value="RUB">RUB</option>
						@endif
						@if ( $account->currency == 'UAH')
                        	<option value="UAH" selected>UAH</option>
						@else
							<option value="UAH">UAH</option>
						@endif
                    </select>
                    @if ($errors->has('currency'))
                        <p>{{ $errors->first('currency') }}</p>
                    @endif
                </div>

				<div class="form__item">
					<button type="submit">
						<i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> Сохранить
					</button>
				</div>
                
                <div class="form__item" align="center">
                    <a href="{{url('home/accounts')}}">Назад</a>
                </div>

			</form>

		</div>
	</main>
	<!-- end main -->

@endsection