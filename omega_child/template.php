<?php

/**
 * @file
 * Template overrides as well as (pre-)process and alter hooks for the.
 */

/**
 * Implements hook_theme().
 */
function omega_child_theme() {
  // Possible variabsl '($existing, $type, $theme, $path)'.
  return array(
    'omegachild_date_ical_icon' => array(
      'variables' => array('url' => NULL, 'tooltip' => NULL),
    ),
  );
}

/**
 * Theme function for the ical icon used by attached iCal feed.
 *
 * Available variables are:
 * $variables['tooltip'] - The tooltip to be used for the ican feed icon.
 * $variables['url'] - The url to the actual iCal feed.
 * $variables['view'] - The view object from which the iCal feed is being
 *   built (useful for contextual information).
 *
 * @param array $variables
 *   Not sure.
 *
 * @return string
 *   Rendered ical link.
 */
function omega_child_date_ical_icon(array $variables) {
  if (empty($variables['tooltip'])) {
    $variables['tooltip'] = t('Add this event to my calendar');
  }
  $variables['path'] = drupal_get_path('module', 'date_ical');
  $variables['path'] .= '/images/ical-feed-icon-34x14.png';
  $variables['alt'] = $variables['tooltip'];
  $variables['title'] = $variables['tooltip'];
  if ($image = theme('image', $variables)) {
    $ical_link = '<a href="' . $variables['url'] . '" class="ical-icon">';
    $ical_link .= '<i class="icon-calendar">&nbsp;</i></a>';
  }
  else {
    $ical_link = '<a href="' . $variables['url'] . '" class="ical-icon">';
    $ical_link .= "{$variables['tooltip']}</a>";
  }
  return $ical_link;
}

/**
 * Render rss link.
 */
function omega_child_feed_icon($variables) {

  $text = t('Subscribe to @feed-title', array(
    '@feed-title' => $variables['title'],
    )
  );
  $rss_link = l('<i class="icon-rss"></i>',
    $variables['url'],
    array(
      'html' => TRUE,
      'attributes' => array(
        'class' => array('feed-icon'),
        'title' => $text,
        'target' => '_blank',
      ),
    ));

  return $rss_link;

}


/**
 * Implements form_alter().
 *
 * @return array
 *   Altered form.
 */
function omega_child_form_alter(&$form, &$form_state, $form_id) {
  // Overiding search page action / URL.
  

  return $form;
}

/**
 * Looks up the node titles to display in form.
 *
 * @param string $type
 *   Id of type.
 * @param int $filterby
 *   Raw op id to filter by.
 * @param int $filtervalue
 *   Nodeid or other reference.
 * @param string $con_type
 *   Type of entity.
 *
 * @return string
 *   Rendered title.
 */
function _omegachild_node_title($type, $filterby, $filtervalue, $con_type) {
  $nodesentity = new EntityFieldQuery();
  $output = NULL;
  $result = $nodesentity->entityCondition('entity_type', 'node', '=')
    ->propertyCondition('type', $type, '=');

  switch ($con_type) :
    case 'field':
      $result->fieldCondition($filterby, 'value', $filtervalue, '=');
      break;

    case 'entity':
      $result->fieldCondition($filterby, 'target_id', $filtervalue, '=');
      break;

    default:
  endswitch;

  $result->execute();

  if (isset($result->ordered_results)) {
    foreach ($result->ordered_results as $noded) {
      $node = node_load($noded->entity_id);
      $output[$node->title] = $node->title;
    }
  }
  return $output;
}


/**
 * Implements hook_facet_items_alter().
 */
function omega_child_facet_items_alter(&$build, &$settings) {
  if ($settings->facet == "field_countries") {
    foreach ($build as $key => $item) {
      if ($key == '!') {
        $build[$key]["#markup"] = "Not Assigned";
      }
      else {
        $build[$key]["#markup"] = country_load($item["#markup"])->name;
      }
    }
  }
  return $build;
}

/**
 * Hook_views_pre_render() intercept view array with custom settings.
 */
function omega_child_views_pre_render(&$view) {

  if ($view->name == 'operation_files') {
    $show_link = $view->display_handler->options['pane_conf']['arguments']['omega_child_view_all_files'];
    $group_results = $view->display_handler->options['pane_conf']['arguments']['omega_child_group_results'];
    if ($show_link === 1) {
      $context_nid = $view->args[0];
      $search_url = "search";
      $search_query = '?f[0]=field_raw_op_id:' . $context_nid;
      $link_text = 'Documents';
      // tid_1 = field_source, tid_2 = field_doument_type
      if ($view->exposed_input['tid_1'] != 'All'
        && $view->exposed_input['tid_2'] === 'All'
      ) {
        $search_query .= '&f[1]=field_source:' . $view->exposed_input['tid_1'];
        $term = taxonomy_term_load($view->exposed_input['tid_1']);
        $link_text = $term->name . ' files';
      }
      elseif ($view->exposed_input['tid_1'] === 'All'
        && $view->exposed_input['tid_2'] != 'All'
      ) {
        $search_query .= '&f[1]=field_document_type:' . $view->exposed_input['tid_2'];
        $term = taxonomy_term_load($view->exposed_input['tid_2']);
        $link_text = $term->name;
      }
      elseif ($view->exposed_input['tid_1'] != 'All'
        && $view->exposed_input['tid_2'] != 'All'
      ) {
        $search_query .= '&f[1]=field_document_type:' . $view->exposed_input['tid_2'];
        $search_query .= '&f[2]=field_source:' . $view->exposed_input['tid_1'];
        $term = taxonomy_term_load($view->exposed_input['tid_1']);
        $link_text = $term->name . ' Files';
      }

      // Could not get the below to work, but would be required for passing.
      // W3C Validation.
      // $options = array(
      // 'query' => array('destination' => $search_query),
      // );
      // $encoded_path = url($search_url, $options);
      $button_link = '<a href="/' . $search_url . $search_query . '"';
      $button_link .= ' class="button"';
      $button_link .= ' target="_blank">';
      $button_link .= 'See all ' . $link_text;
      $button_link .= '</a>';

      $view->footer['area']->options['content'] = $button_link;
    }
    else {
      unset($view->footer['area']);
    }

    if ($group_results === 1) {
      $view->style_plugin->options['grouping']['0']['rendered'] = 1;
      $view->style_plugin->options['grouping']['0']['rendered_strip'] = 1;
      $view->style_plugin->options['grouping']['0']['field'] = "type";
    }
    else {
      $view->style_plugin->options['grouping']['0']['rendered'] = 0;
      $view->style_plugin->options['grouping']['0']['rendered_strip'] = 0;
      $view->style_plugin->options['grouping']['0']['field'] = "-none-";
    }
  }
}
