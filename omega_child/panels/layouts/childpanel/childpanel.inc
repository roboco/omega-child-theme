<?php

/**
 * @file
 * Defines an asymetric Mondrian inspired panels layout.
 */

// Plugin definition.
$plugin = array(
  'title' => t('OmegaChild Panel'),
  'icon' => 'preview.png',
  'category' => t('Omega content layouts'),
  'theme' => 'childpanel',
  'css' => '../../../css/layouts/childpanel/childpanel.layout.css',
  'regions' => array(
  	'top' => t('Top'),
  	'fullpage' => t('Full Page Spread'),
    'main' => t('Main'),
    'secondary' => t('Secondary'),
    'onethird' => t('First Third'),
    'twothird' => t('Second Third'),
    'threethird' => t('Third Third'),
    'onespan' => t('One Span'),
    'twospan' => t('Two Span'),
    'threespan' => t('Three Span'),
    '2ndtwospan' => t('2nd Two Span'),
    '2ndonespan' => t('2nd One Span'),
    'fourthird' => t('Fourth Third'),
    'fifththird' => t('Fifth Third'),
    'sixththird' => t('Sixth Third'),
    'totalspan' => t('Total Span'),  
    'split1' => t('Split One'),
    'split2' => t('Split Two'),
    'split3' => t('Split three'),
    'split4' => t('Split four'),
	'bottom' => t('Bottom'),

  ),
);

/**
 * Implements hook_preprocess_mondrian().
 */
function template_preprocess_childpanel(&$variables) {
  $variables['attributes_array']['class'][] = 'panel-display';
  $variables['attributes_array']['class'][] = 'panel-display--childpanel';

  foreach($variables['content'] as $name => $item) {
    $variables['region_attributes_array'][$name]['class'][] = 'childpanel-region';
    $variables['region_attributes_array'][$name]['class'][] = 'childpanel-region--' . drupal_clean_css_identifier($name);
  }
}
