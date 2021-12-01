<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{{$selection->selected_pump_name}}</title>
    <meta charset="utf-8">
    {{--    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>--}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/selection.css') }}"> --}}
    <style>
        .text-center {
            text-align: center;
        }

        .page {
            margin: 30px 20px
        }
        .td-top {
            vertical-align: top;
        }
        .with-padding {
            padding: 10px 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .little-text {
            font-size: 0.8em;
        }
        .with-border {
            border: 1px solid black
        }
        .tt {
            /*margin-bottom: 30px;*/
            /*border: 1px solid black*/
        }

        tr {
            margin-bottom: 30px;
        }
    </style>
</head>
<body style>
@include('selection::export_selection', ['selection' => $selection, 'request' => $request])
</body>
</html>
