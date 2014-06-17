<?php
/*
Plugin Name: SY Gallery
Plugin URI: https://github.com/samyapp/sygallery.git
Description: Display old nextgen-gallery galleries the way I want to....
Version: 0.1 BETA
Author: Sam Yapp
Author URI: http://samyapp.com
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("sygallery", "sygallery_handler");

function sygallery_handler($incoming) {
	global $wpdb;
	$gid = isset($incoming['id']) ? (int)$incoming['id'] : 0;
	$sql = 'select * from wp_ngg_gallery WHERE gid = '.$gid;
	$gallery = $wpdb->get_row($sql);
	$pic = null;
	$prev = null;
	$next = null;
	$pics = null;
	if ($gallery) {
		$pics = $wpdb->get_results('select * from wp_ngg_pictures WHERE galleryid = '.$gid.' order by sortorder,pid');
		$pid = isset($_REQUEST['pid']) ? (int)$_REQUEST['pid'] : 0;
		$gallery->number = 0;
		if ($pid) {
			$last = null;
			$i = 0;
			foreach( $pics as $p) {
				++$i;
				if ($p->pid == $pid) {
					$gallery->number = $i;
					$pic = $p;
					$prev = $last;
				}
				else if ($last && $last->pid == $pid) {
					$next = $p;
				}
				$last = $p;
			}
		}
		$gallery->pic = $pic;
		$gallery->pics = $pics;
		$gallery->prev = $prev;
		$gallery->next = $next;
		$gallery->url = get_permalink();
		$gallery->image_url = site_url($gallery->path);
		$gallery->size = count($gallery->pics);
		ob_start();
		if ($gallery->pic) {
		// got a pic? then show it with prev/next links
?>
<div style="text-align: center;">
<img src="<?php echo $gallery->image_url?>/<?php echo htmlspecialchars($pic->filename)?>" />
<div style="text-align: center;">Pic #<?php echo $gallery->number?> of <?php echo $gallery->size?>
(<a href="<?php echo get_permalink()?>">back to thumbnails</a>)
</div>
<hr />
<p><?php echo nl2br(htmlspecialchars($pic->alttext))?></p>
<hr />
<?php if( $gallery->prev) { ?>
<div style="float: left;position:relative;">
<a href="<?php echo get_permalink()?>?pid=<?php echo $prev->pid?>"><img src="<?php echo $gallery->image_url?>/thumbs/thumbs_<?php echo htmlspecialchars($prev->filename)?>" /></a>
<br />Previous
</div>
<?php } ?>
<?php if ($gallery->next) { ?>
<div style="float: right;position:relative;text-align:right;">
<a href="<?php echo get_permalink()?>?pid=<?php echo $next->pid?>"><img src="<?php echo $gallery->image_url?>/thumbs/thumbs_<?php echo htmlspecialchars($next->filename)?>" /></a>
<br />Next
</div>
<?php } ?>
<hr style="clear: both;" />
</div>
<?php
		}
		else {
		// otherwise, just show thumbnails
?>
<div>
	<?php foreach ($gallery->pics as $pic) { ?>
	<a href="<?php echo get_permalink()?>?pid=<?php echo $pic->pid?>"><img src="<?php echo $gallery->image_url?>/thumbs/thumbs_<?php echo htmlspecialchars($pic->filename)?>" /></a>
	<?php } ?>
</div>
<?php
		}
		return ob_get_clean();
	}
	return 'Gallery not found :(';
}

