<?php
$alpha = 'CC';

$descs = array(
    'select'        => 'Select',
    'insert'        => 'Insert',
    'update'        => 'Update',
    'delete'        => 'Delete',
    'replace'       => 'Replace',
    'load'          => 'Load Data',
    'deletemulti'   => 'Delete Multi',
    'insertselect'  => 'Insert Select',
    'updatemulti'   => 'Update Multi',
    'replaceselect' => 'Replace Select',
);
    
$opt[1] = "-T 55 -l 0 --vertical-label Commands --title \"$hostname / MySQL Commands\"";
$def[1] = '';

foreach ($DS as $i) {
    $def[1] .= rrd::def("var$i", $rrdfile, $DS[$i], 'AVERAGE');

    if ($i == '1') 
        $def[1] .= rrd::area ("var$i", rrd::color($i, $alpha), rrd::cut($descs[$NAME[$i]], 15));
    else
        $def[1] .= rrd::area ("var$i", rrd::color($i, $alpha), rrd::cut($descs[$NAME[$i]], 15), 'STACK');

    $def[1] .= rrd::gprint  ("var$i", array('LAST','MAX','AVERAGE'), "%4.0lf %s\\t");
}
