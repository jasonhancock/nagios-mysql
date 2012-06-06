<?php
$alpha = 'CC';

$colors = array(
    '#ff0000' . $alpha,
    '#ff7d00' . $alpha,
    '#fff200' . $alpha,
    '#00cf00' . $alpha,
    '#7cb3f1' . $alpha,
);

$opt[1] = "-T 55 -l 0 --vertical-label \"Queries/Second\" --title \"$hostname / Select Types\"";
$def[1] = '';

foreach ($DS as $i) {
    $def[1] .= rrd::def("var$i", $rrdfile, $DS[$i], 'AVERAGE');

    if ($i == '1')
        $def[1] .= rrd::area ("var$i", $colors[$i - 1], prep_name($NAME[$i]));
    else
        $def[1] .= rrd::area ("var$i", $colors[$i - 1], prep_name($NAME[$i]), 'STACK');

    $def[1] .= rrd::gprint  ("var$i", array('LAST','MAX','AVERAGE'), "%4.0lf %s\\t");
}

/*
* Replaces underescores with spaces, up caseses words, then returns the results
* of rrd::cut
*/
function prep_name($name) {
    return rrd::cut(ucwords(str_replace('_', ' ', $name)), 15);
}
