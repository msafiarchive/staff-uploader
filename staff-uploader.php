<?php
/***************
 * Plugin Name: Staff Uploader
 * Plugin URI:
 * Description: This plugin leverages the Fine Uploader JavaScript upload to allow staff uploads through the WordPress Admin Panel.
 ***************/
add_action('admin_menu', 'staff_uploader_menu');
add_action( 'admin_enqueue_scripts', 'load_uploader_styles_scripts' );
add_action( 'admin_head', 'add_uploader_template' );

function add_uploader_template() {
	?>
	<script type="text/template" id="qq-template">
			<div class="qq-uploader-selector qq-uploader">
					<div class="qq-upload-button-selector qq-upload-button">
							<div>Select Files</div>
					</div>
					<div class="qq-upload-drop-area-selector uploader-drop-zone">
							<span class="drop-zone-text">Drop Files Here</span>
							<ul class="qq-upload-list-selector qq-upload-list">
									<li class="file-container">
											<div class="qq-progress-bar-container-selector">
													<div class="qq-progress-bar-selector qq-progress-bar"></div>
											</div>
											<div class="file-info">
													<span class="qq-upload-spinner-selector qq-upload-spinner"></span>
													<img class="qq-thumbnail-selector" qq-max-size="50" qq-server-scale>
													<span class="qq-upload-file-selector qq-upload-file"></span>
													<span class="qq-upload-size-selector qq-upload-size"></span>
											</div>
											<button class="qq-upload-cancel-selector qq-upload-cancel" href="#">Cancel</button>
											<button class="qq-upload-retry-selector qq-upload-retry" href="#">Retry</button>
											<span class="qq-upload-status-text-selector qq-upload-status-text"></span>

                      <span class="staff-uploader-links" style="display: none;">
                        <a target="_blank">View file</a>

                        <a target="_blank">View manifest</a>
                      </span>

											<input type="button" value="View the link" class="staff-uploader-copy-to-clipboard view-btn" style="display: none;">
									</li>
							</ul>
					</div>
					<span class="qq-drop-processing-selector qq-drop-processing">
							<span>Processing dropped files...</span>
							<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
					</span>
			</div>
	</script>
<?php
}

function load_uploader_styles_scripts() {
	wp_register_style('fineuploader-4_4_0', plugins_url('/s3.jquery.fineuploader-4.4.0/fineuploader-4.4.0.css', __FILE__ ),array(), '1.0' );
	wp_register_style('staff-uploader-css', plugins_url('/custom.css', __FILE__ ),array(), '1.0' );
	wp_enqueue_style('fineuploader-4_4_0');
	wp_enqueue_style('staff-uploader-css');
	wp_enqueue_script('fineuploader-4_4_0_script', plugins_url('/s3.jquery.fineuploader-4.4.0/s3.jquery.fineuploader-4.4.0.js', __FILE__ ),array(), '1.0' );
	wp_enqueue_script('aws-glue', plugins_url('/aws-sdk-glue.js', __FILE__ ),array(), '1.0');
	wp_enqueue_script('google-auth', plugins_url('/google-auth.js', __FILE__ ),array(), '1.0');
	wp_enqueue_script('fineuploader-glue', plugins_url('/fineuploader-glue.js', __FILE__ ),array(), '1.0');
	wp_enqueue_script('aws-sdk', '//sdk.amazonaws.com/js/aws-sdk-2.0.0-rc4.min.js');
	wp_enqueue_script('lodash', '//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.3.1/lodash.min.js');
}

function staff_uploader_menu() {
	add_menu_page('Staff Uploader', 'Staff Uploader', 'edit_posts', 'staff-uploader', 'staff_uploader_admin_screen');
}

function staff_uploader_admin_screen() {
	if ( !current_user_can( 'edit_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

?>

<div class="wrapper">
		<h2 class="demo-title">AppAdvice Staff File Uploader</h2>

		<!-- Only lods the content between the "if tags" if you are not running IE9 or older -->
		<!--[if !IE | gt IE 9]> -->
		<div class="row-fluid">
				<div class="span4">
						<div class="sign-in-buttons">
								<!-- Google sign-in button -->
								<div id="google-signin" class="signin-button" style="position:relative; top:18px;">
									<p>Sign-in to start</p>
									<span
										class="g-signin"
										data-callback="s3GoogleOauthHandler"
										data-clientid="1026884566589-phgrus0dftqmmijanahpnkl37fmrqoe1.apps.googleusercontent.com"
										data-cookiepolicy="single_host_origin"
										data-requestvisibleactions="http://schemas.google.com/AddActivity"
										data-scope="https://www.googleapis.com/auth/plus.login">
									</span>
								</div>
						</div>
				</div>
		</div>

		<div id="uploader"></div>

		<hr>

		<!-- <![endif]-->

		<!-- Displayed in place of login buttons & uploader if the browser is IE9 or older. -->
		<!--[if lt IE 10]>
		<div>Sorry, this demo is only functional in modern browsers, such as IE10+, Chrome, Firefox, Opera, and Safari.</div>
		<![endif]-->
</div>

<?php
}

?>
