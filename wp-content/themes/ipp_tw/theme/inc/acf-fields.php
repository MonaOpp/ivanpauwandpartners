<?php
/**
 * Register ACF field groups.
 *
 * @package ipp_tw
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group(
	array(
		'key'      => 'group_cta_banner',
		'title'    => 'CTA Banner',
		'fields'   => array(
			array(
				'key'           => 'field_cta_background',
				'label'         => 'CTA Background Colour',
				'name'          => 'cta_background',
				'type'          => 'select',
				'choices'       => array(
					'dark' => 'Dark (#18273A)',
					'gold' => 'Gold (#AA7040)',
				),
				'default_value' => 'dark',
				'return_format' => 'value',
			),
			array(
				'key'           => 'field_cta_text',
				'label'         => 'CTA Text',
				'name'          => 'cta_text',
				'type'          => 'wysiwyg',
				'default_value' => 'Get in touch with us today',
				'tabs'          => 'visual',
				'toolbar'       => 'basic',
				'media_upload'  => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		),
		'position' => 'side',
	)
);
