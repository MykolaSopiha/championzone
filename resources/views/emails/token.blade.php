<h1>Создан новый токен!</h1><br>

{{$data['action']}}: {{round($data['value']/100, 2)}} {{$data['currency']}}<br>

Пользователь: {{$user->first_name}} ({{$user->name}}) {{$user->last_name}}<br>

@if (isset($data['card2_code']) && isset($data['card2_id']))
	Карты: {{decrypt($data['card_code'])}} >> {{decrypt($data['card2_code'])}}
@else
	Карта: {{decrypt($data['card_code'])}}<br>
@endif

@if ($data['ask'])
Комментарий: {{$data['ask']}}<br>
@endif

<br>
<a href="{{url('home/tokens')}}">Токены</a>