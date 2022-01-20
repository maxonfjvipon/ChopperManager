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

        th {
            text-align: center;
            padding: 5px 0px;
            font-weight: bold;
            border: 1px solid black;
        }

        .bordered td {
            border: 1px solid black;
            text-align: center;
            padding: 2px 5px;
        }

        tr {
            margin-bottom: 30px;
        }

        body {
            font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        }
    </style>
</head>
<body>
<div class="page">
    <h2>{{$project->name}}</h2>
    <table class="bordered little-text" style="border-collapse: collapse; line-height: 1.1;width: 100%; ">
        <tr>
            <th>Дата создания</th>
            <th>Обозн. продукта</th>
            <th>Артикул</th>
            <th>Расход, м³/ч</th>
            <th>Напор, м</th>
            @if($request->personal_price)
                <th>Цена со скидкой, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
                <th>Итоговая цена со скидкой, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
            @endif
            @if($request->retail_price)
                <th>Розничная цена, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
                <th>Итоговая розничная цена, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
            @endif
            <th>P, кВ</th>
            <th>P итого, кВ</th>
        </tr>
        @foreach($project->selections as $selection)
            <tr>
                <td>{{$selection->created_at->format('d.m.Y')}}</td>
                <td>{{$selection->selected_pump_name}}</td>
                <td>{{$selection->pump->article_num_main}}</td>
                <td>{{$selection->flow}}</td>
                <td>{{$selection->head}}</td>
                @if($request->personal_price)
                    <td>{{$selection->discounted_price}}</td>
                    <td>{{$selection->total_discounted_price}}</td>
                @endif
                @if($request->retail_price)
                    <td>{{$selection->retail_price}}</td>
                    <td>{{$selection->total_retail_price}}</td>
                @endif
                <td>{{$selection->pump_rated_power}}</td>
                <td>{{$selection->total_rated_power}}</td>
            </tr>
        @endforeach
        @if($request->personal_price || $request->retail_price)
            <td colspan="6" style="text-align: left">
                <strong>Итого:</strong>
            </td>
            @if($request->personal_price)
                <td>{{array_sum($project->selections->map(fn($selection) => $selection->total_discounted_price)->toArray())}}</td>
            @endif
            @if($request->retail_price)
                @if($request->personal_price)
                    <td></td>
                @endif
                <td>{{array_sum($project->selections->map(fn($selection) => $selection->total_retail_price)->toArray())}}</td>
            @endif
            <td colspan="2"></td>
        @endif
    </table>
    <div style="position: fixed; bottom: 16px; width: 100%; text-align: center" >
        Created by <a href="http://pump-manager.com">pump-manager.com</a>
    </div>
</div>
<div class="page-break"></div>
@foreach($project->selections as $selection)
    @include('selection::export_selection', ['selection' => $selection, 'request' => $request])
    <div class="page-break"></div>
@endforeach
</body>
</html>
