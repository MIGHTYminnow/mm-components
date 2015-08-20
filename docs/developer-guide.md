# Mm Components Developer Guide

This guide contains information on extending and customizing Mm Components.

## General

#### Enabling Specific Components

Not every site needs every component. We can easily enable only specific components like this:

```
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
