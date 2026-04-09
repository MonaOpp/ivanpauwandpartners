<?php
/**
 * The template for the front page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ipp_tw
 */

get_header();
?>

    <?php if ( locate_template( 'template-parts/content/content-home-slider.php' ) ) : ?>
        <?php get_template_part( 'template-parts/content/content', 'home-slider' ); ?>
    <?php endif; ?>

    <?php
    $front_page_id = get_option( 'page_on_front' );
    $description   = get_field( 'description', $front_page_id );
    if ( ! empty( $description ) ) :
    ?>
        <section class="w-full bg-[#F7F2F2]">
            <div class="layout-wrapper mx-auto px-6 py-12">
                <div class="prose max-w-none text-[#132E47]">
                    <?php echo wp_kses_post( $description ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section id="primary" class="bg-[#F7F2F2]">
        <main id="main">
            <div class=" mx-auto w-full ]">
                <?php
                $categories = get_terms(
                    array(
                        'taxonomy'   => 'practice-area-category',
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                    )
                );

                $practice_area_post_type = post_type_exists( 'practice-area' ) ? 'practice-area' : 'post';
                ?>

                <div class=" bg-white px-6 py-10 text-center">
                    <h2 class="layout-wrapper m-0 text-6xl leading-none tracking-wide md:text-6xl ">
                        <span class="font-extrabold uppercase text-[#3A5F82]">OUR KEY </span>
                        <span class="font-semibold uppercase italic text-[#AA7040]">PRACTICE AREAS</span>
                        <span class="font-extrabold text-[#132E47]">.</span>
                    </h2>
                </div>

                <?php if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) : ?>
                    <?php
                    $category_backgrounds = array(
                        0 => '/wp-content/uploads/2026/04/LandDevelopmentHome-scaled.jpg',
                        1 => '/wp-content/uploads/2026/04/Litigation-scaled.jpg',
                    );
                    $cat_index = 0;
                    ?>
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <?php foreach ( $categories as $category ) : ?>
                            <?php $bg_image = isset( $category_backgrounds[ $cat_index ] ) ? $category_backgrounds[ $cat_index ] : ''; ?>
                            <div class="relative flex min-h-[420px] items-center justify-center bg-[#132E47] p-8 text-center text-white"
                                <?php if ( $bg_image ) : ?>
                                    style="background-image: url('<?php echo esc_url( $bg_image ); ?>'); background-size: cover; background-position: center;"
                                <?php endif; ?>
                            >
                                <?php if ( $bg_image ) : ?>
                                    <div class="absolute inset-0" style="background-color: #0000008C;"></div>
                                <?php endif; ?>
                                <div class="relative z-10 w-full">
                                    <h3 class="mb-6 text-3xl font-extrabold uppercase leading-tight md:text-5xl text-white ">
                                        <?php echo esc_html( $category->name ); ?>
                                    </h3>

                                    <?php
                                    $practice_areas = get_posts(
                                        array(
                                            'post_type'      => $practice_area_post_type,
                                            'posts_per_page' => -1,
                                            'post_status'    => 'publish',
                                            'orderby'        => 'menu_order title',
                                            'order'          => 'ASC',
                                            'tax_query'      => array(
                                                array(
                                                    'taxonomy' => 'practice-area-category',
                                                    'field'    => 'term_id',
                                                    'terms'    => $category->term_id,
                                                ),
                                            ),
                                        )
                                    );
                                    ?>

                                    <?php if ( ! empty( $practice_areas ) ) : ?>
                                        <ul class="mt-4 list-none space-y-2 p-0 text-base uppercase tracking-wide md:text-xl">
                                            <?php foreach ( $practice_areas as $area ) : ?>
                                                <?php
                                                $title = get_field( 'practice_area_title', $area->ID );
                                                if ( empty( $title ) ) {
                                                    $title = get_the_title( $area->ID );
                                                }
                                                ?>
                                                <li><?php echo esc_html( $title ); ?></li>
                                            <?php endforeach; ?>
                                            <?php wp_reset_postdata(); ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php ++$cat_index; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </section>

    <?php get_template_part( 'template-parts/content/content', 'sa-map' ); ?>

<?php
get_footer();