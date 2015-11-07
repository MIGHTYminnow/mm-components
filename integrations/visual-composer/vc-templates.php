<?php
/**
 * Mm Components Visual Composer Templates.
 *
 * @since 1.0.0
 *
 * @package mm-components
 */

add_filter( 'vc_load_default_templates', 'mm_vc_register_custom_templates', 10, 1 );
/**
 * Register our custom templates.
 *
 * @since   1.0.0
 *
 * @param   array  $templates  The default templates.
 *
 * @return  array              The filtered templates.
 */
function mm_vc_register_custom_templates( $templates ) {

	// Demo template for all components.
	$all                 = array();
	$all['name']         = __( 'Mm Components - All', 'mm-components' );
	$all['weight']       = 99;
	$all['image_path']   = MM_COMPONENTS_ASSETS_URL . 'template_icon.png';
	$all['custom_class'] = 'mm_components_all_template';
	$all['content']      = <<<CONTENT
		[vc_row][vc_column][vc_text_separator title="Blockquote"][mm_blockquote image_id="56" quote="This is a blockquote. This blockquote should have an image and a citation." citation="Smart Person"][mm_blockquote quote="This is a blockquote. This blockquote should have no image but should have a citation." citation="Smart Person"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Button"][mm_button link="url:http%3A%2F%2Fexample.com|title:Sample%20Page|" size="large" alignment="center"]

Button Text

[/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Countdown"][mm_countdown date="2015-12-25" time="18:00:00" timezone="GMT-0800"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Custom Heading"][mm_custom_heading font_size="36px" text_transform="uppercase" text_align="center"]

This is a custom heading

[/mm_custom_heading][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Expandable Content"][mm_expandable_content link_style="button" link_text="Click to Expand" link_alignment="center" fade="1"][mm_polaroid title="Just a Polaroid here" image="57" link_text="Link Text" link="url:http%3A%2F%2Fexample.com||"]

This is the text for the polaroid.

[/mm_polaroid][/mm_expandable_content][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Hero Banner"][mm_hero_banner overlay_color="#000" overlay_opacity="0.2" text_position="text-center" background_image="59" background_position="cover" heading="Hero Banner Heading" button_text="Button Text" secondary_cta="VGhpcyUyMGlzJTIwYSUyMHNlY29uZGFyeSUyMGNhbGwlMjB0byUyMGFjdGlvbiUyMG9uJTIwdGhlJTIwSGVybyUyMEJhbm5lci4="]

This is a hero banner. It should have a black overlay with an opacity of 0.2, and the background image should always cover the whole banner area.

[/mm_hero_banner][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Highlight Box"][mm_highlight_box heading_text="Highlight Box Heading" paragraph_text="Highlight box paragraph text. Highlight box paragraph text." link_text="Link Text" link="url:http%3A%2F%2Fexample.com||"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Icon Box"][mm_icon_box icon_fontawesome="fa fa-bicycle" heading_text="Icon Box Heading" link="url:http%3A%2F%2Fexample.com|title:Link%20Text|" link_text="Link Text"]Icon box text. Icon box text. Icon box text. Icon box text. Icon box text. Icon box text.

Icon box text. Icon box text. Icon box text. Icon box text.[/mm_icon_box][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Image Grid"][mm_image_grid style="style-full-image" max_in_row="2" title="Image Grid Title" el_class="extra-class"][mm_image_grid_image title="Image Grid Image #1" subtitle="Just a subtitle" image="57" link="url:http%3A%2F%2Fexample.com||"][mm_image_grid_image title="Image Grid Image #2" subtitle="Just another subtitle" image="69" link="url:http%3A%2F%2Fexample.com||" style=""][/mm_image_grid][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Logo Strip"][mm_logo_strip title="Just some logos here" title_alignment="center" images="52,53,54" image_size="thumbnail"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Polaroid"][mm_polaroid title="Polaroid" image="57" link_text="Link Text" link="url:http%3A%2F%2Fexample.com||"]

Just some text content for the polaroid.

[/mm_polaroid][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Polaroid 2"][mm_polaroid_2 title="Polaroid 2" image="57" caption="Just a polaroid caption here" caption_color="dark-text" link="url:http%3A%2F%2Fexample.com||"][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Restricted Content"][mm_restricted_content][vc_column_text]This is the content that all logged in users can view.[/vc_column_text][/mm_restricted_content][/vc_column][/vc_row][vc_row][vc_column][mm_posts post_type="post" show_featured_image="1" show_post_info="1" show_post_meta="1"][/vc_column][/vc_row]
CONTENT;

	// Demo template for Mm Button.
	$button                 = array();
	$button['name']         = __( 'Mm Components - Button', 'mm-components' );
	$button['weight']       = 99;
	$button['image_path']   = MM_COMPONENTS_ASSETS_URL . 'template_icon.png';
	$button['custom_class'] = 'mm_components_button_template';
	$button['content']      = <<<CONTENT
		[vc_row][vc_column][mm_custom_heading text_align="center"]Button Style: Default[/mm_custom_heading][vc_empty_space][mm_button link="url:%23||" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Corner Styles" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Pointed</p>
[/vc_column_text][mm_button link="url:%23||" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Rounded</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Pill</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="pill" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Colors" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" color="light" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" color="medium" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" color="dark" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Sizes" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" size="small" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][mm_button link="url:%23||" corner_style="rounded" size="large" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="80px"][mm_custom_heading text_align="center"]Button Style: Ghost[/mm_custom_heading][vc_empty_space][mm_button link="url:%23||" style="ghost" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Border Styles" border_width="2"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Thin</p>
[/vc_column_text][mm_button link="url:%23||" style="ghost" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Thick</p>
[/vc_column_text][mm_button link="url:%23||" style="ghost" border_weight="thick" corner_style="rounded" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Colors" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_button link="url:%23||" style="ghost" border_weight="thick" corner_style="rounded" color="light" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_button link="url:%23||" style="ghost" border_weight="thick" corner_style="rounded" color="medium" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_button link="url:%23||" style="ghost" border_weight="thick" corner_style="rounded" color="dark" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="80px"][mm_custom_heading text_align="center"]Button Style: Solid to Ghost[/mm_custom_heading][vc_empty_space][mm_button link="url:%23||" style="solid-to-ghost" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Border Styles" border_width="2"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Thin</p>
[/vc_column_text][mm_button link="url:%23||" style="solid-to-ghost" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Thick</p>
[/vc_column_text][mm_button link="url:%23||" style="solid-to-ghost" border_weight="thick" corner_style="rounded" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Colors" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_button link="url:%23||" style="solid-to-ghost" corner_style="rounded" color="light" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_button link="url:%23||" style="solid-to-ghost" corner_style="rounded" color="medium" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_button link="url:%23||" style="solid-to-ghost" corner_style="rounded" color="dark" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="80px"][mm_custom_heading text_align="center"]Button Style: 3D[/mm_custom_heading][vc_empty_space][mm_button link="url:%23||" style="three-d" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Colors" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_button link="url:%23||" style="three-d" corner_style="rounded" color="light" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_button link="url:%23||" style="three-d" corner_style="rounded" color="medium" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_button link="url:%23||" style="three-d" corner_style="rounded" color="dark" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="80px"][mm_custom_heading text_align="center"]Button Style: Gradient[/mm_custom_heading][vc_empty_space][mm_button link="url:%23||" style="gradient" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column][/vc_row][vc_row][vc_column][vc_text_separator title="Colors" border_width="2"][vc_row_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_button link="url:%23||" style="gradient" corner_style="rounded" color="light" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_button link="url:%23||" style="gradient" corner_style="rounded" color="medium" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_button link="url:%23||" style="gradient" corner_style="rounded" color="dark" alignment="center"]Juicy Click Target  [icon name="fighter-jet" class="" unprefixed_class=""][/mm_button][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="35px"][/vc_column][/vc_row]
CONTENT;

	// Demo template for Mm Social Icon.
	$social_icons                 = array();
	$social_icons['name']         = __( 'Mm Components - Social Icons', 'mm-components' );
	$socia_icons['weight']        = 99;
	$social_icons['image_path']   = MM_COMPONENTS_ASSETS_URL . 'template_icon.png';
	$social_icons['custom_class'] = 'mm_components_social_icons_template';
	$social_icons['content']      = <<<CONTENT
	[vc_row][vc_column][mm_custom_heading margin_bottom="32" text_align="center"]Social Icon Style: Default[/mm_custom_heading][mm_social_icons alignment="center" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_text_separator title="Colors"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Default</p>
[/vc_column_text][mm_social_icons alignment="center" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_social_icons alignment="center" color="medium" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_social_icons alignment="center" color="light" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_social_icons alignment="center" color="dark" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_empty_space][vc_column_text]
<p style="text-align: center;">Brand Colors</p>
[/vc_column_text][mm_social_icons alignment="center" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Sizes"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_social_icons alignment="center" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_social_icons alignment="center" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_empty_space][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][mm_social_icons alignment="center" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][/vc_column][/vc_row][vc_row][vc_column][mm_custom_heading margin_bottom="32" text_align="center"]Social Icon Style: Circle[/mm_custom_heading][mm_social_icons alignment="center" style="circle" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_text_separator title="Colors"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Default</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" color="medium" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" color="light" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" color="dark" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_column_text]
<p style="text-align: center;">Brand Colors</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Sizes"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_empty_space][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][mm_social_icons alignment="center" style="circle" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Ghost"][vc_row_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="circle" ghost="1" color="brand-colors" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="circle" ghost="1" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][mm_social_icons alignment="center" style="circle" ghost="1" color="brand-colors" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][/vc_column][/vc_row][vc_row][vc_column][mm_custom_heading margin_bottom="32" text_align="center"]Social Icon Style: Square[/mm_custom_heading][mm_social_icons alignment="center" style="square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_text_separator title="Colors"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Default</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" color="medium" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" color="light" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" color="dark" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_column_text]
<p style="text-align: center;">Brand Colors</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Sizes"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_empty_space][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][mm_social_icons alignment="center" style="square" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Ghost"][vc_row_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="square" ghost="1" color="brand-colors" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="square" ghost="1" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][mm_social_icons alignment="center" style="square" ghost="1" color="brand-colors" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][/vc_column][/vc_row][vc_row][vc_column][mm_custom_heading margin_bottom="32" text_align="center"]Social Icon Style: Rounded Square[/mm_custom_heading][mm_social_icons alignment="center" style="rounded-square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_text_separator title="Colors"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Default</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Medium</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" color="medium" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Light</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" color="light" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_column_text]
<p style="text-align: center;">Dark</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" color="dark" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_column_text]
<p style="text-align: center;">Brand Colors</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Sizes"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_social_icons alignment="center" style="rounded-square" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][vc_empty_space][mm_social_icons alignment="center" style="rounded-square" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][vc_empty_space][vc_text_separator title="Ghost"][vc_row_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="rounded-square" ghost="1" color="brand-colors" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][mm_social_icons alignment="center" style="rounded-square" ghost="1" color="brand-colors" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][mm_social_icons alignment="center" style="rounded-square" ghost="1" color="brand-colors" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][/vc_column][/vc_row][vc_row][vc_column][mm_custom_heading margin_bottom="32" text_align="center"]Social Icon Style: Custom Image[/mm_custom_heading][mm_social_icons icon_type="images" alignment="center" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#" facebook_image="" twitter_image="" instagram_image="" pinterest_image="" youtube_image=""][vc_empty_space][vc_text_separator title="Sizes"][vc_row_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Small</p>
[/vc_column_text][mm_social_icons icon_type="images" alignment="center" size="small" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]
<p style="text-align: center;">Normal</p>
[/vc_column_text][mm_social_icons icon_type="images" alignment="center" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][/vc_column_inner][/vc_row_inner][vc_empty_space][vc_column_text]
<p style="text-align: center;">Large</p>
[/vc_column_text][mm_social_icons icon_type="images" alignment="center" size="large" facebook_link="#" twitter_link="#" instagram_link="#" pinterest_link="#" youtube_link="#"][vc_empty_space][/vc_column][/vc_row]
CONTENT;

	// Demo template for restricted content.
	$restricted_content                 = array();
	$restricted_content['name']         = __( 'Mm Components - Restricted Content', 'mm-components' );
	$restricted_content['weight']       = 99;
	$restricted_content['image_path']   = MM_COMPONENTS_ASSETS_URL . 'template_icon.png';
	$restricted_content['custom_class'] = 'mm_components_restricted_content_template';
	$restricted_content['content']      = <<<CONTENT
	[vc_row][vc_column][vc_text_separator title="Logged in"][mm_restricted_content other_content="QWxsJTIwbG9nZ2VkJTIwaW4lMjB1c2VycyUyMGNhbiUyMHZpZXclMjB0aGlzJTIwY29udGVudC4=" logged_in="1"][vc_column_text]This is the content that all logged in users can view.[/vc_column_text][/mm_restricted_content][vc_empty_space][vc_text_separator title="Logged out"][mm_restricted_content specific_roles="0" roles="administrator" other_content="U29ycnklMkMlMjB5b3UlMjBtdXN0JTIwYmUlMjBsb2dnZWQlMjBpbiUyMHRvJTIwdmlldyUyMHRoaXMlMjBjb250ZW50Lg==" logged_in="1"][vc_column_text]Sorry, you must be logged in to view this content.[/vc_column_text][/mm_restricted_content][vc_empty_space][vc_text_separator title="Administrators"][mm_restricted_content specific_roles="0" roles="administrator" other_content="VGhpcyUyMGlzJTIwdGhlJTIwY29udGVudCUyMHRoYXQlMjBhZG1pbmlzdHJhdG9ycyUyMGNhbiUyMHZpZXcu" logged_in="1"][vc_column_text]This is the content that administrators can view.[/vc_column_text][/mm_restricted_content][/vc_column][vc_column][/vc_column][/vc_row
CONTENT;

	$templates[] = $all;
	$templates[] = $button;
	$templates[] = $social_icons;
	$templates[] = $restricted_content;

	return $templates;
}
