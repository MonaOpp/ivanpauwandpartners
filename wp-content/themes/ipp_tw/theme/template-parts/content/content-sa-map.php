<?php
/**
 * Interactive South Africa province map (PNG-based).
 *
 * Province names on the left; on hover the matching highlighted map PNG
 * is shown on the right, replacing the blank base map.
 *
 * @package ipp_tw
 */

$upload_dir = wp_get_upload_dir();
$base_url   = $upload_dir['baseurl'] . '/2026/04';

$provinces = array(
	'limpopo'       => array(
		'label' => 'Limpopo',
		'image' => $base_url . '/Limpopo.png',
	),
	'gauteng'       => array(
		'label' => 'Gauteng',
		'image' => $base_url . '/Gauteng.png',
	),
	'mpumalanga'    => array(
		'label' => 'Mpumalanga',
		'image' => $base_url . '/Mpumalanga.png',
	),
	'north-west'    => array(
		'label' => 'North West',
		'image' => $base_url . '/NorthWest.png',
	),
	'free-state'    => array(
		'label' => 'Free State',
		'image' => $base_url . '/FreeState.png',
	),
	'kwazulu-natal' => array(
		'label' => 'KwaZulu-Natal',
		'image' => $base_url . '/KwaZuluNatal.png',
	),
	'western-cape'  => array(
		'label' => 'Western Cape',
		'image' => $base_url . '/WesternCape.png',
	),
	'eastern-cape'  => array(
		'label' => 'Eastern Cape',
		'image' => $base_url . '/EasternCape.png',
	),

);

$blank_map = $base_url . '/Gauteng.png';
?>

<section class="w-full bg-[#132E47] py-16 text-white">
	<div class="layout-wrapper mx-auto px-6">
		<div class="flex flex-col items-center gap-10 md:flex-row md:items-center">

			<!-- Left: Province list -->
			<div class="w-full md:w-1/2">
				<ul class="list-none space-y-3 p-0">
					<?php foreach ( $provinces as $key => $prov ) : ?>
						<li>
							<button
								type="button"
								class="province-btn w-full cursor-pointer border-l-4 border-transparent px-4 py-2 text-left text-lg font-semibold uppercase tracking-wide text-white/60 transition-all duration-300 hover:border-[#AA7040] hover:text-white md:text-2xl"
								data-province="<?php echo esc_attr( $key ); ?>"
							>
								<?php echo esc_html( $prov['label'] ); ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Right: Map images -->
			<div class="relative w-full md:w-1/2">
				<!-- Blank base map (always visible underneath) -->
				<img
					src="<?php echo esc_url( $blank_map ); ?>"
					alt="South Africa map"
					class="w-full"
					id="base-map"
				/>

				<!-- Province highlight images (stacked, hidden by default) -->
				<?php foreach ( $provinces as $key => $prov ) : ?>
					<img
						src="<?php echo esc_url( $prov['image'] ); ?>"
						alt="<?php echo esc_attr( $prov['label'] ); ?>"
						class="pointer-events-none absolute inset-0 h-full w-full object-contain opacity-0 transition-opacity duration-300"
						data-province-img="<?php echo esc_attr( $key ); ?>"
					/>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
