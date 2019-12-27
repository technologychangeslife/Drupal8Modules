# GE Marketo Form Implementation

## Add extra attributes

```

/**
 * Implements hook_preprocess_HOOK().
 */
function my_module_preprocess_marketo_form__gated(&$variables) {
  // Add gated attributes.
  $variables['#attached']['drupalSettings']
  ['geMarketoForm']['marketo']['gatedUrl'] =
  $variables['data']['gatedUrl'];
  $gatedDataAttributes = [
    'data-date-created' => $variables['data']['dateCreated'],
    'data-content-type' => $variables['data']['contentType'],
    'data-tracking-key' => $variables['data']['trackingKey'],
    'data-kapost-id' => $variables['data']['kapostId'],
    'data-marketo-gated-url' => $variables['data']['gatedUrl'],
    'gatedurl' => $variables['data']['fullGatedUrl'],
    'data-legacy-gatedid' => $variables['data']['legacyGatedId'],
  ];
  $variables['#attached']['drupalSettings']
  ['geMarketoForm']['marketo']['dataAttributes'] =
  array_merge($variables['#attached']['drupalSettings']
  ['geMarketoForm']['marketo']['dataAttributes'], $gatedDataAttributes);
}

```
