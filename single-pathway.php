<?php
/**
 * The template for displaying all pages of the Course content type. This is primarily
 * a copy of Twenty_Twenty_One's single.php but with added stuff in there and a lot of
 * theme-specific stuff deleted.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header alignwide">

	

	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>


</header>

<div class="entry-content">
<?php if ( !is_front_page() ): ?>
		<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="/">Home</a></li>
		<?php $ancestors= get_ancestors($post->ID, 'page'); ?>
		<?php if($ancestors): ?>
		<?php $ancestors = array_reverse($ancestors) ?>
		<?php foreach($ancestors as $parent): ?>
		<li class="breadcrumb-item"><a href="<?php echo get_permalink($parent); ?>">
			<?php echo get_the_title($parent); ?>
		</a></li>
		<?php endforeach; ?>
		<?php endif; ?>
		<li class="breadcrumb-item active"><?php the_title() ?></li>
		</ol>
		<?php endif; ?>
	<div><?php the_terms( $post->ID, 'activity_types', 'Types: ', ', ', ' ' ); ?></div>


	<?php
	the_content();
	?>

<?php
$child_pages = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = ".$post->ID." AND post_type = 'page' ORDER BY menu_order", 'OBJECT');
//print_r($child_pages);
foreach($child_pages as $cpage) : ?>

<?= get_post_meta( $cpage->ID, 'required', true ) ?>
<div><a href="<?= $cpage->post_name ?>"><?= $cpage->post_title ?></a></div>
<div><?= $cpage->post_content ?></div>
<a href="<?= get_post_meta( $cpage->ID, 'hyperlink', true ) ?>"
	target="_blank"
	rel="nooperner">
	<?= get_post_meta( $cpage->ID, 'hyperlink', true ) ?>
</a>
<?php endforeach ?>

</div><!-- .entry-content -->

<footer class="entry-footer default-max-width">

	<?php the_meta() ?>
	
</footer><!-- .entry-footer -->


</article><!-- #post-<?php the_ID(); ?> -->

	<?php


endwhile; // End of the loop.

get_footer();
