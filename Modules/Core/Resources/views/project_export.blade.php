<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$project->name}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .text-center {
            text-align: center;
        }

        .page {
            margin: 60px 30px
        }

        .page-break {
            page-break-after: always;
        }

        .tt td {
            margin-bottom: 30px;
            border: 1px solid black
        }

        tr {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
@foreach($project->selections as $selection)
    @include('selection::export_selection', ['selection' => $selection, 'request' => $request])
    <div class="page-break"></div>
@endforeach
</body>
</html>
