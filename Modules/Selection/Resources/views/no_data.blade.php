<svg xmlns="http://www.w3.org/2000/svg" width="700" height="500" role="img" viewBox="0 0 700 500"
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
        <text direction="inherit" dx="0" x="370" y="480.26" id="chart-axis-0-axisLabel-0">
            <tspan x="370" dx="0" dy="0" text-anchor="middle"
                   style="padding: 20px; text-anchor: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.axis.flow')}}
            </tspan>
        </text>
        {{--        VERTICAL LINES --}}
        @for($step = $x_axis_step; $step * $dx <= 700; $step += $x_axis_step)
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
        {{--           ShP PERFORMANCE --}}
        <text direction="inherit" x="55" y="198" id="chart-axis-0-axisLabel-20">
            <tspan dx="0" dy="0"
                   style="padding: 20px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.curves.shp')}}
            </tspan>
        </text>
        {{--           NPSH PERFORMANCE --}}
        <text direction="inherit" x="55" y="332" id="chart-axis-0-axisLabel-30">
            <tspan dx="0" dy="0"
                   style="padding: 20px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.curves.npsh')}}
            </tspan>
        </text>
    </g>
</svg>
