<?php
if (!defined('ABSPATH')) exit;
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e("WP Sitemap", 'wp-sitemap-ashlin'); ?>
	</h1>
	<?php if(!empty($message)){
		echo $message;
	} ?>
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=wp-sitemap-ashlin' ) ); ?>" method="post">
		<p>
			<input type="submit" id="wp-sitemap-crawl-submit" class="button" value="<?php esc_attr_e("Create sitemap", 'wp-sitemap-ashlin'); ?>">
			<input type="hidden" name="_nonce" value="<?php echo esc_attr(wp_create_nonce('wp_sitemap_crawl'));?>">
			<input type="hidden" name="action" value="wp_sitemap_crawl">
		</p>
	</form>
</div>
