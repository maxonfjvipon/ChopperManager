{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <meta charset="utf-8"/>--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>--}}
{{--    <link rel="stylesheet"--}}
{{--          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"/>--}}
{{--</head>--}}
{{--<body style="margin: 0">--}}
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="430" role="img" viewBox="0 0 600 430" style="pointer-events: all; width: 100%; height: 100%;">
    {{--    X ASIX AND VERTICAL LINES --}}
    <g role="presentation">
        {{--        X AXIS --}}
        <line vector-effect="non-scaling-stroke"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round;"
              role="presentation" shape-rendering="auto" x1="50" x2="550" y1="380" y2="380"></line>
        {{--        VOLUME FLOW TEXT --}} {{-- TODO: multilocale --}}
        <text direction="inherit" dx="0" x="300" y="410.26" id="chart-axis-0-axisLabel-0">
            <tspan x="300" dx="0" dy="0" text-anchor="middle"
                   style="padding: 20px; text-anchor: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif;
                font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0;">
                Volume flow, m³/h
            </tspan>
        </text>
        {{--        VERTICAL LINES --}}
        @for($step = $x_axis_step; $step * $dx <= 500; $step += $x_axis_step)
            <g role="presentation">
                <line vector-effect="non-scaling-stroke"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10px, 5px; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"
                      role="presentation" shape-rendering="auto" x1="{{50 + $step * $dx}}" x2="{{50 + $step * $dx}}" y1="380"
                      y2="50"></line>
                <line vector-effect="non-scaling-stroke"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1px; stroke-linecap: round; stroke-linejoin: round;"
                      role="presentation" shape-rendering="auto" x1="{{50 + $step * $dx}}" x2="{{50 + $step * $dx}}" y1="380"
                      y2="385"></line>
                <text direction="inherit" dx="0" x="{{50 + $step * $dx}}" y="396.26" id="chart-axis-0-tickLabels- . {{$step}}">
                    <tspan x="{{50 + $step * $dx}}" dx="0" dy="0" text-anchor="middle"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
    </g>
    {{--    Y AXIS AND HORIZONTAL LINES --}}
    <g role="presentation">
        {{--       Y AXIS --}}
        <line vector-effect="non-scaling-stroke"
              style="stroke: rgb(144, 164, 174); fill: transparent; stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round;"
              role="presentation" shape-rendering="auto" x1="50" x2="50" y1="50" y2="380"></line>
        {{--        DELIVERY HEAD TEXT --}} {{-- TODO: add multilocales --}}
        <text direction="inherit" dx="0" x="30" y="213.26" transform="rotate(-90,30,215)" id="chart-axis-1-axisLabel-0">
            <tspan x="30" dx="0" dy="0" text-anchor="middle"
                   style="padding: 20px; text-anchor: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                Delivery head, m
            </tspan>
        </text>
        {{--        HORIZONTAL LINES --}}
        @for($step = $y_axis_step; $step * $dy <= 330; $step += $y_axis_step)
            <g role="presentation">
                <line vector-effect="non-scaling-stroke"
                      style="stroke: rgb(236, 239, 241); fill: none; stroke-dasharray: 10px, 5px; stroke-linecap: round; stroke-linejoin: round; pointer-events: painted;"
                      role="presentation" shape-rendering="auto" x1="50" x2="550" y1="{{380 - $dy * $step}}" y2="{{380 - $dy * $step}}"></line>
                <line vector-effect="non-scaling-stroke"
                      style="stroke: rgb(144, 164, 174); fill: transparent; size: 5px; stroke-width: 1px; stroke-linecap: round; stroke-linejoin: round;"
                      role="presentation" shape-rendering="auto" x1="50" x2="45" y1="{{380 - $dy * $step}}" y2="{{380 - $dy * $step}}"></line>
                <text direction="inherit" dx="0" x="44" y="{{380 - $dy * $step}}" id="chart-axis-1-tickLabels-0">
                    <tspan x="44" dx="0" dy="0" text-anchor="end"
                           style="padding: 1px; font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                        {{$step}}
                    </tspan>
                </text>
            </g>
        @endfor
    </g>
    {{--    PERFORMANCE CURVES --}}
    @for($i = 0; $i < count($performance_lines); $i++)
        <g style="height: 100%; width: 100%;" clip-path={{"url(#victory-clip-" . $i . ")"}}>
            <defs>
                <clipPath id={{"url(#victory-clip-" . $i . ")"}}>
                    <rect vector-effect="non-scaling-stroke" x="50" y="50" width="500" height="330"></rect>
                </clipPath>
            </defs>
            <path
                d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ',' . ((380 - $dy * $point['y'])), $performance_lines[$i]))}}
{{--                    d="M73.29749103942652,108.1362958609156L82.25806451612904,109.29810656236467L91.21863799283153,111.6217279652628L100.17921146953405,113.94534936816098L109.13978494623657,116.84987612178367L118.10035842293908,119.75440287540637L127.06093189964159,123.82074033047815L136.02150537634412,127.88707778554993L144.98207885304663,133.11522594207077L153.94265232974914,138.3433740985916L162.90322580645164,143.57152225511248L171.86379928315415,149.96148111308239L180.82437275985666,156.9323453217769L189.78494623655916,163.90320953047132L198.74551971326167,171.45497908989034L207.70609318996418,179.58765400003392L216.66666666666669,187.72032891017744L225.62724014336922,197.0148145217701L234.58781362007173,206.30930013336274L243.5483870967742,216.18469109567988L252.5089605734767,226.64098740872163L261.4695340501792,237.67818907248784L270.4301075268817,249.29629608697863L279.39068100358423,260.9144031014694L288.35125448028674,273.69432081740933L297.31182795698925,286.47423853334914L299.10394265232975,287.63604923479824"--}}
                style="fill: transparent; stroke: black; opacity: 1; stroke-width: 2px;" role="presentation"
                shape-rendering="auto"></path>
        </g>
    @endfor
    {{--    SYSTEM PERFORMACE CURVE --}}
    <g style="height: 100%; width: 100%;" clip-path="url(#victory-clip-44)">
        <defs>
            <clipPath id="victory-clip-44">
                <rect vector-effect="non-scaling-stroke" x="50" y="50" width="500" height="330"></rect>
            </clipPath>
        </defs>
        <path
            d={{"M" . join("L", array_map(fn($point) => (50 + $dx * $point['x']) . ',' . ((380 - $dy * $point['y'])),
            $system_performance))}}
{{--            d="M50,380L58.96057347670251,379.41909464927545L67.92114695340501,377.67637859710186L76.88172043010752,375.35275719420366L85.84229390681004,372.44823044058097L94.80286738351255,368.38189298550924L103.76344086021506,363.1537448289884L112.72401433691758,357.3446913217429L121.68458781362007,350.95473246377304L130.64516129032256,343.402962904354L139.6057347670251,334.68938264348594L148.5663082437276,325.3948970318933L157.5268817204301,314.9386007188516L166.48745519713262,303.9013990550854L175.44802867383515,292.2832920405946L184.40860215053766,279.5033743246547L193.36917562724017,265.5616459072657L202.32974910394265,251.03901213915228L211.29032258064515,235.93547302031425L220.25089605734766,219.67012320002715L229.2114695340502,202.24296267829092L238.1720430107527,184.2348968058302"--}}
            style="fill: transparent; stroke: red; opacity: 1; stroke-width: 2px;" role="presentation"
            shape-rendering="auto"></path>
    </g>
    {{--    INERSECTION POINT AND WORKING POINT DOTS --}}
    <g>
        <path d="{{"M " . (50 + $dx * $intersection_point['flow']) . ', ' . (380 - $dy * $intersection_point['head']) .
      "\n m -6, 0
      a 6, 6 0 1,0 12,0
      a 6, 6 0 1,0 -12,0"}}"
            style="fill: green; opacity: 1; stroke: transparent; stroke-width: 0px;" role="presentation"
              shape-rendering="auto"></path>
    </g>
    <g>
        <path d="{{"M " . 50 + $dx * $working_point['flow'] . ', ' . 380 - $dy * $working_point['head'] .
      "\nm -6, 0
      a 6, 6 0 1,0 12,0
      a 6, 6 0 1,0 -12,0"}}"
            style="fill: red; opacity: 1; stroke: transparent; stroke-width: 0px;" role="presentation"
              shape-rendering="auto"></path>
    </g>
    {{-- LEGEND --}}
    <g>
        <rect vector-effect="non-scaling-stroke" style="fill: none;" role="presentation" shape-rendering="auto" x="450" y="20"
              width="130.28750000000002" height="110.94"></rect>
        <path d="M 462, 32
      m -4.8, 0
      a 4.8, 4.8 0 1,0 9.6,0
      a 4.8, 4.8 0 1,0 -9.6,0" style="fill: red;" role="presentation" shape-rendering="auto"></path>
        <path d="M 462, 87.47
      m -4.8, 0
      a 4.8, 4.8 0 1,0 9.6,0
      a 4.8, 4.8 0 1,0 -9.6,0" style="fill: green;" role="presentation" shape-rendering="auto"></path>
        <text direction="inherit" dx="0" x="476.4" y="23.39" id="chart-legend-7-labels-0">
            <tspan x="476.4" dx="0" dy="0" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                Working point
            </tspan>
            <tspan x="476.4" dx="0" dy="12" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                {{"Flow: " . $working_point['flow']}}
            </tspan>
            <tspan x="476.4" dx="0" dy="12" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                {{"Head: " . $working_point['head']}}
            </tspan>
        </text>
        <text direction="inherit" dx="0" x="476.4" y="78.86" id="chart-legend-7-labels-1">
            <tspan x="476.4" dx="0" dy="0" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                Intersection point
            </tspan>
            <tspan x="476.4" dx="0" dy="12" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                {{"Flow: " . round( $intersection_point['flow'], 1)}}
            </tspan>
            <tspan x="476.4" dx="0" dy="12" text-anchor="start"
                   style="font-family: &quot;Helvetica Neue&quot;, Helvetica, sans-serif; font-size: 12px; letter-spacing: normal; padding: 8px; fill: rgb(69, 90, 100); stroke: transparent; stroke-width: 0px;">
                {{"Head: " . round($intersection_point['head'], 1)}}
            </tspan>
        </text>
    </g>
</svg>
{{--</body>--}}
{{--</html>--}}
