@extends('layouts.app')

@section('content')

	<!-- begin header -->
	@section('page-name') Карта id: {{ $card->id }} @endsection
	@include('layouts.headers.home')
	<!-- end header -->



	<!-- begin main -->
	<main class="main">
		<div class="main-inner">

			<form class="form" method="POST" action="{{ url('/home/cards/') }}/{{ $card->id }}">

				{{ csrf_field() }}

				<header class="form__header">
					<h2>Пользователь карты</h2>
				</header>

				<input type="hidden" name="_method" value="put">

				<div class="form__item">
					<select name="user" id="user">
						@foreach ($users as $user)
							@if ( $user->id === $card->user_id )
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

				<div class="form__item">
					<button type="submit">
						<i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> Сохранить
					</button>
				</div>
                
                <div class="form__item" align="center">
                    <a href="{{url('home/cards')}}">Назад</a>
                </div>

			</form>

		</div>
	</main>
	<!-- end main -->

@endsection