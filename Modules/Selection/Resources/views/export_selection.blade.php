<div class="page">
    <h3>{{$selection->selected_pump_name}}</h3>
    <h5>Артикул: {{$selection->pump->article_num_main}}</h5>
    <br/>
    <table class="tt" style="border-collapse: collapse;
            line-height: 1.1;
            height: 100%;
            width: 100%;">
        <tr>
            <td>
                @include('selection::selection_perf_curves', $selection->curves_data)
            </td>
            <td style="vertical-align: top">
                <table style="width: 300px">
                    <tr>
                        <td colspan="2">
                            <strong>Запрашиваемые параметры:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Расход</td>
                        <td><strong>{{$selection->flow}} м3/ч</strong></td>
                    </tr>
                    <tr>
                        <td>Напор</td>
                        <td><strong>{{$selection->head}} м</strong></td>
                    </tr>
                    <tr>
                        <td>Температура воды</td>
                        <td><strong>{{$selection->fluid_temperature}} C</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <strong>Фактические параметры:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Расход</td>
                        <td><strong>{{round($selection->curves_data['intersection_point']['flow'],1)}} м3/ч</strong></td>
                    </tr>
                    <tr>
                        <td>Напор</td>
                        <td><strong>{{round($selection->curves_data['intersection_point']['head'],1)}} м</strong></td>
                    </tr>
                    <tr>
                        <td>Количество основных насосов:</td>
                        <td><strong>{{$selection->pumps_count - $selection->reserve_pumps_count}}</strong></td>
                    </tr>
                    <tr>
                        <td>Количество резервных насосов:</td>
                        <td><strong>{{$selection->reserve_pumps_count}}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        @if($request->print_pump_image && $selection->pump->image)
            {{--            @php--}}
            {{--            @endphp--}}
            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->image))
                <tr>
                    <td colspan="2" style="text-align: center">
                        <img style="max-height: 300px;" src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()
            ->id . '/' . $selection->pump->image)}}"/>
                    </td>
                </tr>
            @endif
        @endif
    </table>
</div>
@if($request->print_pump_sizes_image && $selection->pump->sizes_image)
<div class="page-break"></div>
    <div class="page">
        <table class="tt" style="border-collapse: collapse;
            line-height: 1.1;
            height: 100%;
            width: 100%;">
            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->sizes_image))
                <tr>
                    <td style="text-align: center">
                        <img style="max-height: 300px;" src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()
            ->id . '/' . $selection->pump->sizes_image)}}"/>
                    </td>
                </tr>
            @endif
            @endif
            @if($request->print_pump_electric_diagram_image && $selection->pump->electric_diagram_image)
                @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .
                $selection->pump->electric_diagram_image))
                    <tr>
                        <td style="text-align: center">
                            <img style="max-height: 300px;" src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()
            ->id . '/' . $selection->pump->electric_diagram_image)}}"/>
                        </td>
                    </tr>
                @endif
        </table>
    </div>
@endif
@if($request->print_pump_cross_sectional_drawing_image && $selection->pump->cross_sectional_drawing_image)
<div class="page-break"></div>
    <div class="page">
        <table class="tt" style="border-collapse: collapse;
            line-height: 1.1;
            height: 100%;
            width: 100%;">
            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .
            $selection->pump->cross_sectional_drawing_image))
                <tr>
                    <td style="text-align: center">
                        <img style="max-height: 300px;"
                             src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->cross_sectional_drawing_image)}}"/>
                    </td>
                </tr>
            @endif
        </table>
    </div>
@endif
