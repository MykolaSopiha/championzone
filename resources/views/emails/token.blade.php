<h1>Создан новый токен!</h1><br>

{{$request['action']}}: {{$request['value']}} {{$token_card->currency}}<br>

Пользователь: {{$user->first_name}} ({{$user->name}}) {{$user->last_name}}<br>
Карта: {{decrypt($token_card->code)}}<br>
@if ($request['ask'])
Комментарий: {{$request['ask']}}<br>
@endif

<br>
<a href="{{url('home/tokens')}}">Токены</a>