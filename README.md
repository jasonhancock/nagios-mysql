nagios-mysql
===============

A collection of Nagios scripts/plugins for monitoring MySQL. Heavily inspired
by the Cacti graphs in the Percona Monitoring Plugins.

This is a work in progress. New plugins/graphs will be added as I find time
to incorporate them.

LICENSE: MIT
------------
Copyright (c) 2012 Jason Hancock <jsnbyh@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

PLUGINS:
--------

**check_mysql_commands:**

This plugin reports on the number of queries being performed per second.

![check_mysql_commands](https://github.com/jasonhancock/nagios-mysql/raw/master/example-images/check_mysql_commands.png)

**check_mysql_connections:**

This plugin reports on connection statistics. It is broken out into two graphs.
The first graph shows you how many connections the server is configured to accept,
the maximum number of connections you have ever used, and the current number of
connections (TODO: replace this graph with a better one).

![check_mysql_connections](https://github.com/jasonhancock/nagios-mysql/raw/master/example-images/check_mysql_connections.png)

The second graph shows you per-second connection statistics.

![check_mysql_connections_persecond](https://github.com/jasonhancock/nagios-mysql/raw/master/example-images/check_mysql_connections_persecond.png)

**check_mysql_selects:**

This plugin breaks down the select queries being run by type. Ideally, you would
not see any of the Full Join types of queries.

![check_mysql_selects](https://github.com/jasonhancock/nagios-mysql/raw/master/example-images/check_mysql_selects.png)

INSTALLATION:
-------------

You can run these plugins either directly from the Nagios server or via NRPE.
If you are running on the Nagios server, install the plugins on that machine.
If you are running via NRPE, the plugins need to be installed locally on the
MySQL server. Either way, Copy the plugins out of the plugins directory and
put them into Nagios' plugins directory (this is usually /usr/lib64/nagios/plugins
on a 64-bit RHEL/CentOS box). 

Copy the pnp4nagios templates out of the pnp4nagios/templates directory and put
them into pnp4nagios' templates directory on the Nagios server (On EL6 using the
pnp4nagios package from EPEL, this directory is 
/usr/share/nagios/html/pnp4nagios/templates).

Copy the pnp4nagios check commands configs out of the pnp4nagios/check\_commands
directory and put them in pnp4nagios' check\_commands directory on the Nagios
server. Using the same package from EPEL as above, this is
/etc/pnp4nagios/check\_commands. Do this BEFORE configuring the service checks
in Nagios otherwise the RRD's will get created with the wrong data types (To fix
this, just delete the .rrd files and start over).


MYSQL USER CONFIGURATION:
-------------------------

All statistics are obtained by running either "SHOW GLOBAL STATUS" or 
"SHOW GLOBAL VARIABLES", thus the MySQL user you will run the plugins
with does not need any privileges at all. The following grant would
be sufficient to set up a user named "testuser" with this minimum level
of access:

```
GRANT USAGE ON *.* TO testuser@'localhost' IDENTIFIED BY 'password';
```

NAGIOS CONFIGURATION:
---------------------

The example below shows how to configure to run these checks via NRPE. This
configuration is for the Nagios server:

```
define service {
    check_command                  check_nrpe!check_mysql_connections
    use                            generic-service-graphed
    host_name                      mysqlserver.example.com
    service_description            MySQL Connections
}

define service {
    check_command                  check_nrpe!check_mysql_commands
    use                            generic-service-graphed
    host_name                      mysqlserver.example.com 
    service_description            MySQL Command Counters
}

define service {
    check_command                  check_nrpe!check_mysql_selects
    use                            generic-service-graphed
    host_name                      mysqlserver.example.com
    service_description            MySQL Select Types
}
```

NRPE configuration on the MySQL server:

```
command[check_mysql_commands]=/usr/lib64/nagios/plugins/check_mysql_commands -H localhost -u testuser -p password 
command[check_mysql_connections]=/usr/lib64/nagios/plugins/check_mysql_connections -H localhost -u testuser -p password -w 75 -c 90
command[check_mysql_selects]=/usr/lib64/nagios/plugins/check_mysql_selects -H localhost -u testuser -p password
```
