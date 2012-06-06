<?php

$alpha = 'CC';

$colors_allowed     = '#AAAAAA' . $alpha;
$colors_used        = '#ffd660' . $alpha;
$colors_current     = '#ff7d00' . $alpha;
$colors_connected   = '#0000FF' . $alpha;
$colors_abrt_client = '#FF0000' . $alpha;
$colors_abrt_conn   = '#00FF00' . $alpha;

$ds_name[1] = 'Connections';
$opt[1] = sprintf('-T 55 -l 0 --vertical-label "Connections" --title "%s / MySQL Connections"', $hostname);
$def[1] = '';

$def[1] .= rrd::def('var1', $rrdfile, $DS[1], 'AVERAGE');
$def[1] .= rrd::area('var1', $colors_allowed, rrd::cut('Max Connections', 20));
$def[1] .= rrd::gprint('var1', array('LAST'), '%4.0lf %s');

$def[1] .= rrd::def('var2', $rrdfile, $DS[2], 'AVERAGE');
$def[1] .= rrd::area('var2', $colors_used, rrd::cut('Max Used Connections', 20));
$def[1] .= rrd::gprint('var2', array('LAST'), '%4.0lf %s');

$def[1] .= rrd::def('var3', $rrdfile, $DS[3], 'AVERAGE');
$def[1] .= rrd::line2('var3', $colors_current, rrd::cut('Current Connections', 20));
$def[1] .= rrd::gprint('var3', array('LAST', 'AVERAGE', 'MAX'), '%4.0lf %s');


$ds_name[2] = 'Connections Per Second';
$opt[2] = sprintf('-T 55 -l 0 --vertical-label "Connections/Second" --title "%s / MySQL Connections Per Second"', $hostname);
$def[2] = '';

$def[2] .= rrd::def('var1', $rrdfile, $DS[6], 'AVERAGE');
$def[2] .= rrd::line2('var1', $colors_connected, rrd::cut('Connections', 20));
$def[2] .= rrd::gprint('var1', array('LAST', 'AVERAGE', 'MAX'), '%4.0lf %s');

$def[2] .= rrd::def('var2', $rrdfile, $DS[4], 'AVERAGE');
$def[2] .= rrd::line2('var2', $colors_abrt_client, rrd::cut('Aborted Clients', 20));
$def[2] .= rrd::gprint('var2', array('LAST', 'AVERAGE', 'MAX'), '%4.0lf %s');

$def[2] .= rrd::def('var3', $rrdfile, $DS[5], 'AVERAGE');
$def[2] .= rrd::line2('var3', $colors_abrt_conn, rrd::cut('Aborted Connections', 20));
$def[2] .= rrd::gprint('var3', array('LAST', 'AVERAGE', 'MAX'), '%4.0lf %s');

