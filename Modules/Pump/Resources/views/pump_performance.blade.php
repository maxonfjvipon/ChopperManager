<svg xmlns="http://www.w3.org/2000/svg" width="1000" height="500" role="img" viewBox="0 0 1000 500"
     style="pointer-events: all; width: 100%; height: 100%;">
    <g role="presentation">
        <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950" y1="450" y2="450"
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
        <text direction="inherit" dx="0" x="30" y="248.26" transform="rotate(-90,30,250)" id="chart-axis-1-axisLabel-0">
            <tspan x="30" dx="0" dy="0" text-anchor="middle"
                   style="padding: 20px; text-anchor: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                {{__('graphic.axis.head')}}
            </tspan>
        </text>
        {{--        HORIZONTAL LINES --}}
        @for($step = $y_axis_step; $step * $dy <= 400; $step += $y_axis_step)
            <g role="presentation">
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="950"
                      y1="{{450 - $dy * $step}}" y2="{{450 - $dy * $step}}"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10, 5; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"></line>
                <line vector-effect="non-scaling-stroke" role="presentation" shape-rendering="auto" x1="50" x2="45"
                      y1="{{450 - $dy * $step}}" y2="{{450 - $dy * $step}}"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1; stroke-linecap: round; stroke-linejoin: round;"></line>
                <text direction="inherit" dx="0" x="44" y="{{450 - $dy * $step}}" id="chart-axis-1-tickLabels-0">
                    <tspan x="44" dx="0" dy="0" text-anchor="end"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
    </g>
    <g clip-path="url(#victory-clip-curve)" style="height: 100%; width: 100%; user-select: none;">
        <defs>
            <clipPath id="victory-clip-curve">
                <rect vector-effect="non-scaling-stroke" x="50" y="50" width="900" height="400"></rect>
            </clipPath>
        </defs>
        <path
            d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ',' . ((450 - $dy * $point['y'])),
            $performance_line))}}
                d="M50,313.15789473684214L133.33333333333334,318.42105263157896L216.66666666666669,323.6842105263158L300,331.57894736842104L383.3333333333333,342.10526315789474L466.66666666666663,352.63157894736844L549.9999999999999,365.7894736842105L633.3333333333334,378.9473684210526L716.6666666666666,392.10526315789474L800,407.89473684210526L883.3333333333333,426.3157894736842L950,442.10526315789474"
            role="presentation" shape-rendering="auto"
            style="fill: transparent; stroke: blue; opacity: 1; stroke-width: 2;"></path>
    </g>
    <g>
        @foreach($performance_line as $point)
            <circle cx="{{50 + $dx * $point['x']}}" cy="{{450 - $dy * $point['y']}}" r="5"
                    role="presentation" shape-rendering="auto"
                    style="fill: blue; opacity: 1; stroke: transparent; stroke-width: 0;"
            />
        @endforeach
    </g>
</svg>
