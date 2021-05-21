<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body>


@if (isset($objects))
    <table>

        Результаты поиска:

        <tr>
            <th>Название объекта</th>
            <th>Расстояние, км</th>
            <th>Координаты (широта, долгота)</th>
        </tr>

        @foreach($objects as $object1)
            <tr>
                <td>{{$object1->name}}</td>
                <td>{{round($object1->distance/1000,3)}}</td>
                <td>{{$object1->lat}}, {{$object1->lng}}</td>
            </tr>
        @endforeach
    </table>
@endif
<br /><br />
<form method="POST" action="/">
    @csrf
    Введите Ваши координаты:
    <br/>
    Широта <input type="number" min="-89.9" max="89.9" step="0.000001" name="lat" placeholder="latitude" {{isset($request->lat) ? "value=$request->lat" : ''}}  required />
    <br/>
    Долгота <input type="number" min="-179.9" max="179.9" step="0.000001" name="lng" placeholder="longitude" {{isset($request->lng) ? "value=$request->lng" : ''}}  required />
    <br/>
    Радиус поиска объектов <input type="number" min="1" max="200" name="radiusKM" placeholder="километры" {{isset($request->radiusKM) ? "value=$request->radiusKM" : 'value=100'}}  required />
    <br/>
    Кол-во результатов <input type="number" min="1" max="200" name="numResults" {{isset($request->numResults) ? "value=$request->numResults" : 'value=10'}}  required />
    <button>Запросить</button>
</form>


</body>
</html>
