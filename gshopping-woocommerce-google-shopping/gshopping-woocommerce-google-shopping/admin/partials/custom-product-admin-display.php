<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function form_html( $attributes, $merchant_config, $prefix ) {
	$mapping_data_arr = $merchant_config->product_mapping_merchant( "" );
	$link_google_support = 'https://support.google.com/merchants/answer/';
	$locale              = explode( '_', get_locale() );
	$locale              = $locale[0];

	foreach ( $attributes as $key => $attribute ) {
		$name        = $prefix . $key;
		$placeholder = 'placeholder=' . $attribute['placeholder'];
		if ( strpos( $name, '-' ) !== false ) {
			$explodes = explode( '-', $name, 2 );
			if ( array_key_exists($explodes[0] , $mapping_data_arr) ) {
				continue;
			}
		}

		if ( array_key_exists($key, $mapping_data_arr) ) {
			continue;
		}

		if ( isset( $mapping_data_arr[ $key ]['type'] ) && ( $mapping_data_arr[ $key ]['type'] == 'multiple' ) ) {
			continue;
		}

		$required = ( $attribute['required'] === true ) ? '(required)' : '';

		if ( $attribute['type'] === 'element' ) {
			if ( $attribute['level'] === 0 ) {
				printf( '<div class="pfvi_fields-custom-product pfvi_custom-product pfvi_custom-product-0">
                     <a href="%s" target="_blank" class="pfvi_none_underline pfvi_title">%s %s</a>
                     <div class="pfvi_custom-product-padding">',
					esc_html( $link_google_support . $attribute['support'] . '?ht=' . $locale ),
					esc_attr__( $attribute['title'], 'gshopping-wc-google-shopping' ),
					$required );
			} else {
				printf( '<div class="pfvi_fields-custom-product pfvi_custom-product">
                                <div class="pfvi_title">%s %s</div>
                                <div class="pfvi_custom-product-padding">',
					esc_attr__( $attribute['title'], 'gshopping-wc-google-shopping' ),
					$required );
			}
			$name .= '-';
			//recursive
			form_html( $attribute['element'], $merchant_config, $name );

			echo '</div>';
		} else {
			if ( $attribute['level'] === 0 ) {
				printf( '<div class="pfvi_field-custom-product pfvi_custom-product pfvi_custom-product-0">
                    <a href="%s" target="_blank" class="pfvi_none_underline">%s %s</a>',
					esc_html( $link_google_support . $attribute['support'] . '?ht=' . $locale ),
					esc_attr__( $attribute['title'], 'gshopping-wc-google-shopping' ),
					$required );
			} else {
				printf( '<div class="pfvi_field-custom-product pfvi_custom-product">
                    <div class="pfvi_title">%s %s</div>',
					esc_attr__( $attribute['title'], 'gshopping-wc-google-shopping' ),
					$required );
			}

			echo '<div class="pfvi_custom-product-value">';
			if ( $attribute['type'] === "textarea" ) {
				printf( '<textarea maxlength="%u" name="%s">%s</textarea>',
					esc_attr( $attribute['max'] ),
					esc_attr( $name ),
					$merchant_config->product_mapping_merchant( $name ) );

			} else if ( $attribute['type'] === "select" ) {
				printf( '<select name="%s"><option value="">%s</option>',
					esc_attr( $name ),
					esc_html(' '));

				foreach ( $attribute['option'] as $key_option => $option ) {
					printf( '<option value="%s" %s %s>%s</option>',
						$key_option,
						( isset( $mapping_data_arr[ $key ] ) && ( $merchant_config->product_mapping_merchant( $name ) != $key_option ) ) ? 'disabled' : '',
						( $merchant_config->product_mapping_merchant( $name ) == $key_option ) ? 'selected' : '',
						esc_attr__( $option, 'gshopping-wc-google-shopping' ) );
				}
				printf( '</select>' );
			} elseif ( $attribute['type'] === "float" ) {
				printf( '<input type="number" step="any" name="%s" value="%s" min="0">',
					esc_attr( $name ),
					esc_attr( $merchant_config->product_mapping_merchant( $name ) ) );
			} elseif ( $attribute['type'] === "datetime" ) {
				printf( '<input type="datetime-local" name="%s" value="%s">',
					esc_attr( $name ),
					esc_attr( $merchant_config->product_mapping_merchant( $name ) ) );
			} else {
				printf( '<input type="%s" name="%s" value="%s">',
					esc_attr( $attribute['type'] ),
					esc_attr( $name ),
					esc_attr( $merchant_config->product_mapping_merchant( $name ) ) );
			}

			echo '</div>';
		}

		echo '</div>';
	}
}

?>

<?php form_html( $attributes, $merchant_config, '' );
