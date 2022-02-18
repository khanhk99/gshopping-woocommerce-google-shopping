<?php
if (!defined('ABSPATH')) {
    die;
}

function form_html($attributes, $merchant_config, $prefix)
{
    $mapping_data_arr = $merchant_config->product_mapping_merchant("");
    $link_google_support = 'https://support.google.com/merchants/answer/';
    $locale = explode('_', get_locale());
    $locale = $locale[0];

    foreach ($attributes as $key => $attribute) {
        $name = $prefix . $key;
        $placeholder = $attribute['placeholder'];
        if (strpos($name, '-') !== false) {
            $explodes = explode('-', $name, 2);
            if (array_key_exists($explodes[0], $mapping_data_arr)) {
                continue;
            }
        }

        if (array_key_exists($key, $mapping_data_arr)) {
            continue;
        }

        if (isset($mapping_data_arr[$key]['type']) && ($mapping_data_arr[$key]['type'] == 'multiple')) {
            continue;
        }

        $repeated = "";
        $multiple_select = "";
        if (isset($attribute['repeated']) && ($attribute['repeated'] === true)) {
            if (($attribute['level'] === 0) && ($attribute['type'] == 'select')) {
                $repeated = sprintf('<div class="pfvi_custom-product-special">%s</div>',
                    esc_html__('You can select multiple value', 'gshopping-wc-google-shopping'));
            } else {
                $repeated = sprintf('<div class="pfvi_custom-product-special">%s</div>',
                    esc_html__('To submit more than 1 value, separate each value with a comma ( , )', 'gshopping-wc-google-shopping'));
            }

            $multiple_select = 'multiple size=7';
        }

        if (($attribute['type'] === 'element') && (!isset($attribute['unit_fix']))) {
            if ($attribute['level'] === 0) {
                printf('<div class="pfvi_fields-custom-product pfvi_custom-product pfvi_custom-product-0">
                     <a href="%s" target="_blank" class="pfvi_none_underline pfvi_title">%s %s</a>',
                    esc_html($link_google_support . $attribute['support'] . '?ht=' . $locale),
                    esc_attr__($attribute['title'], 'gshopping-wc-google-shopping'),
                    $repeated);
                echo '<div class="pfvi_custom-product-padding">';
            } else {
                printf('<div class="pfvi_fields-custom-product pfvi_custom-product">
                                <div class="pfvi_title">%s %s</div>
                                <div class="pfvi_custom-product-padding">',
                    esc_attr__($attribute['title'], 'gshopping-wc-google-shopping'),
                    $repeated);
            }
            $name .= '-';
            //recursive
            form_html($attribute['element'], $merchant_config, $name);

            echo '</div>';
        } else {
            $value_config = $merchant_config->product_mapping_merchant($name);
            print_r($name);
            print_r($value_config);
            if (($attribute['level'] == 0) && isset($attribute['repeated']) && $attribute['repeated']) {
                unset($value_config['type']);

//					$value_config = $data_config->reconvert_repeated( $value_config );
                if (($attribute['type'] != 'select') && ($attribute['type'] != 'element')) {
                    $value_config = implode(",", $value_config);
                }
            }

            if ($attribute['level'] === 0) {
                printf('<div class="pfvi_field-custom-product pfvi_custom-product pfvi_custom-product-0">
                    <a href="%s" target="_blank" class="pfvi_none_underline">%s %s</a>',
                    esc_html($link_google_support . $attribute['support'] . '?ht=' . $locale),
                    esc_attr__($attribute['title'], 'gshopping-wc-google-shopping'),
                    $repeated);
            } else {
                printf('<div class="pfvi_field-custom-product pfvi_custom-product">
                    <div class="pfvi_title">%s %s</div>',
                    esc_attr__($attribute['title'], 'gshopping-wc-google-shopping'),
                    $repeated);
            }

            echo '<div class="pfvi_custom-product-value">';
            if (isset($attribute['unit_fix'])) {
                if ($attribute['unit_fix'] == "currency") {
                    $currency = get_woocommerce_currency();
                    printf('<input type="number" step="any" name="%s" value="%s" min="0" placeholder="%s"> (%s)
									<input name="%s" value="%s" hidden>',
                        esc_attr($name . "-value"),
                        esc_attr($merchant_config->product_mapping_merchant($name . "-value")),
                        esc_attr($placeholder),
                        $currency,
                        esc_attr($name . "-currency"),
                        $currency
                    );
                } elseif ($attribute['unit'] == "dimension") {
                    $unit_config = $merchant_config->convert_unit("", $merchant_config->dimension_unit());
                    $unit = $unit_config['unit'];
                    printf('<input type="number" step="any" name="%s" value="%s" min="0" placeholder="%s"> (%s)
									<input name="%s" value="%s" hidden>',
                        esc_attr($name . "-value"),
                        esc_attr($merchant_config->product_mapping_merchant($name . "-value")),
                        esc_attr($placeholder),
                        $unit,
                        esc_attr($name . "-unit"),
                        $unit
                    );
                } elseif ($attribute['unit'] == "weight") {
                    $unit_config = $merchant_config->convert_unit("", $merchant_config->weight_unit());
                    $unit = $unit_config['unit'];
                    printf('<input type="number" step="any" name="%s" value="%s" min="0" placeholder="%s"> (%s)
									<input name="%s" value="%s" hidden>',
                        esc_attr($name . "-value"),
                        esc_attr($merchant_config->product_mapping_merchant($name . "-value")),
                        esc_attr($placeholder),
                        $unit,
                        esc_attr($name . "-unit"),
                        $unit
                    );
                }
            } elseif ($attribute['type'] === "textarea") {
                printf('<textarea maxlength="%u" name="%s">%s</textarea>',
                    esc_attr($attribute['max']),
                    esc_attr($name),
                    $value_config);

            } elseif ($attribute['type'] === "select") {
                printf('<select name="%s%s" %s><option value="">%s</option>',
                    esc_attr($name),
                    (($attribute['level'] === 0) && isset($attribute['repeated']) && ($attribute['repeated'] === true)) ? "[]" : "",
                    esc_attr($multiple_select),
                    esc_html(' '));

                foreach ($attribute['option'] as $key_option => $option) {
                    printf('<option value="%s" %s %s>%s</option>',
                        $key_option,
                        (isset($mapping_data_arr[$key]) && ($value_config != $key_option)) ? 'disabled' : '',
                        (($value_config == $key_option) || (is_array($value_config) && in_array($key_option, $value_config))) ? 'selected' : '',
                        esc_attr__($option, 'gshopping-wc-google-shopping'));
                }
                printf('</select>');
            } elseif ($attribute['type'] === "float") {
                printf('<input type="number" step="any" name="%s" value="%s" min="0" placeholder="%s">',
                    esc_attr($name),
                    esc_attr($value_config),
                    esc_attr($placeholder)
                );
            } elseif ($attribute['type'] === "datetime") {
                printf('<input type="datetime-local" name="%s" value="%s" placeholder="%s">',
                    esc_attr($name),
                    esc_attr($value_config),
                    esc_attr($placeholder)
                );
            } else {
                printf('<input type="%s" name="%s" value="%s" min="0" placeholder="%s">',
                    esc_attr($attribute['type']),
                    esc_attr($name),
                    esc_attr($value_config),
                    esc_attr($placeholder)
                );
            }

            echo '</div>';
        }

        echo '</div>';
    }
}

?>

<?php form_html($attributes, $merchant_config, '');
