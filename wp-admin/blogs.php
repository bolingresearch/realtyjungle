<?php
/**
 * Blog Administration Panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once('admin.php');

if ( isset( $_POST[ 'action' ] ) ) {
	switch( $_POST[ 'action' ] ) {
		case "update":
			do_action( 'myblogs_update' );
			wp_redirect( admin_url( 'blogs.php?updated=1' ) );
			die();
		break;
	}
}

$blogs = get_blogs_of_user( $current_user->ID );
if( !$blogs || ( is_array( $blogs ) && empty( $blogs ) ) ) {
	wp_die( __( 'You must be a member of at least one blog to use this page.' ) );
}

if ( empty($title) )
	$title = __('My Blogs');
$parent_file = 'profile.php';

require_once('admin-header.php');
?>
<div class="wrap">
<?php if( $_GET[ 'updated' ] ) { ?>
<div id="message" class="updated fade"><p><strong><?php _e( 'Your blog options have been updated.' ); ?></strong></p></div>
<?php } ?>
<?php screen_icon(); ?>
<h2><?php echo wp_specialchars( $title ); ?></h2>
<form id="myblogs" action="" method="post">
<?php
do_action( 'myblogs_allblogs_options' );
?><table class='widefat'> <?php 
$settings_html = apply_filters( 'myblogs_options', '', 'global' );
if( $settings_html != '' ) { ?>
	<tr><td valign='top'><h3><?php _e( 'Global Settings' ); ?></h3></td><td>
	<?php echo $settings_html; ?>
	</td></tr>
<?php }
reset( $blogs );
foreach( $blogs as $user_blog ) {
	$c = $c == "alternate" ? "" : "alternate";
	?><tr class='<?php echo $c; ?>'><td valign='top'><h3><?php echo $user_blog->blogname; ?></h3>
	<p><?php echo apply_filters( "myblogs_blog_actions", "<a href='{$user_blog->siteurl}'>Visit</a> | <a href='{$user_blog->siteurl}/wp-admin/'>Dashboard</a>", $user_blog ); ?></p>
	</td><td valign='top'>
	<?php
	echo apply_filters( 'myblogs_options', '', $user_blog );
	?></td></tr><?php
}
?>
</table>
	<input type="hidden" name="action" value="update" />
	<input type="submit" class="button-primary" value="<?php _e('Update Options') ?>" name="submit" />
</form>
</div>
<?php include('admin-footer.php'); ?>
