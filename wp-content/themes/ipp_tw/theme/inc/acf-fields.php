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
		'key'      => 'group_download_fields',
		'title'    => 'Download Fields',
		'fields'   => array(
			array(
				'key'   => 'field_download_name',
				'label' => 'Download Name',
				'name'  => 'download_name',
				'type'  => 'text',
				'instructions' => 'Display name for this download.',
				'required' => 1,
			),
			array(
				'key'           => 'field_download_file',
				'label'         => 'Download File',
				'name'          => 'download_file',
				'type'          => 'file',
				'return_format' => 'url',
				'mime_types'    => 'pdf',
				'instructions'  => 'Upload the PDF file.',
				'required'      => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'download',
				),
			),
		),
		'position' => 'normal',
		'style'    => 'default',
	)
);

acf_add_local_field_group(
	array(
		'key'      => 'group_contact_page',
		'title'    => 'Contact Page Fields',
		'fields'   => array(
			array(
				'key'   => 'field_contact_heading',
				'label' => 'Section Heading',
				'name'  => 'contact_heading',
				'type'  => 'text',
				'default_value' => 'GET IN TOUCH',
			),
			array(
				'key'   => 'field_contact_description',
				'label' => 'Description',
				'name'  => 'contact_description',
				'type'  => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
				'instructions' => 'Introductory text shown below the heading.',
			),
			array(
				'key'   => 'field_contact_phone',
				'label' => 'Phone Number',
				'name'  => 'contact_phone',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_contact_email',
				'label' => 'Email Address',
				'name'  => 'contact_email',
				'type'  => 'email',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'page',
					'operator' => '==',
					'value'    => '16',
				),
			),
		),
		'position' => 'normal',
		'style'    => 'default',
	)
);
