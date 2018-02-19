@extends('layouts.app')


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

				<div class="form__item big-select">
					<select name="user_id" class="js-select" id="user_id">
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

                <div class="form__item{{ $errors->has('rate') ? ' form__item--error' : '' }}">
                    <label for="rate">Курс относительно USD</label>
                    <input id="rate" name="rate" class="money_input2" value="{{$account->rate}}" required>
                    @if ($errors->has('rate'))
                        <p>{{ $errors->first('rate') }}</p>
                    @endif
                </div>

		        <div class="form__item{{ $errors->has('currency') ? ' form__item--error' : '' }}  big-select">
                    <label for="acc_currency">Валюта</label>
                    <select name="currency" id="acc_currency" class="js-select">
						@foreach($currencies as $currency)
							<option value="{{$currency}}" {{($currency == $account->currency)? "selected" : ""}}>{{$currency}}</option>
						@endforeach
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