<?php
/*
Plugin Name: JR_AntiSpam
Plugin URI: http://www.jakeruston.co.uk/2009/11/wordpress-plugin-jr-antispam/
Description: Block spam on your blog - Forever!
Version: 1.1.5
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2009 Jake Ruston - the.escapist22@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Hook for adding admin menus
add_action('admin_menu', 'jr_antispam_add_pages');
add_action('comment_post', 'jr_antispam_check');
add_action('comment_form', 'jr_antispam_show');

// action function for above hook
function jr_antispam_add_pages() {
    add_options_page('JR Antispam', 'JR Antispam', 'administrator', 'jr_antispam', 'jr_antispam_options_page');
}

if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}

register_activation_hook(__FILE__,'antispam_choice');

function antispam_choice () {
if (get_option("jr_antispam_links_choice")=="") {

$content = curl_get_contents("http://www.jakeruston.co.uk/pluginslink4.php");

update_option("jr_antispam_links_choice", $content);
}
}

// jr_antispam_options_page() displays the page content for the Test Options submenu
function jr_antispam_options_page() {

    // variables for the field and option names 
    $opt_name = 'mt_antispam_header';
	$opt_name_2 = 'mt_antispam_type';
	$opt_name_3 = 'mt_antispam_question';
	$opt_name_4 = 'mt_antispam_answer';
	$opt_name_5 = 'mt_antispam_ips';
	$opt_name_9 = 'mt_antispam_plugin_support';
    $hidden_field_name = 'mt_antispam_submit_hidden';
    $data_field_name = 'mt_antispam_header';
	$data_field_name_2 = 'mt_antispam_type';
	$data_field_name_3 = 'mt_antispam_question';
	$data_field_name_4 = 'mt_antispam_answer';
	$data_field_name_9 = 'mt_antispam_plugin_support';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
	$opt_val_2 = get_option( $opt_name_2 );
	$opt_val_3 = get_option( $opt_name_3 );
	$opt_val_4 = get_option( $opt_name_4 );
	$opt_val_9 = get_option($opt_name_9);
    
if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Antispam";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$subject=$_POST['subject'];
$name=$_POST['name'];
$response=$_POST['response'];
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
$headers1 = "From: feedback@jakeruston.co.uk";
$emailsubject1=$response.$plugin_name." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);
?>
<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>
<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];
		$opt_val_2 = $_POST[ $data_field_name_2 ];
		$opt_val_3 = $_POST[ $data_field_name_3 ];
		$opt_val_4 = $_POST[ $data_field_name_4 ];
		$opt_val_9 = $_POST[$data_field_name_9];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
		update_option( $opt_name_2, $opt_val_2 );
		update_option( $opt_name_3, $opt_val_3 );
		update_option( $opt_name_4, $opt_val_4 );
		update_option( $opt_name_9, $opt_val_9 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Antispam Plugin Options', 'mt_trans_domain' ) . "</h2>";

		?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p></div>
<?php

    // options form
    
    $change4 = get_option("mt_antispam_plugin_support");
	$change5 = get_option("mt_antispam_type");

if ($change4=="Yes" || $change4=="") {
$change4="checked";
$change41="";
} else {
$change4="";
$change41="checked";
}

if ($change5=="CAPTCHA" || $change5=="") {
$change5="checked";
$change51="";
$change52="";
} else if ($change5=="Math") {
$change5="";
$change51="checked";
$change52="";
} else if ($change5=="Custom") {
$change5="";
$change51="";
$change52="checked";
}
    ?>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("CAPTCHA Label (Text next to CAPTCHA):", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>">
</p><hr />

<p><?php _e("Type of Security:", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_2; ?>" value="CAPTCHA" <?php echo $change5; ?>>CAPTCHA
<input type="radio" name="<?php echo $data_field_name_2; ?>" value="Math" <?php echo $change51; ?>>Basic Math Question
<input type="radio" name="<?php echo $data_field_name_2; ?>" value="Custom" <?php echo $change52; ?>>Custom
</p><hr />

<p><?php _e("Custom Question:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_3; ?>" value="<?php echo $opt_val_3; ?>">
</p><hr />

<p><?php _e("Custom Answer:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_4; ?>" value="<?php echo $opt_val_4; ?>">
</p><hr />

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_9; ?>" value="Yes" <?php echo $change4; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_9; ?>" value="No" <?php echo $change41; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<h3>Feedback Form!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b></p>
<form name="form2" method="post" action="">
<p><?php _e("Name (Optional):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("E-Mail (Optional):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Subject:", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Comment:", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ) ?>" />
</p><hr />
</form>
</div>
<?php
}

if (get_option("jr_antispam_links_choice")=="") {
antispam_choice();
}

function jr_antispam_show($id) {

  $antispamlabel = get_option("mt_antispam_header"); 
  $supportplugin = get_option("mt_antispam_plugin_support");
  $antispamtype = get_option("mt_antispam_type");
  $antispamquestion = get_option("mt_antispam_question");
  
  if ($antispamlabel=="") {
  $antispamlabel="Code";
  }
  
global $user_ID;

if ($user_ID) {
} else {

if ($antispamtype=="CAPTCHA" || $antispamtype=="") {
echo '<p>'.$antispamlabel.'*:<br /><img src="wp-content/plugins/jr-antispam/captcha.php" /><input type="text" name="sec15784" /></p>';
}

if ($antispamtype=="Math") {
echo '<p>'.$antispamlabel.'*:<br /><img src="wp-content/plugins/jr-antispam/math.php" /><input type="text" name="sec15784" /></p>';
}

if ($antispamtype=="Custom") {
echo '<p><strong>'.$antispamquestion.'</strong><br /><input type="text" name="sec15784" /></p>';
}
}

if ($supportplugin=="" || $supportplugin=="Yes") {
add_action('wp_footer', 'antispam_footer_plugin_support');
}
}

 function get_comment_author_IP2() {
global $comment;
return apply_filters('get_comment_author_IP', $comment->comment_author_IP);
}

function jr_antispam_check($id) {
$captcha_entry=$_POST['sec15784'];
$antispamtype = get_option("mt_antispam_type");
$antispamanswer = get_option("mt_antispam_answer");

global $user_ID;

if ($user_ID) {
} else {

if ($antispamtype=="Custom") {
if (strtolower($captcha_entry)!=strtolower($antispamanswer)) {
wp_set_comment_status($id, 'delete');
echo "The code was entered incorrectly. Please try again.";
exit;
}
}

if ($antispamtype=="Math") {
if ($captcha_entry != $_SESSION['math']) {
wp_set_comment_status($id, 'delete');
echo "The code was entered incorrectly. Please try again.";
exit;
}
}

if ($antispamtype=="CAPTCHA" || $antispamtype=="") {
if($captcha_entry != $_SESSION['captcha']) {
wp_set_comment_status($id, 'delete');
echo "The code was entered incorrectly. Please try again.";
exit;
}
}
}
}

function antispam_footer_plugin_support() {
  $pshow = "<p style='font-size:x-small'>Antispam Plugin created by <a href='http://www.jakeruston.co.uk'>Jake</a> Ruston - ".get_option('jr_antispam_links_choice')."</p>";
  echo $pshow;
}

?>
