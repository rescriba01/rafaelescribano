<?php
/**
 * Block Styles Registration
 *
 * @package re
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block styles
 */
function re_register_block_styles() {
    // Core Details Block Styles
    register_block_style(
        'core/details',
        array(
            'name'         => 'arrow-icon-details',
            'label'        => __('Arrow icon', 're'),
            'inline_style' => '
            .is-style-arrow-icon-details {
                padding-block: var(--wp--preset--spacing--30);
            }
            .is-style-arrow-icon-details summary {
                list-style-type: "\2193\00a0\00a0\00a0";
            }
            .is-style-arrow-icon-details[open]>summary {
                list-style-type: "\2192\00a0\00a0\00a0";
            }',
        )
    );

    // Core Heading Block Styles
    register_block_style(
        'core/heading',
        array(
            'name'         => 'display',
            'label'        => __('Display', 're'),
            'inline_style' => '.is-style-display { font-size: var(--wp--preset--font-size--display) !important; font-weight: 500 !important;}'
        )
    );

    // Core List Block Styles
    register_block_style(
        'core/list',
        array(
            'name'         => 'checkmark-list',
            'label'        => __('Checkmark', 're'),
            'inline_style' => '
            ul.is-style-checkmark-list {
                list-style-type: "\2713";
            }
            ul.is-style-checkmark-list li {
                padding-inline-start: 1ch;
            }',
        )
    );
}
add_action('init', 're_register_block_styles'); 