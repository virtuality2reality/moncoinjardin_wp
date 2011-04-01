<?php
/**
 * This file controls the Import/Export functions of the Genesis Framework
 *
 * @since 1.4
 */

/**
 * This function controls the admin page for the Genesis Import/Export functionality.
 *
 * @since 1.4
 */
function genesis_import_export_admin() { ?>
	
	<div class="wrap">
		<?php screen_icon('tools'); ?>	
		<h2><?php _e('Genesis - Import/Export', 'genesis'); ?></h2>
			
			<table class="form-table"><tbody>
				
				<tr>
					<th scope="row"><p><b><?php _e('Import Genesis Settings File', 'genesis'); ?></b></p></th>
					<td>
						<p><?php _e('Upload the data file from your computer (.json) and we\'ll import your settings.', 'genesis'); ?></p>
						<p><?php _e('Choose the file from your computer and click "Upload and Import"', 'genesis'); ?></p>
						<p>
							<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin.php?page=genesis-import-export'); ?>">
								<?php wp_nonce_field('genesis-import'); ?>
								<input type="hidden" name="genesis-import" value="1" />
								<label for="genesis-import-upload"><?php sprintf( __('Upload File: (Maximum Size: %s)', 'genesis'), ini_get('post_max_size') ); ?></label>
								<input type="file" id="genesis-import-upload" name="genesis-import-upload" size="25" />
								<input type="submit" class="button" value="<?php _e('Upload file and import', 'genesis'); ?>" />
							</form>
						</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><p><b><?php _e('Export Genesis Settings File', 'genesis'); ?></b></p></th>
					<td>
						<p><?php _e('When you click the button below, Genesis will generate a JSON file for you to save to your computer.', 'genesis'); ?></p>
						<p><?php _e('Once you have saved the download file, you can use the import function on another site to import this data.', 'genesis'); ?></p>
						<p>
							<form method="post" action="<?php echo admin_url('admin.php?page=genesis-import-export'); ?>">
								<?php wp_nonce_field('genesis-export'); ?>
								<select name="genesis-export">
									<option value="theme">Theme Settings</option>
									<option value="seo">SEO Settings</option>
									<option value="all">Theme and SEO Settings</option>
								</select>
								<input type="submit" class="button" value="<?php _e('Download Export File', 'genesis'); ?>" />
							</form>
						</p>
					</td>
				</tr>
				
				<?php genesis_import_export_form(); // hook ?>
				
			</tbody></table>
		
	</div>
	
<?php }

add_action('admin_notices', 'genesis_import_export_notices');
/**
 * This is the notice that displays when you successfully save or reset
 * the theme settings.
 */
function genesis_import_export_notices() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'genesis-import-export' )
		return;
	
	if ( isset( $_REQUEST['imported'] ) && $_REQUEST['imported'] == 'true' ) {
		echo '<div id="message" class="updated"><p><strong>'.__('Settings successfully imported!', 'genesis').'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['error']) && $_REQUEST['error'] == 'true') {  
		echo '<div id="message" class="error"><p><strong>'.__('There was a problem importing your settings. Please Try again.', 'genesis').'</strong></p></div>';
	}
	
}

add_action( 'admin_init', 'genesis_export' );
/**
 * This function generates the export file, if requested, in JSON format
 *
 * @since 1.4
 */
function genesis_export() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'genesis-import-export' )
		return;
		
	if ( empty( $_REQUEST['genesis-export'] ) )
		return;
		
	check_admin_referer('genesis-export'); // Verify nonce
	
	// hookable
	do_action('genesis_export', $_REQUEST['genesis-export']);
	
	$settings = array();
	
	if ( $_REQUEST['genesis-export'] === 'all' ) {		
		$settings = array(
			GENESIS_SETTINGS_FIELD => get_option( GENESIS_SETTINGS_FIELD ),
			GENESIS_SEO_SETTINGS_FIELD => get_option( GENESIS_SEO_SETTINGS_FIELD )
		);
		$prefix = 'genesis-settings';
	}
	
	if ( $_REQUEST['genesis-export'] === 'theme' ) {		
		$settings = array(
			GENESIS_SETTINGS_FIELD => get_option( GENESIS_SETTINGS_FIELD )
		);
		$prefix = 'genesis-theme-settings';
	}
	
	if ( $_REQUEST['genesis-export'] === 'seo' ) {		
		$settings = array(
			GENESIS_SEO_SETTINGS_FIELD => get_option( GENESIS_SEO_SETTINGS_FIELD )
		);
		$prefix = 'genesis-seo-settings';
	}
	
	if ( !$settings ) return;
	
    $output = json_encode( (array)$settings );

    header( 'Content-Description: File Transfer' );
    header( 'Cache-Control: public, must-revalidate' );
    header( 'Pragma: hack' );
    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="' . $prefix . '-' . date("Ymd-His") . '.json"' );
    header( 'Content-Length: ' . strlen($output) );
    echo $output;
    exit;
	
}

add_action( 'admin_init', 'genesis_import' );
/**
 * This function handles the import.
 *
 * @since 1.4
 */
function genesis_import() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'genesis-import-export' )
		return;
		
	if ( empty( $_REQUEST['genesis-import'] ) )
		return;
		
	check_admin_referer('genesis-import'); // Verify nonce
	
	// hookable
	do_action('genesis_import', $_REQUEST['genesis-import'], $_FILES['genesis-import-upload']);
	
	// Extract file contents
	$upload = file_get_contents($_FILES['genesis-import-upload']['tmp_name']);
	
	// Decode the JSON
	$options = json_decode( $upload, true );
	
	// Check for errors
	if ( !$options || $_FILES['genesis-import-upload']['error'] ) {
		wp_redirect( admin_url( 'admin.php?page=genesis-import-export&error=true' ) );
		exit;
	}
	
	// Cycle through data, import settings
	foreach ( (array)$options as $key => $settings ) {
		update_option( $key, $settings );
	}
	
	// Redirect, add success flag to the URI
	wp_redirect( admin_url( 'admin.php?page=genesis-import-export&imported=true' ) );
	exit;
	
}