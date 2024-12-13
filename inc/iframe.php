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
        Select a trace to visualize:
    </label>
	<select name="file">
		<?php foreach ( $files as $_file ) : ?>
			<option value="<?php echo esc_attr( $_file['filename'] ); ?>" <?php selected( $file, $_file['filename'] ); ?>>
				<?php printf(
                    '%s (%s ago)',
                    esc_html( $_file['filename'] ),
                    esc_html( human_time_diff( $_file['timestamp'], time() ) )
                ); ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>

<?php echo $flamegraph_data; ?>