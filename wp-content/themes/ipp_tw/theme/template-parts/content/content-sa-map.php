<?php
/**
 * Interactive South Africa province map (SVG-based).
 *
 * Inline SVG map with hover highlights and map pins.
 * Click a province to lock the selection and show flagship data in the sidebar.
 *
 * @package ipp_tw
 */

$upload_dir = wp_get_upload_dir();
$bg_image   = $upload_dir['baseurl'] . '/2026/04/Province-backgroud.png';

$provinces = array(
	'limpopo'       => 'Limpopo',
	'gauteng'       => 'Gauteng',
	'mpumalanga'    => 'Mpumalanga',
	'north-west'    => 'North West',
	'free-state'    => 'Free State',
	'kwazulu-natal' => 'KwaZulu-Natal',
	'nothern-cape'  => 'Northern Cape',
	'western-cape'  => 'Western Cape',
	'eastern-cape'  => 'Eastern Cape',
);
?>

<style>
	#sa-map { width: 100%; height: auto; display: block; position: relative; z-index: 1; }
	.province-path { cursor: pointer; transition: fill 0.3s ease; }

	/* ── Per-province hover & active colours ───────────────── */
	.province-path[data-province="limpopo"]:hover,
	.province-path[data-province="limpopo"].province-active       { fill: #C7976E; }
	.province-path[data-province="gauteng"]:hover,
	.province-path[data-province="gauteng"].province-active       { fill: #6D99C3; }
	.province-path[data-province="mpumalanga"]:hover,
	.province-path[data-province="mpumalanga"].province-active    { fill: #C7976E; }
	.province-path[data-province="north-west"]:hover,
	.province-path[data-province="north-west"].province-active    { fill: #6D99C3; }
	.province-path[data-province="free-state"]:hover,
	.province-path[data-province="free-state"].province-active    { fill: #C7976E; }
	.province-path[data-province="kwazulu-natal"]:hover,
	.province-path[data-province="kwazulu-natal"].province-active { fill: #6D99C3; }
	.province-path[data-province="nothern-cape"]:hover,
	.province-path[data-province="nothern-cape"].province-active  { fill: #C7976E; }
	.province-path[data-province="western-cape"]:hover,
	.province-path[data-province="western-cape"].province-active  { fill: #C7976E; }
	.province-path[data-province="eastern-cape"]:hover,
	.province-path[data-province="eastern-cape"].province-active  { fill: #6D99C3; }
	.map-pin { pointer-events: none; opacity: 0; transition: opacity 0.3s ease; }
	.map-pin.visible { opacity: 1; }
</style>

<section
	class="w-full bg-white bg-right bg-no-repeat bg-contain py-16"
	style="background-image: url('<?php echo esc_url( $bg_image ); ?>');"
>
	<div class="layout-wrapper relative mx-auto px-6">
		<div class="flex flex-col gap-10 lg:flex-row lg:items-start">

			<!-- Left: Province info sidebar -->
			<div class="w-full lg:w-1/2" id="sa-map-sidebar">

				<!-- Province button list -->
				

				<!-- Default prompt -->
				<div id="sa-map-default" class="text-center text-gray-400">
					<p class="text-lg">Click a province to view flagship projects.</p>
				</div>

				<!-- Province detail (hidden until a province is selected) -->
				<div id="sa-map-info" class="hidden">
					<h2 class="text-left text-[50px] md:text-7xl font-bold">OUR provincial <br>success</h2>
					<h3 id="sa-map-province-name" class="mb-4 text-2xl font-bold text-[#0D1622] text-left"></h3>
					<ul id="sa-map-flagship-list" class="list-none p-0 flex flex-wrap gap-4"></ul>
					<div id="sa-map-flagship-btn" class="hidden" style="margin-top:16px;"></div>
					<p id="sa-map-no-projects" class="hidden text-gray-400">No flagship projects in this province yet.</p>
				</div>

			</div>

			<!-- Right: Interactive SVG map -->
			<div class="w-full lg:w-1/2" id="sa-map-container">
				<?php
				$svg_path = get_template_directory() . '/images/sa-map.svg';
				if ( file_exists( $svg_path ) ) {
					// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
					echo file_get_contents( $svg_path );
				}
				?>
			</div>

		</div>
	</div>
</section>
