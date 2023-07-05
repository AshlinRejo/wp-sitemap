<?php
if (!defined('ABSPATH')) exit;
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php esc_html_e('Sitemap', 'wp-sitemap-ashlin'); ?></title>
	<style>
		ul {
			padding: 0;
		}

		li {
			list-style: none;
		}

		.wp_sitemap_block {
			width: 23%;
			min-width: 250px;
			display: inline-table;
		}
	</style>
</head>
<body>
<?php include 'sitemap.php'; ?>
</body>
</html>
