<?php
/**
 * Class for save traitify settings 
 */
class Traitify_Settings
{
	function save_settings() {
		$traitify_settings = array();
		$traitify_secretkey = $traitify_secretkey = '';
		if(isset($_POST['traitify_secretkey'])) {
			$traitify_settings['secretkey'] = sanitize_text_field($_POST['traitify_secretkey']);
		}
		if(isset($_POST['traitify_publickey'])) {
                        $traitify_settings['publickey'] = sanitize_text_field($_POST['traitify_publickey']);
                }
		if($traitify_settings) {
			update_option('traitify_settings', $traitify_settings);
		} 
		$get_traitify_settings = get_option('traitify_settings');
		$get_traitify_secretkey = $get_traitify_settings['secretkey'];
		$get_traitify_publickey = $get_traitify_settings['publickey'];
		$content = '<div style="width:98%;">
			<h3>Settings</h3>
			<form name="traitify_settings" method="post" action="">
			<div class="form-group">
			<label for="traitify_secretkey">Secret Key</label>
			<input type="text" class="form-control" id="traitify_secretkey" name="traitify_secretkey" placeholder="Enter your Secret Key" value="' .$get_traitify_secretkey. '">
			</div>
			<div class="form-group">
			<label for="traitify_publickey">Public Key</label>
			<input type="text" class="form-control" id="traitify_publickey" name="traitify_publickey" placeholder="Enter your Public Key" value="' .$get_traitify_publickey. '">
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
			</form>
			</div>';

		echo $content;
	}
}
