=== Query Monitor xdebug Flamegraphs ===
Contributors: shadyvb
Tags: debug, development, xdebug, profiling, performance
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display flamegraphs within Query Monitor panels for XDebug traces.

== Description ==

Query Monitor xdebug Flamegraphs adds a new panel to Query Monitor that displays flamegraphs for XDebug traces. This helps you visualize the execution flow of your code and identify performance bottlenecks.

= Features =
* View flamegraphs for XDebug traces directly in Query Monitor
* Select from recent trace files

= Requirements =
* WordPress 6.0 or higher
* PHP 8.1 or higher
* XDebug 3.0 or higher
* Query Monitor plugin

== Installation ==

1. Install and activate the Query Monitor plugin
2. Install and activate this plugin
3. Enable XDebug tracing in your WordPress environment
   * For WP-ENV, run `npx wp-env start --xdebug=debug,trace` to start the WordPress environment with XDebug tracing enabled.

== Frequently Asked Questions ==

= How do I enable XDebug tracing? =

You need to configure XDebug in your php.ini file. Add these settings:

```ini
xdebug.trace_format=3
xdebug.trace_output_name=trace.%u.%R
```

= Why don't I see any traces? =

Make sure:
1. XDebug is installed and enabled and is at version 3.4 or above
2. Tracing is active
3. The trace output directory is writable
4. The trace format is set to 3

== Screenshots ==

1. Flamegraph panel in Query Monitor
2. Trace file selection dropdown
3. Example flamegraph visualization

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release

== Development ==

Development of this plugin happens on GitHub: [Query Monitor xdebug Flamegraphs](https://github.com/shadyvb/query-monitor-xdebug-flamegraphs)

= Filters =

* `qm/flamegraphs/number_of_files` - Filter to change number of files to show in the dropdown menu.
* `qm/flamegraph/library` - Filter to change the path to the flamegraph generation script.

This plugin is licensed under the GPL v3 or later, except for the included FlameGraph library which is licensed under CDDL 1.0.

