<div class="page">
    <h3>{{$selection->selected_pump_name}}</h3>
    <h5>Артикул: {{$selection->pump->article_num_main}}</h5>
    <table style="border-collapse: collapse; line-height: 1.1;height: 100%;width: 100%;">
        <tr>
            <td class="with-border little-text td-top">
                <table style="width: 300px">
                    <tr>
                        <td class="td-top" colspan="2">
                            <strong>Запрашиваемые параметры:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-top">Расход</td>
                        <td class="td-top">{{$selection->flow}} м³/ч</td>
                    </tr>
                    <tr>
                        <td class="td-top">Напор</td>
                        <td class="td-top">{{$selection->head}} м</td>
                    </tr>
                    <tr>
                        <td class="td-top">Температура воды</td>
                        <td class="td-top">{{$selection->fluid_temperature}} °C</td>
                    </tr>
                    @if($selection->pump_type === \Modules\Pump\Entities\Pump::$DOUBLE_PUMP)
                        <tr>
                            <td class="td-top">Схема работы:</td>
                            <td class="td-top">{{$selection->dp_work_scheme->name}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="td-top" colspan="2">
                            <strong>Фактические параметры:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-top">Расход</td>
                        <td class="td-top">{{round($selection->curves_data['intersection_point']['flow'],1)}} м3/ч</td>
                    </tr>
                    <tr>
                        <td class="td-top">Напор</td>
                        <td class="td-top">{{round($selection->curves_data['intersection_point']['head'],1)}} м</td>
                    </tr>
                    @if($selection->pump_type !== \Modules\Pump\Entities\Pump::$DOUBLE_PUMP)
                        <tr>
                            <td class="td-top">Количество основных насосов:</td>
                            <td class="td-top">{{$selection->pumps_count - $selection->reserve_pumps_count}}</td>
                        </tr>
                        <tr>
                            <td class="td-top">Количество резервных насосов:</td>
                            <td class="td-top">{{$selection->reserve_pumps_count}}</td>
                        </tr>
                    @endif
                </table>
            </td>
            <td class="with-border little-text td-top">
                <table>
                    <tr>
                        <td class="td-top" colspan="2">
                            <strong>Данные продукта:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-top">Масса</td>
                        <td class="td-top">{{$selection->pump->weight}} кг</td>
                    </tr>
                    <tr>
                        <td class="td-top">Мощность</td>
                        <td class="td-top">{{$selection->pump->rated_power}} кВ</td>
                    </tr>
                    <tr>
                        <td class="td-top">Ток</td>
                        <td class="td-top">{{$selection->pump->rated_current}} А</td>
                    </tr>
                    <tr>
                        <td class="td-top">Тип соединения</td>
                        <td>{{$selection->pump->connection_type->name}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Мин.тем.жидк.</td>
                        <td class="td-top">{{$selection->pump->fluid_temp_min}} °C</td>
                    </tr>
                    <tr>
                        <td class="td-top">Макс.тем.жидк.</td>
                        <td class="td-top">{{$selection->pump->fluid_temp_max}} °C</td>
                    </tr>
                    <tr>
                        <td class="td-top">Монтажная длина</td>
                        <td>{{$selection->pump->ptp_length}} мм</td>
                    </tr>
                    <tr>
                        <td class="td-top">Категория</td>
                        <td class="td-top">{{$selection->pump->series->category->name}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Встроенное регулирование</td>
                        <td class="td-top">{{$selection->pump->series->power_adjustment->name}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Эл. соединение</td>
                        <td class="td-top">{{$selection->pump->mains_connection->full_value}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">ДУ входа</td>
                        <td class="td-top">{{$selection->pump->dn_suction->value}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">ДУ выхода</td>
                        <td class="td-top">{{$selection->pump->dn_pressure->value}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Тип</td>
                        <td class="td-top">{{$selection->pump->types}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Применение</td>
                        <td class="td-top">{{$selection->pump->applications}}</td>
                    </tr>
                    <tr>
                        <td class="td-top">Описание</td>
                        <td class="td-top">{{$selection->pump->description}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        @if($request->print_pump_image && $selection->pump->image)
            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->image))
                <tr>
                    <td class="with-border td-top with-padding">
                        Фото насоса
                    </td>
                    <td class="with-border" colspan="2" style="text-align: center">
                        <img style="max-height: 300px;"
                             src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->image)}}"/>
                    </td>
                </tr>
            @endif
        @endif
    </table>
</div>
<div class="page-break"></div>
<div class="page">
    <h4>Гидравлическая характеристика</h4>
    @include('selection::selection_perf_curves', $selection->curves_data)
</div>
@if(($request->print_pump_sizes_image && $selection->pump->sizes_image) ||
($request->print_pump_electric_diagram_image && $selection->pump->electric_diagram_image) ||
($request->print_pump_cross_sectional_drawing_image && $selection->pump->cross_sectional_drawing_image))
    <div class="page-break"></div>
    <div class="page">
        <table style="border-collapse: collapse; line-height: 1.1;height: 100%;width: 100%;">
            @if($request->print_pump_sizes_image && $selection->pump->sizes_image)
                <tr>
                    <td class="with-border td-top with-padding">
                        Размеры насоса
                    </td>
                    <td class="with-border td-top with-padding">
                        @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->sizes_image))
                            <div style="text-align: center">
                                <img style="max-height: 300px;"
                                     src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->sizes_image)}}"/>
                            </div>
                        @endif
                    </td>
                </tr>
            @endif
            @if($request->print_pump_electric_diagram_image && $selection->pump->electric_diagram_image)
                <tr>
                    <td class="with-border td-top with-padding">
                        Электрическая схема
                    </td>
                    <td class="with-border td-top with-padding">
                        @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .
                           $selection->pump->electric_diagram_image))
                            <div style="text-align: center">
                                <img style="max-height: 300px;" src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()
            ->id . '/' . $selection->pump->electric_diagram_image)}}"/>
                            </div>
                        @endif
                    </td>
                </tr>
            @endif
            @if($request->print_pump_cross_sectional_drawing_image && $selection->pump->cross_sectional_drawing_image)
                <tr>
                    <td class="with-border td-top with-padding">
                        Взрыв модель
                    </td>
                    <td class="with-border td-top with-padding">
                        @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .
                    $selection->pump->cross_sectional_drawing_image))
                            <div style="text-align: center">
                                <img style="max-height: 300px;"
                                     src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pump->cross_sectional_drawing_image)}}"/>
                            </div>
                        @endif
                    </td>
                </tr>
            @endif
        </table>
    </div>
