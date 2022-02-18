<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function pfvi_select_html_country( $name, $arrs, $config ) {
	printf( '<select class="vi-ui dropdown search field-api-country" name="%s"><option value=""></option>', esc_attr( $name ) );
	foreach ( $arrs as $key => $value ) {
		$selected = ( $key === $config ) ? " selected" : "";
		printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), $selected, esc_html__( $value, 'gshopping-wc-google-shopping' ) );
	}
	printf( '</select>' );
}

function pfvi_gender_guide( $guides ) {
	if ( ! empty( $guides ) && is_array( $guides ) ) {
		foreach ( $guides as $index => $guide ) {
			$guide = wp_parse_args( $guide, [ 'text' => '', 'img' => '', 'desc' => '' ] );
			pfvi_guide_row( $guide['text'], $guide['img'], $guide['desc'], $index + 1 );
		}
	}
}

function pfvi_guide_row( $text, $image = '', $desc = '', $index = '' ) {
	?>
    <div class="title">
        <i class="dropdown icon"></i>
        <div>
			<?php echo wp_kses_post( $index . ' - ' . $text ) ?>
        </div>
    </div>
    <div class="content pfvi_guide-wrap-content">
		<?php
		if ( $desc ) {
			echo wp_kses_post( $desc );
		}
		if ( $image ) {
			printf( '<div class="pfvi_image-div"><div class="vi-ui segment"><img class="" src="%s"></div></div>', esc_attr( PFVI_ADMIN_IMAGES_URL . $image ) );
		}
		?>
    </div>
	<?php
}

$languages = "";
if ( class_exists( 'Polylang' ) ) {
	$languages = pll_languages_list();
} else {
	$languages = PFVI_LANGUAGE;
}

$attribute_required     = array();
$attribute_not_required = array();

foreach ( $attributes as $key => $attribute ) {
	if ( $attribute["required"] === true ) {
		$attribute_required[ $key ] = $attribute;
	} else {
		$attribute_not_required[ $key ] = $attribute;
	}
}

