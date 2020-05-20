<?php
$chart_url = (new ImageCharts())
                ->cht('bvg') // vertical bar chart
                ->chs('300x300') // 300px x 300px
                ->chd('a:60,40') // 2 data points: 60 and 40
                ->toBinary(); // download chart image

echo $chart_url; // Image content
