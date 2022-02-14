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
            margin: 20px 20px
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

        .price {
            min-width: 85px
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
                <th class="price">Цена со скидкой, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
                <th class="price">Итоговая цена со скидкой, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
            @endif
            @if($request->retail_price)
                <th class="price">Розничная цена, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
                <th class="price">Итоговая розничная цена, {{\Illuminate\Support\Facades\Auth::user()->currency->symbol}}</th>
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
                    <td class="price">{{number_format($selection->discounted_price, 1, ",", " ")}}</td>
                    <td class="price">{{number_format($selection->total_discounted_price, 1, ",", " ")}}</td>
                @endif
                @if($request->retail_price)
                    <td class="price">{{number_format($selection->retail_price, 1, ",", " ")}}</td>
                    <td class="price">{{number_format($selection->total_retail_price, 1, ",", " ")}}</td>
                @endif
                <td>{{str_replace(".", ",", $selection->pump_rated_power)}}</td>
                <td>{{str_replace(".", ",", $selection->total_rated_power)}}</td>
            </tr>
        @endforeach
        @if($request->personal_price || $request->retail_price)
            <td colspan="6" style="text-align: left">
                <strong>Итого:</strong>
            </td>
            @if($request->personal_price)
                <td class="price">{{number_format(array_sum($project->selections->map(fn($selection) => $selection->total_discounted_price)
                ->toArray()), 1, ",", " ")}}</td>
            @endif
            @if($request->retail_price)
                @if($request->personal_price)
                    <td></td>
                @endif
                <td class="price">{{number_format(array_sum($project->selections->map(fn($selection) => $selection->total_retail_price)
                ->toArray()), 1, ",", " ")}}</td>
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
