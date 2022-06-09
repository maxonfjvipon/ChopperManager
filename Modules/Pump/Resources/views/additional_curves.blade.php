<svg xmlns="http://www.w3.org/2000/svg" width="1000" height="500" role="img" viewBox="0 0 1000 500"
     style="pointer-events: all; width: 100%; height: 100%;">
    <g role="presentation">
        {{--        NPSH HORIZONTAL AXIS --}}
        <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950" y1="450" y2="450"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;"></line>
        {{--        SHAFT POWER HORIZONTAL AXIS --}}
        <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950" y1="317" y2="317"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;"></line>
        {{--        HYDRAULIC EFFICIENCY HORIZONTAL AXIS --}}
        <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950" y1="184" y2="184"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;"></line>
        <text direction="inherit" dx="0" x="500" y="480.26" id="chart-axis-0-axisLabel-0">
            <tspan x="500" dx="0" dy="0" text-anchor="middle"
                   style="padding: 20px; text-anchor: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.axis.flow')}}
            </tspan>
        </text>
        {{--        VERTICAL LINES --}}
        @for($step = $x_axis_step; $step * $dx <= 900; $step += $x_axis_step)
            <g role="presentation">
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="{{50 + $step * $dx}}"
                      x2="{{50 + $step * $dx}}" y1="450" y2="50"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10, 5; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"></line>
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="{{50 + $step * $dx}}"
                      x2="{{50 + $step * $dx}}" y1="450" y2="455"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1; stroke-linecap: round; stroke-linejoin: round;"></line>
                <text direction="inherit" dx="0" x="{{50 + $step * $dx}}" y="466.26" id="{{"chart-axis-0-tickLabels-" . $step}}">
                    <tspan x="{{50 + $step * $dx}}" dx="0" dy="0" text-anchor="middle"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
    </g>
    <g role="presentation">
        <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="50" y1="50" y2="450"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;"></line>
        {{--           HORIZONTAL LINES --}}
        {{--           HE PERFORMANCE --}}
        <text direction="inherit" x="55" y="65" id="chart-axis-0-axisLabel-10">
            <tspan dx="0" dy="0"
                   style="padding: 20px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.curves.he')}}
            </tspan>
        </text>
        @for($step = $HE_performance['y_axis_step']; $step * $HE_performance['dy'] <= 133; $step += $HE_performance['y_axis_step'])
            <g role="presentation">
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950"
                      y1="{{184 - $HE_performance['dy'] * $step}}" y2="{{184 - $HE_performance['dy'] * $step}}"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10, 5; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"></line>
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="45"
                      y1="{{184 - $HE_performance['dy'] * $step}}" y2="{{184 - $HE_performance['dy'] * $step}}"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1; stroke-linecap: round; stroke-linejoin: round;"></line>
                <text direction="inherit" dx="0" x="44" y="{{184 - $HE_performance['dy'] * $step}}"
                      id="chart-axis-1-tickLabels-0">
                    <tspan x="44" dx="0" dy="0" text-anchor="end"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                        {{$step * 100}}
                    </tspan>
                </text>
            </g>
        @endfor
        {{--           ShP PERFORMANCE --}}
        <text direction="inherit" x="55" y="198" id="chart-axis-0-axisLabel-20">
            <tspan dx="0" dy="0"
                   style="padding: 20px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.curves.shp')}}
            </tspan>
        </text>
        @for($step = $ShP_performance['y_axis_step']; $step * $ShP_performance['dy'] <= 133; $step += $ShP_performance['y_axis_step'])
            <g role="presentation">
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950"
                      y1="{{317 - $ShP_performance['dy'] * $step}}" y2="{{317 - $ShP_performance['dy'] * $step}}"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10, 5; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"></line>
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="45"
                      y1="{{317 - $ShP_performance['dy'] * $step}}" y2="{{317 - $ShP_performance['dy'] * $step}}"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1; stroke-linecap: round; stroke-linejoin: round;"></line>
                <text direction="inherit" dx="0" x="44" y="{{317 - $ShP_performance['dy'] * $step}}"
                      id="chart-axis-1-tickLabels-0">
                    <tspan x="44" dx="0" dy="0" text-anchor="end"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
        {{--           NPSH PERFORMANCE --}}
        <text direction="inherit" x="55" y="332" id="chart-axis-0-axisLabel-30">
            <tspan dx="0" dy="0"
                   style="padding: 20px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.curves.npsh')}}
            </tspan>
        </text>
        @for($step = $NPSH_performance['y_axis_step']; $step * $NPSH_performance['dy'] <= 133; $step += $NPSH_performance['y_axis_step'])
            <g role="presentation">
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950"
                      y1="{{450 - $NPSH_performance['dy'] * $step}}" y2="{{450 - $NPSH_performance['dy'] * $step}}"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10, 5; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"></line>
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="45"
                      y1="{{450 - $NPSH_performance['dy'] * $step}}" y2="{{450 - $NPSH_performance['dy'] * $step}}"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1; stroke-linecap: round; stroke-linejoin: round;"></line>
                <text direction="inherit" dx="0" x="44" y="{{450 - $NPSH_performance['dy'] * $step}}"
                      id="chart-axis-1-tickLabels-0">
                    <tspan x="44" dx="0" dy="0" text-anchor="end"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
    </g>
    @foreach($HE_performance['lines'] as $line)
        <g clip-path="url(#victory-clip-he-curve)" style="height: 100%; width: 100%; user-select: none;">
            <defs>
                <clipPath id="victory-clip-he-curve">
                    <rect vector-effect="non-scaling-stroke" x="50" y="50" width="900" height="133"></rect>
                </clipPath>
            </defs>
            <path
                d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ',' . ((184 - $HE_performance['dy'] *
            $point['y'])), $line))}}
                    role="presentation" shape-rendering="auto"
                style="fill: transparent; stroke: blue; opacity: 1; stroke-width: 2;"></path>
        </g>
    @endforeach
    @foreach($ShP_performance['lines'] as $line)
        <g clip-path="url(#victory-clip-shp-curve)" style="height: 100%; width: 100%; user-select: none;">
            <defs>
                <clipPath id="victory-clip-shp-curve">
                    <rect vector-effect="non-scaling-stroke" x="50" y="183" width="900" height="133"></rect>
                </clipPath>
            </defs>
            <path
                d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ','
                . ((317 - $ShP_performance['dy'] * $point['y'])), $line))}}
                    role="presentation" shape-rendering="auto"
                style="fill: transparent; stroke: blue; opacity: 1; stroke-width: 2;"></path>
        </g>
    @endforeach
    @foreach($NPSH_performance['lines'] as $line)
        <g clip-path="url(#victory-clip-npsh-curve)" style="height: 100%; width: 100%; user-select: none;">
            <defs>
                <clipPath id="victory-clip-npsh-curve">
                    <rect vector-effect="non-scaling-stroke" x="50" y="316" width="900" height="133"></rect>
                </clipPath>
            </defs>
            <path
                d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ','
                . ((450 - $NPSH_performance['dy'] * $point['y'])), $line))}}
                    role="presentation" shape-rendering="auto"
                style="fill: transparent; stroke: blue; opacity: 1; stroke-width: 2;"></path>
        </g>
    @endforeach
</svg>
