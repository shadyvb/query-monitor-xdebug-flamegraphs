# Query Monitor XDebug Flamegraphs

A WordPress plugin that generates flame graphs from XDebug tracing data in a Query Monitor panel.

## Description

This plugin extends Query Monitor to visualize XDebug tracing data using flame graphs, helping developers identify performance bottlenecks in their WordPress applications.

The plugin shows the current request trace data by default, but also allows viewing previous trace files as well through a dropdown menu.

## Third-Party Libraries

This plugin includes the following third-party software:

### FlameGraph

This plugin includes the FlameGraph library for generating flame graph visualizations.

- Original work Copyright 2016 Netflix, Inc.
- Original work Copyright 2011 Joyent, Inc.
- Original work Copyright 2011 Brendan Gregg
- Source: https://github.com/brendangregg/FlameGraph
- License: CDDL 1.0 (http://opensource.org/licenses/CDDL-1.0)

The FlameGraph library is used under the terms of the Common Development and Distribution License (CDDL) Version 1.0. A copy of the CDDL license can be found in `flamegraphs/cddl1.txt`.

## License

This plugin is licensed under the GPL v2 or later, except for the included FlameGraph library which is licensed under CDDL 1.0.

## Installation

- Install and activate the Query Monitor plugin
- Install and activate this plugin
- Enable XDebug tracing in your WordPress environment
  - For WP-ENV, run `npx wp-env start --xdebug=debug,trace` to start the WordPress environment with XDebug tracing enabled.

## Usage

- Navigate to the Query Monitor panel in your WordPress admin
- Click on the "Flamegraph 🔥" panel to view the current request trace data
- Use the dropdown menu to view previous trace files

## Filters

- `qm/flamegraphs/number_of_files` - Filter to change number of files to show in the dropdown menu.

## Requirements

- WordPress 6.0
- PHP 8.1
- XDebug 3.4
- Query Monitor

