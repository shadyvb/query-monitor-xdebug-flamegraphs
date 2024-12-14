<style>
    body {
        margin: 0;
        padding: 0;
		font-family: -apple-system, "system-ui", "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		#qm-flamegraph-select {
			padding: 0 0 10px 0;
		}
		#qm-flamegraph-select select {
			padding: 5px;
		}
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
		document.querySelector( 'select[name="file"]' ).addEventListener('change', function(event) {
            this.disabled = true;
            window.location.href = window.location.href.replace(/file=[^&]*/, 'file=' + event.target.value);
        });
    });
</script>

<div id="qm-flamegraph-select">
    <label for="file">
        <?php esc_html_e( 'Select a trace to visualize:', 'query-monitor-xdebug-flamegraphs' ); ?>
    </label>
	<select name="file">
		<?php foreach ( $files as $_file ) : ?>
			<option value="<?php echo esc_attr( $_file['filename'] ); ?>" <?php selected( $file, $_file['filename'] ); ?>>
				<?php printf(
                    /* translators: 1: filename, 2: time ago */
                    esc_html__( '%1$s (%2$s ago)', 'query-monitor-xdebug-flamegraphs' ),
                    esc_html( $_file['filename'] ),
                    esc_html( human_time_diff( $_file['timestamp'], time() ) )
                ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>

<?php echo $flamegraph_data; ?>