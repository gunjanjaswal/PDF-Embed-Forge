<?php
/**
 * Plugin Name:       PDF Embed Forge
 * Plugin URI:        https://github.com/gunjanjaswal/PDF-Embed-Forge
 * Description:       Embed PDF files directly on your site with a block or a shortcode. A clean, responsive, full-width inline viewer. No external services.
 * Version:           1.0.4
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Gunjan Jaswal
 * Author URI:        https://www.gunjanjaswal.me
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pdf-embed-forge
 * Domain Path:       /languages
 *
 * @package PDF_Embed_Forge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PDF_EMBED_FORGE_VERSION', '1.0.4' );
define( 'PDF_EMBED_FORGE_FILE', __FILE__ );
define( 'PDF_EMBED_FORGE_DIR', plugin_dir_path( __FILE__ ) );
define( 'PDF_EMBED_FORGE_URL', plugin_dir_url( __FILE__ ) );

require_once PDF_EMBED_FORGE_DIR . 'includes/class-pdf-embed-forge.php';

PDF_Embed_Forge::instance();
