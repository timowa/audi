<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>АУДИ</title>
</head>
<body>
<div class="container" style="max-width: 800px; margin: auto ">
    @if($models->count()>0)
        <div >
        @foreach($models as $model)
            <div >
                <h1><a href="{{$model->url}}">{{$model->name}}</a></h1>
                <div style="display: flex; flex-wrap: wrap">
                @foreach($model->generations as $generation)
                    <div style="width: fit-content">
                        <img src="{{$generation->pictureUrl}}" alt="" width="200" height="200">
                        <p><a href="{{$generation->link}}">{{$generation->name}}</a></p>
                        <p>{{$generation->period}}</p>
                        <p>{{$generation->generation}} поколение</p>
                        <p>{{$generation->market}} </p>
                    </div>
                @endforeach
                </div>
            </div>

        @endforeach
        </div>
    @else
        <h1>Пока пусто</h1>
        <p>Выполните команды</p>
        <p>php artisan audi:models</p>
        <p>php artisan audi:generations</p>
    @endif
</div>
</body>
</html>
