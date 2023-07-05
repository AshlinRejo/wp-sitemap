<?php
if (!defined('ABSPATH')) exit;
if (!empty($sitemap_url)) {
	?>
	<style>
		#wp_sitemap_container {
			padding: 10px;
			background: #ddd;
			text-align: center;
		}
	</style>
	<div id="wp_sitemap_container">
		<a href="<?php echo esc_url($sitemap_url); ?>" title="Sitemap">Sitemap.html</a>
	</div>
	<?php
}
?>
