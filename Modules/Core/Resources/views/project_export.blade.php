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
                    <td>{{number_format($selection->discounted_price, 1)}}</td>
                    <td>{{number_format($selection->total_discounted_price, 1)}}</td>
                @endif
                @if($request->retail_price)
                    <td>{{number_format($selection->retail_price, 1)}}</td>
                    <td>{{number_format($selection->total_retail_price, 1)}}</td>
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
                <td>{{number_format(array_sum($project->selections->map(fn($selection) => $selection->total_discounted_price)->toArray()), 1)}}</td>
            @endif
            @if($request->retail_price)
                @if($request->personal_price)
                    <td></td>
                @endif
                <td>{{number_format(array_sum($project->selections->map(fn($selection) => $selection->total_retail_price)->toArray()), 1)}}</td>
            @endif
            <td colspan="2"></td>
        @endif
    </table>
    @include("selection::created_by")
</div>
<div class="page-break"></div>
@foreach($project->selections as $selection)
    @include('selection::export_selection', ['selection' => $selection, 'request' => $request])
    <div class="page-break"></div>
@endforeach
</body>
</html>
