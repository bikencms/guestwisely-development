<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-category-grid', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-category-grid.css", ["_villas-365-styles"], VILLAS_365_VERSION);

if(file_exists(get_stylesheet_directory() . "/365villas/css/villas-365-styles.css"))
{
	wp_enqueue_style('_child-villas-365-styles', get_stylesheet_directory_uri() . "/365villas/css/villas-365-styles.css", ["_villas-365-styles"], wp_get_theme()->get('Version'));
}

if(file_exists(get_stylesheet_directory() . "/365villas/css/villas-365-category-grid.css"))
{
	wp_enqueue_style('_child-villas-365-category-grid-styles', get_stylesheet_directory_uri() . "/365villas/css/villas-365-category-grid.css", ["_child-villas-365-styles", "_villas-365-category-grid"], wp_get_theme()->get('Version'));
}

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

$searchPageURL = null;
if(isset($cateogryGrid_atts) && (count($cateogryGrid_atts) > 0) && array_key_exists("searchpageid", $cateogryGrid_atts) && is_numeric($cateogryGrid_atts["searchpageid"]))
{
	$searchPageURL = get_page_link($cateogryGrid_atts["searchpageid"]);
}

$categoriesPerRow = 4;
if(isset($cateogryGrid_atts) && (count($cateogryGrid_atts) > 0) && array_key_exists("perrow", $cateogryGrid_atts) && is_numeric($cateogryGrid_atts["perrow"]))
{
	$categoriesPerRow = $cateogryGrid_atts["perrow"];
}


$searchCategories = API365::GetCategories([
	"include" => [
		"gallery",
		"languages"
	],
	"property_include" => 1
]);
?>

<?php if(!is_null($searchPageURL)) :
	if(!is_null($searchCategories)) : ?>
<div class="_villas-365-bootstrap _villas-365-category-grid">
	<div class="container">
		<?php
		$firstLoop = true;
		foreach(array_chunk($searchCategories, $categoriesPerRow, true) as $categoriesChunk):
			//If we don't have enough to fill a row then break out of the loop.
			if(!$firstLoop && count($categoriesChunk) < $categoriesPerRow)
			{
				break;
			}
		?>
			<div class="row">
				<?php
				//Add some empty columns so the columns on the page add up correctly and are the correct width.
				while(count($categoriesChunk) < $categoriesPerRow)
				{
					$categoriesChunk[] = null;
				}
				?>
				<?php foreach($categoriesChunk as $categoryId => $category): ?>
				<div class="col-6 col-lg mb-3 px-2">
					<?php if(!is_null($category)):
						$categoryImageUrl = null;
						if(array_key_exists("gallery", $category) && (count($category["gallery"]) > 0))
						{
							$categoryImageUrl = $category["gallery"][0];
						}
					?>
					<div class="_villas-365-category">
						<div class="_villas-365-category-image-overlay"></div>
						<a href="<?php echo esc_html(!is_null($searchPageURL) ? $searchPageURL . "?categoryid=" . $categoryId : "#"); ?>" class="unstyled-link _villas-365-category-image-link" style="background-image:url('<?php echo esc_html($categoryImageUrl); ?>');">
							<h5 class="_villas-365-category-name"><?php echo esc_html($category["name"]) ?></h5>
						</a>
					</div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		<?php
			$firstLoop = false;
		endforeach; ?>
	</div>
</div>
<?php endif;
else : ?>
<div class="_villas-365-bootstrap _villas-365-search">
	<div class="container">
		<div class="row">
			<div class="col">
			<?php __v3te("Please specify a valid search page ID to use the cagegory grid."); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>