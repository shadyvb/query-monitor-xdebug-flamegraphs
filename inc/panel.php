<?php declare( strict_types=1 );
namespace QM\XdebugFlamegraphs;

$url = wp_nonce_url(
    admin_url( 'admin-ajax.php?action=qm_get_flamegraph_iframe_content' ),
    'qm_flamegraph_nonce',
    'nonce'
);

// Check if xdebug functions exist and are available
$has_xdebug = extension_loaded('xdebug');
$filename = '';

if ( $has_xdebug && function_exists( 'xdebug_get_tracefile_name' ) ) {
    try {
        $tracefile = @xdebug_get_tracefile_name();
        if ($tracefile) {
            $filename = str_replace( ini_get( 'xdebug.output_dir' ) . '/', '', $tracefile );
            $url = add_query_arg( 'file', $filename, $url );
        }
    } catch (\Throwable $e) {
        // Silently fail if xdebug functions throw errors
    }
}

$notices = [];

if ( ! $has_xdebug ) {
	$notices[] = __( '<strong>Error:</strong> Xdebug is not available, traces are not generated, so you can only view traces that are already generated.', 'query-monitor-xdebug-flamegraphs' );
}

if ( empty( $filename ) ) {
	$notices[] = __( '<strong>Error:</strong> Tracing is not active, traces are not generated, so you can only view traces that are already generated.', 'query-monitor-xdebug-flamegraphs' );
}

if ( '3' !== ini_get( 'xdebug.trace_format' ) ) {
	$notices[] = __( '<strong>Error:</strong> Xdebug trace format is not 3, flamegraphs cannot be generated for other formats. Please set <code>xdebug.trace_format</code> to 3 in your <code>php.ini</code> file.', 'query-monitor-xdebug-flamegraphs' );
}

if ( strpos( ini_get( 'xdebug.trace_output_name' ), '%R' ) === false ) {
	$notices[] = __( '<strong>Notice:</strong> Xdebug trace output name does not contain request URL, add %R to the end of the trace output name to make it easier to associate traces with requests. <a href="https://xdebug.org/docs/trace#trace_output_name" target="_blank" rel="noopener noreferrer">Learn more</a>.', 'query-monitor-xdebug-flamegraphs' );
}
?>

<div class="qm qm-non-tabular" id="qm-flamegraph" style="width: 100%; height: 100%;">
    <style>
        #qm-flamegraph-container { width: 100%; height: 100%; }
        #qm-flamegraph-container .qm .qm-notice{ width: max-content; max-width: calc(100% - 80px) !important; }
    </style>

    <?php if ( ! empty( $notices ) ) : ?>
        <div class="qm-boxed">
            <section>
                <div class="qm-notice">
                    <?php foreach ( $notices as $notice ) : ?>
                        <p><?php echo $notice; ?></p>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    <?php endif; ?>

    <div class="qm-loading">
        Loading...
    </div>
    <iframe
        loading="lazy"
        src="<?php echo esc_url( $url ); ?>"
        width="100%%"
        height="100%%"
        onload="document.querySelector( '#qm-flamegraph .qm-loading' ).remove()"
    ></iframe>

</div>
