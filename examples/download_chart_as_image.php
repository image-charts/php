<?php

(new \ImageCharts())
->cht('bvg') // vertical bar chart
->chs('300x300') // 300px x 300px
->chd('a:60,40') // 2 data points: 60 and 40
->toFile('/tmp/gorgeous_chart.png'); // write the chart