@endif
{{--@if($request->print_pump_sizes_image && $selection->pumpable->sizes_image)--}}
{{--    <h5>Размеры насоса</h5>--}}
{{--    @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pumpable->sizes_image))--}}
{{--        <div style="text-align: center">--}}
{{--            <img style="max-height: 300px;"--}}
{{--                 src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pumpable->sizes_image)}}"/>--}}
{{--        </div>--}}
{{--        @endif--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if($request->print_pump_electric_diagram_image && $selection->pumpable->electric_diagram_image)--}}
{{--        <div class="page-break"></div>--}}
{{--        <div class="page">--}}
{{--            <h5>Электрическая схема</h5>--}}
{{--            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .--}}
{{--                           $selection->pumpable->electric_diagram_image))--}}
{{--                <div style="text-align: center">--}}
{{--                    <img style="max-height: 300px;" src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()--}}
{{--            ->id . '/' . $selection->pumpable->electric_diagram_image)}}"/>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if($request->print_pump_cross_sectional_drawing_image && $selection->pumpable->cross_sectional_drawing_image)--}}
{{--        <div class="page-break"></div>--}}
{{--        <div class="page">--}}
{{--            <h5>Взрыв модель</h5>--}}
{{--            @if(\Illuminate\Support\Facades\Storage::disk('media')->exists(\Modules\AdminPanel\Entities\Tenant::current()->id . '/' .--}}
{{--                $selection->pumpable->cross_sectional_drawing_image))--}}
{{--                <div style="text-align: center">--}}
{{--                    <img style="max-height: 300px;"--}}
{{--                         src="{{storage_path('app/media/' . \Modules\AdminPanel\Entities\Tenant::current()->id . '/' . $selection->pumpable->cross_sectional_drawing_image)}}"/>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}