$attributes_taxonomies = wc_get_attribute_taxonomies();
?>

    <div class="wrap">
        <h2>Product Feed Config</h2>
        <div id="message" class="error <?php echo isset( $error ) ? '' : esc_attr( 'hidden' ); ?>">
            <p><?php echo isset( $error ) ? esc_html( $error ) : ""; ?></p>
        </div>
		<?php settings_errors( 'oauth-error' ); ?>
        <form class="vi-ui form" method="post" id="pfvi_config_form">
            <input type="hidden" name="pfvi_nonce" value="<?php echo wp_create_nonce( 'pfvi_save_config' ) ?>">
            <div class="vi-ui top attached tabular menu">
                <a class="item active"
                   data-tab="config_google_merchant"><?php esc_html_e( "Google Merchant Feed", 'gshopping-wc-google-shopping' ) ?></a>
                <a class="item"
                   data-tab="config_product_data"><?php esc_html_e( "Product Data", 'gshopping-wc-google-shopping' ) ?></a>
                <a class="item"
                   data-tab="config_credential"><?php esc_html_e( "Credentials", 'gshopping-wc-google-shopping' ) ?></a>
                <a class="item"
                   data-tab="config_guide"><?php esc_html_e( "Guide", 'gshopping-wc-google-shopping' ) ?></a>
            </div>
            <div id="config_google_merchant" data-tab="config_google_merchant"
                 class="vi-ui bottom attached tab segment active">
                <!--        config to connect fetch schedule-->
                <div class="vi-ui styled fluid accordion vi-pfvi-accordion" id="fetch_schedule">
                    <div class="vi-pfvi-accordion-title title active">
                        <i class="dropdown icon"></i>
						<?php esc_html_e( "Config for Fetch Schedule", 'gshopping-wc-google-shopping' ) ?>
                        <p>
							<?php esc_html_e( "You'll host a file on your website that contains data and schedule a regular time for Google to fetch updates. Updates are only applied to your account when the fetch occurs.", 'gshopping-wc-google-shopping' ) ?>
                        </p>
                    </div>
                    <div class="vi-pfvi-accordion-content content active">
                        <div>
                            <div class="vi-ui yellow message hidden"></div>
                        </div>
                        <table class="vi-ui table">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Enable', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Language', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Action', 'gshopping-wc-google-shopping' ); ?></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php if ( class_exists( 'Polylang' ) ) {
								foreach ( $languages as $key => $language ) {
									$record = array();
									if ( isset( $params_config["schedule"][ $language ] ) ) {
										$record = $params_config["schedule"][ $language ];
									} else {
										$record = array(
											"schedule_enable" => "",
											"link_xml"        => "",
										);
									}
									?>
                                    <tr id="schedule-<?php echo esc_attr( $language ) ?>">
                                        <td class="pfvi_enable">
                                            <div class="vi-ui toggle checkbox pfvi_toggle-enable">
                                                <input type="checkbox"
                                                       name="<?php echo esc_attr( "schedule_enable[$language]" ) ?>"
													<?php echo esc_attr( ( $record["schedule_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                                <label></label>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?php echo esc_html( $languages_map[ $language ], 'gshopping-wc-google-shopping' ); ?></p>
                                        </td>
                                        <td class="pfvi_action-schedule">
                                            <div class="vi-ui buttons">
												<?php
												if ( ! empty( $record["link_xml"] ) && file_exists( PFVI_DIR_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . "feed-" . $language . ".xml" ) ) {
													?>
                                                    <div class="vi-ui small icon button copy-xml"
                                                         data-tooltip="Copy url">
                                                        <i class="copy icon"></i>
                                                        <input name="<?php echo esc_attr( "link_xml[$language]" ) ?>"
                                                               value="<?php echo esc_attr( $record["link_xml"] ) ?>"
                                                               hidden>
                                                    </div>
                                                    <div class="vi-ui small icon button clear-xml"
                                                         data-tooltip="Clear data">
                                                        <i class="redo icon"></i>
                                                        <input value="<?php echo esc_attr( $language ) ?>" hidden>
                                                    </div>
                                                    <div class="vi-ui small icon button push-all-schedule"
                                                         data-tooltip="Push all product">
                                                        <i class="angle double up icon"></i>
														<?php printf( '<input language="%s" value="%s" hidden>',
															esc_attr__( $languages_map[ $language ], 'gshopping-wc-google-shopping' ),
															esc_attr( $language ) ) ?>
                                                    </div>
													<?php
												} else { ?>
                                                    <div class="vi-ui small icon button generate-xml"
                                                         data-tooltip="Generate XML">
                                                        <i class="plus icon"></i>
                                                        <input name="<?php echo esc_attr( "link_xml[$language]" ) ?>"
                                                               value="<?php echo esc_attr( $language ) ?>" hidden>
                                                    </div>
												<?php }
												?>
                                            </div>
                                        </td>
                                    </tr>
								<?php }
							} else {
								$record = $params_config["schedule"][ $languages ];
								?>
                                <tr id="schedule-<?php echo esc_attr( $languages ) ?>">
                                    <td class="pfvi_enable">
                                        <div class="vi-ui toggle checkbox pfvi_toggle-enable">
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( "schedule_enable[$languages]" ) ?>"
												<?php echo esc_attr( ( $record["schedule_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                            <label></label>
                                        </div>
                                    </td>
                                    <td>
                                        <p><?php echo esc_html_e( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ); ?></p>
                                    </td>
                                    <td class="pfvi_action-schedule">
                                        <div class="vi-ui buttons">
											<?php
											$filename = PFVI_DIR_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'feed-' . $languages . '.xml';
											if ( file_exists( $filename ) ) {
												?>
                                                <div class="vi-ui small icon button copy-xml" data-tooltip="Copy url">
                                                    <i class="copy icon"></i>
                                                    <input name="<?php echo esc_attr( "link_xml[$languages]" ) ?>"
                                                           value="<?php echo esc_attr( $record["link_xml"] ) ?>" hidden>
                                                </div>
                                                <div class="vi-ui small icon button clear-xml"
                                                     data-tooltip="Clear data">
                                                    <i class="redo icon"></i>
                                                    <input value="<?php echo esc_attr( $languages ) ?>" hidden>
                                                </div>
                                                <div class="vi-ui small icon button push-all-schedule"
                                                     data-tooltip="Push all product">
                                                    <i class="angle double up icon"></i>
													<?php printf( '<input language="%s" value="%s" hidden>',
														esc_attr__( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ),
														esc_attr( $languages ) ) ?>
                                                </div>
												<?php
											} else { ?>
                                                <div class="vi-ui small icon button generate-xml"
                                                     data-tooltip="Generate XML">
                                                    <i class="plus icon"></i>
                                                    <input name="<?php echo esc_attr( "link_xml[$languages]" ) ?>"
                                                           value="<?php echo esc_attr( $languages ) ?>" hidden>
                                                </div>
											<?php }
											?>
                                        </div>
                                    </td>
                                </tr>

							<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--        config to connect google sheet-->
                <div class="vi-ui styled fluid accordion vi-pfvi-accordion" id="google_sheet">
                    <div class="vi-pfvi-accordion-title title">
                        <i class="dropdown icon"></i>
						<?php esc_html_e( "Config for Google Sheet", 'gshopping-wc-google-shopping' ) ?>
                        <p>
							<?php esc_html_e( "You'll make updates to your product data in a Google Sheet, and they'll automatically be applied to your account.", 'gshopping-wc-google-shopping' ) ?>
                        </p>
                    </div>
                    <div class="vi-pfvi-accordion-content content">
                        <div>
                            <div class="vi-ui yellow message hidden"></div>
                        </div>
                        <table class="vi-ui table">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Enable', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Language', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Spreadsheet ID', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Range', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Action', 'gshopping-wc-google-shopping' ); ?></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php if ( class_exists( 'Polylang' ) ) {
								foreach ( $languages as $key => $language ) {
									$record = array();
									if ( isset( $params_config["sheet"][ $language ] ) ) {
										$record = $params_config["sheet"][ $language ];
									} else {
										$record = array(
											"sheet_enable" => "",
											"sheet_id"     => "",
											"sheet_range"  => "Sheet1",
										);
									}
									?>
                                    <tr id="sheet-<?php echo esc_attr( $language ) ?>">
                                        <td class="pfvi_enable">
                                            <div class="vi-ui toggle checkbox field">
                                                <input type="checkbox"
                                                       name="<?php echo esc_attr( "sheet_enable[$language]" ); ?>"
													<?php echo esc_attr( ( $record["sheet_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                                <label></label>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?php echo esc_html_e( $languages_map[ $language ], 'gshopping-wc-google-shopping' ) ?></p>
                                        </td>
                                        <td>
                                            <input type="text" class="field-sheet-id"
                                                   name="<?php echo esc_attr( "sheet_id[$language]" ); ?>"
                                                   value="<?php echo esc_attr( $record["sheet_id"] ) ?>"
                                                   placeholder="1IkKnmZh...w5GTWuGjZ....mbNp9nYrMlw">
                                        </td>
                                        <td>
                                            <input type="text" class="field-sheet-range"
                                                   name="<?php echo esc_attr( "sheet_range[$language]" ); ?>"
                                                   value="<?php echo esc_attr( $record["sheet_range"] ) ?>"
                                                   placeholder="Sheet1">
                                        </td>
                                        <td class="pfvi_action-sheet">
											<?php if ( ! empty( $record["sheet_id"] ) && ! empty( $record["sheet_range"] ) ) { ?>
                                                <div class="vi-ui buttons">
                                                    <a class="vi-ui small icon button"
                                                       data-tooltip="Access"
                                                       target="_blank"
                                                       href="<?php echo esc_attr( "https://docs.google.com/spreadsheets/d/" . $record["sheet_id"] . "/edit" ) ?>">
                                                        <i class="linkify icon"></i>
                                                    </a>
													<?php if ( $record["sheet_enable"] == "on" ) { ?>
                                                        <div class="vi-ui small icon button push-all-sheet"
                                                             data-tooltip="Push all product">
                                                            <i class="angle double up icon"></i>
															<?php printf( '<input language="%s" value="%s" hidden>',
																esc_attr__( $languages_map[ $language ], 'gshopping-wc-google-shopping' ),
																esc_attr( $language ) ) ?>
                                                        </div>
													<?php } ?>
                                                </div>
											<?php } ?>
                                            <div class="vi-ui buttons create-sheet">
                                                <div class="vi-ui small icon button" data-tooltip="Create new file">
                                                    <i class="plus icon"></i>
													<?php printf( '<input language="%s" value="%s" hidden>',
														esc_attr__( $languages_map[ $language ], 'gshopping-wc-google-shopping' ),
														esc_attr( $language ) ) ?>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
								<?php }
							} else {
								$record = $params_config["sheet"][ $languages ]; ?>
                                <tr id="sheet-<?php echo esc_attr( $languages ) ?>">
                                    <td class="pfvi_enable">
                                        <div class="vi-ui toggle checkbox field">
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( "sheet_enable[$languages]" ); ?>"
												<?php echo esc_attr( ( $record["sheet_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                            <label></label>
                                        </div>
                                    </td>
                                    <td>
                                        <p><?php echo esc_html_e( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ) ?></p>
                                    </td>
                                    <td>
                                        <input type="text" class="field-sheet-id"
                                               name="<?php echo esc_attr( "sheet_id[$languages]" ); ?>"
                                               value="<?php echo esc_attr( $record["sheet_id"] ) ?>"
                                               placeholder="1IkKnmZh...w5GTWuGjZ....mbNp9nYrMlw">
                                    </td>
                                    <td>
                                        <input type="text" class="field-sheet-range"
                                               name="<?php echo esc_attr( "sheet_range[$languages]" ); ?>"
                                               value="<?php echo esc_attr( $record["sheet_range"] ) ?>"
                                               placeholder="Sheet1">
                                    </td>
                                    <td class="pfvi_action-sheet">
										<?php if ( ! empty( $record["sheet_id"] ) && ! empty( $record["sheet_range"] ) ) { ?>
                                            <div class="vi-ui buttons">
                                                <a class="vi-ui small icon button"
                                                   data-tooltip="Access"
                                                   target="_blank"
                                                   href="<?php echo esc_attr( "https://docs.google.com/spreadsheets/d/" . $record["sheet_id"] . "/edit" ) ?>">
                                                    <i class="linkify icon"></i>
                                                </a>
												<?php if ( $record["sheet_enable"] == "on" ) { ?>
                                                    <div class="vi-ui small icon button push-all-sheet"
                                                         data-tooltip="Push all product">
                                                        <i class="angle double up icon"></i>
														<?php printf( '<input language="%s" value="%s" hidden>',
															esc_attr__( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ),
															esc_attr( $languages ) ) ?>
                                                    </div>
												<?php } ?>
                                            </div>
										<?php } ?>
                                        <div class="vi-ui buttons create-sheet">
                                            <div class="vi-ui small icon button" data-tooltip="Create new file">
                                                <i class="plus icon"></i>
												<?php printf( '<input language="%s" value="%s" hidden>',
													esc_attr__( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ),
													esc_attr( $languages ) ) ?>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

							<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--        config to connect Content API-->
                <div class="vi-ui styled fluid accordion vi-pfvi-accordion" id="merchant_api">
                    <div class="vi-pfvi-accordion-title title">
                        <i class="dropdown icon"></i>
						<?php esc_html_e( "Config to Merchant API", 'gshopping-wc-google-shopping' ) ?>
                        <p>
							<?php esc_html_e( "The Content API for Shopping enables you to automatically upload product listings and so make your products easily discoverable through Shopping ads.", 'gshopping-wc-google-shopping' ) ?>
                        </p>
                    </div>
                    <div class="vi-pfvi-accordion-content content">
                        <div>
                            <div class="vi-ui yellow message hidden"></div>
                        </div>
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>Merchant ID*</th>
                                <td>
                                    <input type="text"
                                           name="<?php echo esc_attr( "merchant_id" ); ?>"
                                           value="<?php echo esc_attr( $params_config["merchant_id"] ) ?>"
                                           placeholder="6666688888">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="vi-ui table">
                            <thead>
                            <tr>
                                <th><?php esc_html_e( 'Enable', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Language', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Target country', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Channel', 'gshopping-wc-google-shopping' ); ?></th>
                                <th><?php esc_html_e( 'Action', 'gshopping-wc-google-shopping' ); ?></th>
                            </tr>
                            </thead>
                            <tbody>
							<?php if ( class_exists( 'Polylang' ) ) {
								foreach ( $languages as $key => $language ) {
									$record = array();
									if ( isset( $params_config["api"][ $language ] ) ) {
										$record = $params_config["api"][ $language ];
									} else {
										$record = array(
											"api_enable"  => "",
											"api_country" => "",
											"channel"     => "local",
										);
									}
									?>
                                    <tr id="api-<?php echo esc_attr( $language ) ?>">
                                        <td class="pfvi_enable">
                                            <div class="vi-ui toggle checkbox field">
                                                <input type="checkbox"
                                                       name="<?php echo esc_attr( "api_enable[$language]" ); ?>"
													<?php echo esc_attr( ( $record["api_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                                <label></label>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?php echo esc_html_e( $languages_map[ $language ], 'gshopping-wc-google-shopping' ) ?></p>
                                        </td>
                                        <td>
											<?php pfvi_select_html_country( "api_country[$language]", $countries, $record["api_country"] ); ?>
                                        </td>
                                        <td>
											<?php
											$channel        = $record["channel"];
											$local_channel  = "";
											$online_channel = "";

											switch ( $channel ) {
												case "local":
													$local_channel = "selected";
													break;
												case "online":
													$online_channel = "selected";
													break;
												default:
													"";
											}
											?>
                                            <select class="vi-ui dropdown"
                                                    name="<?php echo esc_attr( "channel[$language]" ); ?>">
                                                <option value="local" <?php echo esc_attr( $local_channel ); ?>>Local
                                                </option>
                                                <option value="online" <?php echo esc_attr( $online_channel ); ?>>
                                                    Online
                                                </option>
                                            </select>
                                        </td>
                                        <td class="pfvi_action-api">
											<?php if ( ! empty( $params_config["merchant_id"] ) && ( $record["api_enable"] == "on" ) && ! empty( $record["api_country"] ) && ! empty( $record["channel"] ) ) { ?>
                                                <div class="vi-ui buttons">
                                                    <div class="vi-ui small icon button push-all-api"
                                                         data-tooltip="Push all product">
                                                        <i class="angle double up icon"></i>
														<?php printf( '<input language="%s" value="%s" hidden>',
															esc_attr__( $languages_map[ $language ], 'gshopping-wc-google-shopping' ),
															esc_attr( $language ) ) ?>
                                                    </div>
                                                </div>
											<?php } ?>
                                        </td>
                                    </tr>
								<?php }
							} else {
								$record = $params_config["api"][ $languages ]; ?>
                                <tr id="api-<?php echo esc_attr( $languages ) ?>">
                                    <td class="pfvi_enable">
                                        <div class="vi-ui toggle checkbox field">
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( "api_enable[$languages]" ); ?>"
												<?php echo esc_attr( ( $record["api_enable"] == "on" ) ? ( 'checked="checked"' ) : '' ) ?>>
                                            <label></label>
                                        </div>
                                    </td>
                                    <td>
                                        <p><?php echo esc_html_e( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ) ?></p>
                                    </td>
                                    <td>
										<?php pfvi_select_html_country( "api_country[$languages]", $countries, $record["api_country"] ); ?>
                                    </td>
                                    <td>
										<?php
										$channel        = $record["channel"];
										$local_channel  = "";
										$online_channel = "";

										switch ( $channel ) {
											case "local":
												$local_channel = "selected";
												break;
											case "online":
												$online_channel = "selected";
												break;
										}
										?>
                                        <select class="vi-ui dropdown"
                                                name="<?php echo esc_attr( "channel[$languages]" ); ?>">
                                            <option value="local" <?php echo esc_attr( $local_channel ); ?>>Local
                                            </option>
                                            <option value="online" <?php echo esc_attr( $online_channel ); ?>>Online
                                            </option>
                                        </select>
                                    </td>
                                    <td class="pfvi_action-api">
										<?php if ( ! empty( $params_config["merchant_id"] ) && ( $record["api_enable"] == "on" ) && ! empty( $record["api_country"] ) && ! empty( $record["channel"] ) ) { ?>
                                            <div class="vi-ui buttons">
                                                <div class="vi-ui small icon button push-all-api"
                                                     data-tooltip="Push all product">
                                                    <i class="angle double up icon"></i>
													<?php printf( '<input language="%s" value="%s" hidden>',
														esc_attr__( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ),
														esc_attr( $languages ) ) ?>
                                                </div>
                                            </div>
										<?php } ?>
                                    </td>
                                </tr>
							<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="config_product_data" data-tab="config_product_data" class="vi-ui bottom attached tab segment">
                <div class="vi-ui blue message">
					<?php
					printf( '<p>%s. <a target="_blank" href="%s">%s</a></p>',
						esc_html__( 'The product information you submit using these attributes is Merchant foundation for creating successful ads and free listings for your products', 'gshopping-wc-google-shopping' ),
						esc_url( 'https://support.google.com/merchants/answer/7052112' ),
						esc_html__( "Product data specification", 'gshopping-wc-google-shopping' ),
					);
					?>
                </div>
                <table class="form-table" id="product_attributes_select">
                    <tbody>
                    <tr>
                        <th><?php esc_html_e( "Select attributes", 'gshopping-wc-google-shopping' ) ?></th>
                        <td>
							<?php
							foreach ( $attribute_required as $key => $attribute ) {
								printf( '<input name="attributes[]" value="%s" hidden>', esc_attr( $key ) );
							}

							?>
                            <select class="vi-ui fluid search dropdown" multiple="" name="attributes[]">
								<?php
								foreach ( $attribute_not_required as $key => $attribute ) {
									$attribute_selected = ( is_array( $params_config["attributes"] ) && in_array( $key, $params_config["attributes"] ) ) ? " selected" : "";
									printf( '<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										esc_attr( $attribute_selected ),
										esc_html( $attribute["title"], 'gshopping-wc-google-shopping' )
									);
								}
								?>
                            </select>
                            <div class="pfvi_mt-2p">
                                <button class="vi-ui red basic right floated button mini" type="button" id="clear-all">
                                    Clear all
                                </button>
                                <button class="vi-ui blue basic right floated button mini" type="button"
                                        id="select-all">Select all
                                </button>
                            </div>
                            <div class="pfvi_mt-3">
                                <span><b>Required: </b></span>
								<?php
								foreach ( $attribute_required as $key => $attribute ) {
									printf( '<div class="vi-ui tiny basic label">%s</div>',
										esc_html( $attribute["title"], 'gshopping-wc-google-shopping' )
									);
								}
								?>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="form-table" id="map_attributes">
                    <tbody>
                    <tr>
                        <th><?php esc_html_e( "Map attributes", 'gshopping-wc-google-shopping' ) ?></th>
                        <td colspan="2">
                            <table class="vi-ui table very basic">
                                <tbody>
                                <tr>
                                    <td><b>Google attribute</b></td>
                                    <td><b>Product attribute</b></td>
                                </tr>
								<?php foreach ( $params_config['attributes_map'] as $key => $value ) { ?>
                                    <tr>
                                        <td><?php esc_html_e( $key, 'gshopping-wc-google-shopping' ) ?></td>
                                        <td>
                                            <select class="vi-ui dropdown" name="attributes_map[<?php echo esc_html($key) ?>]">
												<?php
												foreach ( $attributes_taxonomies as $attributes_taxonomy ) {
													$attributes_taxonomy_selected = ($value === $attributes_taxonomy->attribute_name) ? " selected" : "";
													printf( '<option value="%s" %s>%s</option>',
														esc_attr($attributes_taxonomy->attribute_name),
                                                        esc_attr($attributes_taxonomy_selected),
														esc_html( $attributes_taxonomy->attribute_label, 'gshopping-wc-google-shopping' ) );
												}
												?>
                                            </select>
                                        </td>
                                    </tr>
								<?php } ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="config_credential" data-tab="config_credential" class="vi-ui bottom attached tab segment">
                <div class="vi-ui blue message">
					<?php
					printf( '<p>%s. <a target="_blank" href="%s">%s</a></p>',
						esc_html__( 'If not use Google Sheet and Merchant API, you don\'t have to enter these fields', 'gshopping-wc-google-shopping' ),
						esc_url( 'https://console.cloud.google.com/apis/credentials' ),
						esc_html__( "Get value here", 'gshopping-wc-google-shopping' )
					);
					?>
                </div>
                <div class="vi-ui yellow message hidden"></div>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>API KEY*</th>
                        <td>
                            <div class="field">
                                <input type="text" name="api_key"
                                       value="<?php echo esc_attr( $params_config["api_key"] ) ?>"
                                       placeholder="AIz...kauBJ7aq7X7Of">
                                <div class="vi-ui pointing label">
									<?php
									esc_html_e( 'Get this value in Google API Console', 'gshopping-wc-google-shopping' );
									?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>CLIENT ID*</th>
                        <td>
                            <div class="field">
                                <input type="text" name="client_id"
                                       value="<?php echo esc_attr( $params_config["client_id"] ) ?>"
                                       placeholder="...apps.googleusercontent.com">
                                <div class="vi-ui pointing label">
									<?php
									esc_html_e( 'Get this value in Google API Console', 'gshopping-wc-google-shopping' );
									?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>CLIENT SECRET*</th>
                        <td>
                            <div class="field">
                                <input type="text" name="client_secret"
                                       value="<?php echo esc_attr( $params_config["client_secret"] ) ?>"
                                       placeholder="GOCSP...tsda3oFb">
                                <div class="vi-ui pointing label">
									<?php
									esc_html_e( 'Get this value in Google API Console', 'gshopping-wc-google-shopping' );
									?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Redirect URI</th>
                        <td>
                            <div class="field">
                                <div class="vi-ui action input">
                                    <input type="text" readonly
                                           value="<?php echo esc_attr( $params_config["redirect_uri"] ) ?>">
                                    <button type="button" class="vi-ui right small icon button copy-xml"
                                            data-tooltip="Copy url">
                                        <i class="copy icon"></i>
                                        <input value="<?php echo esc_attr( $params_config["redirect_uri"] ) ?>" hidden>
                                    </button>
                                </div>
                                <div class="vi-ui pointing label">
									<?php
									esc_html_e( 'Paste this value to Google API Console', 'gshopping-wc-google-shopping' );
									?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <tr>
                        <th></th>
                        <td>
                            <div class="inline field">
                                <a class="vi-ui button" type="button" id="getClient">
                                    OAuth
                                </a>
                                <div class="vi-ui left pointing label">
									<?php esc_html_e( 'Click here to Authenticate after complete fields above', 'gshopping-wc-google-shopping' ); ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="config_guide" data-tab="config_guide" class="vi-ui bottom attached tab segment">
                <div class="pfvi_wrap-guide">
                    <div class="pfvi_guide-main">
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_schedule">
                            <h4><?php esc_html_e( "Use Schedule fetch", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => esc_html__( 'In tab "Google Merchant Feed", click generate file. If you don\'t see plus button that mean file existed so you can skip this step', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-fetch-schedule-1.png',
								],
								[
									'text' => esc_html__( 'Click copy button then paste into File URL setting Scheduled fetch\'s feed you created', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-fetch-schedule-2.png'
								],
								[
									'text' => esc_html__( 'Enable language you want to push product into the file XML. You can\'t upload products if you don\'t enable', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-fetch-schedule-3.png'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_sheet">
                            <h4><?php esc_html_e( "Use Google sheet", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => esc_html__( 'If you had a file google sheet, get Spreadsheet ID and Spreadsheet range following the picture below', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-gg-sheet-1.png'
								],
								[
									'text' => esc_html__( 'Paste Spreadsheet ID and Spreadsheet range you took at previous step or you can click create a new file then fields will be autocomplete', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-gg-sheet-2.png'
								],
								[
									'text' => esc_html__( 'Enable language you want to push product into the Google sheet. You can\'t upload products if you don\'t enable', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-gg-sheet-3.png'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_api">
                            <h4><?php esc_html_e( "Use Merchant API", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => esc_html__( 'Access Google Merchant Center dashboard to get Merchant ID', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-merchant-api-1.png'
								],
								[
									'text' => esc_html__( 'Enter Merchant ID', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-merchant-api-2.png'
								],
								[
									'text' => esc_html__( 'Select "country" you want to sale', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-merchant-api-3.png'
								],
								[
									'text' => esc_html__( 'Enable language you want to push product into Merchant. You can\'t upload products if you don\'t enable', 'gshopping-wc-google-shopping' ),
									'img'  => 'use-merchant-api-4.png'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_credential">
                            <h4><?php esc_html_e( "Config credentials", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => sprintf( '%s <a href="%s" target="_blank">%s</a>%s',
										esc_attr__( 'Access website ', 'gshopping-wc-google-shopping' ),
										esc_attr( 'https://console.cloud.google.com/apis/library' ),
										esc_attr( 'https://console.cloud.google.com/apis/library' ),
										esc_attr__( ' search and enable "Google Sheets API" library, "Content API for Shopping" library', 'gshopping-wc-google-shopping' )
									),
									'img'  => 'credentials-1.png'
								],
								[
									'text' => esc_attr__( 'At OAuth consent screen tab of Google console, create OAuth consent screen then click "PUBLISH APP"', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-2.png'
								],
								[
									'text' => esc_attr__( 'At Credentials tab of Google console, if you haven\'t created project, you have to create new project to use', 'gshopping-wc-google-shopping' ),
									'img'  => ''
								],
								[
									'text' => esc_attr__( 'Click "create credentials, choose "API key". System will create for you a string of API key, note it', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-4.png'
								],
								[
									'text' => esc_attr__( 'At Credentials tab of setting GShopping page, copy Redirect URI', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-5.png'
								],
								[
									'text' => esc_attr__( 'Comeback Google console, click "create credentials" one more time, select "OAuth client ID". ', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-6.png'
								],
								[
									'text' => esc_attr__( 'At field "Application type", select "Web application"', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-7.png'
								],
								[
									'text' => esc_attr__( 'At field "Authorized redirect URIs", click "ADD URI" button, paste url you copied above', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-8.png'
								],
								[
									'text' => esc_attr__( 'When OAuth client have created, you will receive 2 values are Client ID and Client Secret, note it', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-9.png'
								],
								[
									'text' => esc_attr__( 'At Credentials tab of setting GShopping page, get 3 values you receive in the above steps to complete fields data. Click "Save" data', 'gshopping-wc-google-shopping' ),
									'img'  => 'credentials-10.png'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_push_all">
                            <h4><?php esc_html_e( "Push all products", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => esc_html__( 'Access Google Merchant Feed tab of setting GShopping page', 'gshopping-wc-google-shopping' ),
									'img'  => 'guide-push-all-1.PNG'
								],
								[
									'text' => esc_html__( 'Each language of feed have button let you push', 'gshopping-wc-google-shopping' ),
									'img'  => 'guide-push-all-2.PNG'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                        <div class="vi-ui accordion pfvi_guide-wrap" id="guide_push_filter">
                            <h4><?php esc_html_e( "Push products by filter", 'gshopping-wc-google-shopping' ) ?></h4>
							<?php
							$guides = [
								[
									'text' => esc_html__( 'Access All products page', 'gshopping-wc-google-shopping' ),
									'img'  => 'guide-push-filter-1.PNG'
								],
								[
									'text' => esc_html__( 'Select product you want to push', 'gshopping-wc-google-shopping' ),
									'img'  => 'guide-push-filter-2.PNG'
								],
								[
									'text' => esc_html__( 'In bulk actions, select type feed then click "Apply" button', 'gshopping-wc-google-shopping' ),
									'img'  => 'guide-push-filter-3.PNG'
								],
							];

							pfvi_gender_guide( $guides );
							?>
                        </div>
                    </div>
                    <div class="pfvi_guide-menu">
                        <div class="pfvi_guide-menu-sticky">
                            <div class="vi-ui ordered list">
                                <div class="item">
                                    <a href="#guide_schedule">Feeds</a>
                                    <div class="list">
                                        <a class="item"
                                           href="#guide_schedule"><?php esc_html_e( 'Use Fetch schedule', 'gshopping-wc-google-shopping' ) ?></a>
                                        <a class="item"
                                           href="#guide_sheet"><?php esc_html_e( 'Use Google sheet', 'gshopping-wc-google-shopping' ) ?></a>
                                        <a class="item"
                                           href="#guide_api"><?php esc_html_e( 'Use Merchant API', 'gshopping-wc-google-shopping' ) ?></a>
                                    </div>
                                </div>
                                <a class="item"
                                   href="#guide_credential"><?php esc_html_e( 'Credentials', 'gshopping-wc-google-shopping' ); ?></a>
                                <div class="item">
                                    <a class="item"
                                       href="#guide_push_all"><?php esc_html_e( 'Push product', 'gshopping-wc-google-shopping' ); ?></a>
                                    <div class="list">
                                        <a class="item"
                                           href="#guide_push_all"><?php esc_html_e( 'Push all product', 'gshopping-wc-google-shopping' ); ?></a>
                                        <a class="item"
                                           href="#guide_push_filter"><?php esc_html_e( 'Push products by filter', 'gshopping-wc-google-shopping' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pfvi_button-sticky">
                <button type="submit" class="vi-ui primary labeled icon button"
                        name="<?php echo esc_attr( PFVI_PREFIX ) ?>save_setting_feed">
                    <i class="save icon"></i>
					<?php echo esc_html_e( 'Save', 'gshopping-wc-google-shopping' ) ?>
                </button>
                <button type="submit" class="vi-ui button negative labeled icon"
                        name="<?php echo esc_attr( PFVI_PREFIX ) ?>reset_save_setting_feed">
                    <i class="undo icon"></i>
					<?php echo esc_html_e( 'Reset Settings', 'gshopping-wc-google-shopping' ) ?>
                </button>
            </div>
        </form>
    </div>
<?php
do_action( 'villatheme_support_gshopping-wc-google-shopping' );