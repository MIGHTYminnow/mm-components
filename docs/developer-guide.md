# Mm Components Developer Guide

This guide contains information on extending and customizing Mm Components.

## General

#### Enabling Specific Components

Not every site needs every component. We can easily enable only specific components using the `mm_components_active_components` filter:

```php
add_filter( 'mm_components_active_components', 'prefix_enable_specific_components' );
/**
 * Only enable specific components.
 */
function prefix_enable_specific_components( $components ) {

	// Turn off any components we don't want.
	unset( $components['countdown'] );
	unset( $components['hero-banner'] );
	// etc...

	return $components;
}
```

## Components

#### Posts

This component is all about making a query and displaying the results.

The approach taken is inspired by the Genesis framework and consists of several primary hooks that are looped over, with all output except the wrapper div coming from callback functions attached to the primary hooks. This allows for easily moving around any part of the default output and also for including any custom output in any position relative to the default content. All of the default content can also be overridden or removed entirely, making this a very flexible framework for creating any kind of custom template.

It all starts with the `mm_posts_register_hooks` action. This is where all hooks that will output custom content and any modifications to the order and on/off status of the default output should be registered. It's important to register these things _only_ on this hook, as every time one full run through of the shortcode happens the primary hooks are reset to allow for multiple [mm_posts] shortcodes on the same page.

By default only the **post title** and **excerpt** are included in the output, but there are params on the shortcode that optionally include in the **featured image**, **post info**, and **post meta**. All of the markup that is output is designed to match a standard WordPress archive page, so any CSS that applies to entries should also apply to the output of [mm_posts].

All of the hooks you can use for output including `mm_posts_register_hooks` get at least two arguments passed to them, the global post object for the page the shortcode is being displayed on ( `$context` ) and the array of attributes specified on the shortcode ( `$atts` ). The three primary hooks inside the loop `mm_posts_header`, `mm_posts_content`, and `mm_posts_footer` also get the current post object passed to them. These arguments can be used to restrict any modifications to only apply in certain situations or to certain posts, which is important because you probably don't want to ever make global modifications to the output of all instances of [mm_posts].

Let's take a look at a quick example:

```php
add_action( 'mm_posts_register_hooks', 'prefix_mm_posts_customize_hooks', 10, 2 );
/**
 * Customize the output of [mm_posts].
 */
function prefix_mm_posts_customize_hooks( $context, $atts ) {

	// Don't output the excerpt if we're displaying the post type 'movie'.
	if ( 'movie' == $atts['post_type'] ) {
		remove_action( 'mm_posts_content', 'mm_posts_output_post_content' );
	}

	// Move the output of the post header below the post content if we're on page 13.
	if ( 231 == $context->ID ) {
		remove_action( 'mm_posts_header', 'mm_posts_output_post_header' );
		add_action( 'mm_posts_content', 'mm_posts_output_post_header', 11 );
	}
}
```

Neat, but with the default output being so minimal it's more likely that we want to include some custom output. Let's say we've got a custom post type 'store' and we've saved custom fields for 'address' and 'phone_number', and we want to display the address below the excerpt and the phone number below the address. First we'll define reusable functions to output the fields we want, then we'll attach these to the appropriate hooks:

```php
add_action( 'mm_posts_register_hooks', 'prefix_mm_posts_custom_store_output', 10, 2 );
/**
 * Customize the output of [mm_posts] to include an address and phone number if the post type is 'store'.
 */
function prefix_mm_posts_custom_store_output( $context, $atts ) {

	if ( 'store' == $atts['post_type'] ) {
		add_action( 'mm_posts_content', 'prefix_output_post_address', 15, 3 );
		add_action( 'mm_posts_footer', 'prefix_output_post_phone', 12, 3 );
	}
}

/**
 * Output the address for the passed in post if it's there.
 */
function prefix_output_post_address( $post, $context, $atts ) {

	$address = get_post_meta( $post->ID, 'address', true );

	if ( $address ) {
		echo '<div class="entry-address">' . esc_html( $address ) . '</div>';
	}
}

/**
 * Output the phone number for the passed in post if it's there.
 */
function prefix_output_post_phone( $post, $context, $atts ) {

	$phone = get_post_meta( $post->ID, 'phone_number', true );

	if ( $phone ) {
		echo '<div class="entry-phone-number">' . esc_html( $phone ) . '</div>';
	}
}
```
