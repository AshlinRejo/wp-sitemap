<?php
if (!defined('ABSPATH')) exit;
?>
<h3><?php esc_html_e('Sitemap', 'wp-sitemap-ashlin'); ?></h3>
<?php if (isset($last_updated_at)) { ?>
	<p><?php echo sprintf(esc_html__('Last updated on %s', 'wp-sitemap-ashlin'), $last_updated_at); ?></p>
<?php } ?>
<br>
<?php if (isset($sitemap['sitemap'])) { ?>
	<?php foreach ($sitemap['sitemap'] as $key => $items) { ?>
		<div class="wp_sitemap_block">
			<p>
				<b><?php echo esc_html(strtoupper(str_replace('-', ' ', $key))); ?></b>
			</p>
			<p>
			<ul>
				<?php foreach ($items as $item) { ?>
					<li>
						<a href="<?php echo esc_attr($item['href']); ?>"><?php echo esc_html($item['anchorText']); ?></a>
					</li>
				<?php } ?>
			</ul>
			</p>
		</div>
	<?php } ?>
<?php } ?>
