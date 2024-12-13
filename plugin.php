<?php declare( strict_types=1 );
/**
 * Plugin Name:     Query Monitor xdebug Flamegraphs
 * Plugin URI:      https://github.com/shadyvb/query-monitor-xdebug-flamegraphs
 * Description:     Display flamegraphs within Query Monitor panels
 * Version:         1.0.0
 * Author:          Shady Sharaf
 * Author URI:      https://sharaf.me
 * License:         GPL3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     query-monitor-xdebug-flamegraphs
 * Domain Path:     /languages
 * Requires PHP:    8.1
 * Tested up to:    6.4
 */

namespace QM\XdebugFlamegraphs;

 if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register the init action.
add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Initialize the plugin
 *
 * @return void
 */
function init(): void {
	// Check PHP version
	if ( version_compare( PHP_VERSION, '8.1', '<' ) ) {
		add_action( 'admin_notices', function() {
			echo '<div class="error"><p>' .
				 esc_html__( 'Query Monitor xdebug Flamegraphs requires PHP 8.1 or higher.', 'query-monitor-xdebug-flamegraphs' ) .
				 '</p></div>';
		});
		return;
	}

	// Register the outputter.
	add_filter( 'qm/outputter/html', __NAMESPACE__ . '\register_qm_output', 120, 2 );
	// Register an AJAX action to get the flamegraph iframe content.
	add_action( 'wp_ajax_qm_get_flamegraph_iframe_content', __NAMESPACE__ . '\get_flamegraph_iframe_content' );
}

/**
 * Register the output
 *
 * @param array $output
 * @param \QM_Collectors $collectors
 * @return array
 */
function register_qm_output( array $output ) {
	$output['flamegraph'] = new class ( new class extends \QM_Collector {
		public $id = 'flamegraph';
	} ) extends \QM_Output_Html {
		public function __construct( \QM_Collector $collector ) {
			parent::__construct( $collector );
			add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 110 );
		}

		public function name() {
			return 'Flamegraph 🔥';
		}

		public function output() {
			include_once __DIR__ . '/inc/panel.php';
		}
	};
	return $output;
}

/**
 * Get the top latest trace files.
 *
 * @return array
 */
function get_xdebug_trace_files() {
	$trace_files = [];
	$trace_files_dir = ini_get( 'xdebug.output_dir' );
	$number_of_files = apply_filters( 'qm/flamegraphs/number_of_files', 50 );
	$trace_file_format = ini_get( 'xdebug.trace_output_name' );
	$trace_file_format = preg_replace( '/%[a-z]/i', '*', $trace_file_format ) . '*';

	// Iterate over the trace files using DirectoryIterator, and get only the top $number_of_files files.
	$dir = new \DirectoryIterator( $trace_files_dir );
	foreach ( $dir as $file ) {
		if ( ! fnmatch( $trace_file_format, $file->getFilename() ) ) {
			continue;
		}

		$trace_files[] = [
			'filename' => $file->getFilename(),
			'timestamp' => $file->getCTime(),
		];
	}

	// Sort the trace files by timestamp, and get the latest $number_of_files files.
	usort( $trace_files, function( $a, $b ) {
		return $b['timestamp'] - $a['timestamp'];
	} );

	$trace_files = array_slice( $trace_files, 0, $number_of_files );

	// Return the trace files flat array.
	return $trace_files;
}

/**
 * Get the flamegraph iframe content.
 *
 * @return void
 */
function get_flamegraph_iframe_content(): void {
	$xdebug_output_dir = ini_get( 'xdebug.output_dir' );
	$file = filter_input( INPUT_GET, 'file' );
	$full_file_path = $xdebug_output_dir . '/' . $file;
	$files = get_xdebug_trace_files();

	// Verify the file is in the list of trace files.
	$flamegraph_data = '';
	if ( 0 !== strlen( $file ) && file_exists( $full_file_path ) && in_array( $file, array_column( $files, 'filename' ) ) ) {
		$flamegraph_data = generate_flamegraph( $full_file_path );
	}

	include_once dirname( __FILE__ ) . '/inc/iframe.php';

	wp_die( '' );
}

/**
 * Get the flamegraph data for a given trace file.
 *
 * @param string $file
 * @return string
 */
function generate_flamegraph( string $file ): string {
	if ( ! file_exists( $file ) ) {
		return '';
	}

	$flamegraph_library = apply_filters( 'qm/flamegraph/library', __DIR__ . '/flamegraphs/flamegraph.pl' );

	if ( ! $flamegraph_library || ! file_exists( $flamegraph_library ) ) {
		return '';
	}

	ob_start();
	passthru( $flamegraph_library . ' ' . $file );
	$data = ob_get_clean();

	return $data;
}
