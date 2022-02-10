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

?>
<div id="setup_wizard" class="pfvi_wizard-wrap">
    <div class="pfvi_wizard-logo">
        <img src="https://villatheme.com/wp-content/uploads/2015/04/logo.png">
    </div>
    <div class="pfvi_wizard-main">
        <div step="wizard_feed" class="pfvi_wizard-step active">
            <div class="pfvi_wizard-step-top">
				<?php
				printf( '<h1>%s</h1>', esc_html__( 'Choose Feeds', 'gshopping-wc-google-shopping' ) );
				printf( '<p>%s</p>', esc_html__( 'Choose at least 1 Feed, which you use to push product on Merchant', 'gshopping-wc-google-shopping' ) )
				?>
                <div class="vi-ui yellow message hidden"></div>
            </div>
            <div class="pfvi_wizard-step-main">
                <div class="vi-ui pfvi_wizard-card">
                    <label for="feed_schedule">
                        <div class="pfvi_wizard-card__logo">
                            <span class="dashicons dashicons-admin-page"></span>
                        </div>
                        <div class="pfvi_wizard-card__title">Fetch Schedule</div>
                    </label>
                    <input type="checkbox" name="feed" id="feed_schedule">
                </div>
                <div class="vi-ui pfvi_wizard-card">
                    <label for="feed_sheet">
                        <div class="pfvi_wizard-card__logo">
                            <span class="dashicons dashicons-media-spreadsheet"></span>
                        </div>
                        <div class="pfvi_wizard-card__title">Google Sheet</div>
                    </label>
                    <input type="checkbox" name="feed" id="feed_sheet">
                </div>
                <div class="vi-ui pfvi_wizard-card">
                    <label for="feed_api">
                        <div class="pfvi_wizard-card__logo">
                            <span class="dashicons dashicons-rest-api"></span>
                        </div>
                        <div class="pfvi_wizard-card__title">Merchant API</div>
                    </label>
                    <input type="checkbox" name="feed" id="feed_api">
                </div>
            </div>
            <div class="pfvi_wizard-step-bottom">
                <button class="vi-ui right floated blue basic button" step="wizard_feed" type="button">
					<?php
					printf( '%s', esc_html__( 'Continue', 'gshopping-wc-google-shopping' ) );
					?>
                </button>
            </div>
        </div>
        <form class="vi-ui fluid form" method="POST" id="wizard_credential">
            <input type="hidden" name="pfvi_nonce_credential"
                   value="<?php echo wp_create_nonce( 'pfvi_wizard_credential_nonce' ) ?>">
            <div id="config_credential" step="wizard_api" class="pfvi_wizard-step">
                <div class="pfvi_wizard-step-top">
					<?php
					printf( '<h1>%s</h1>', esc_html__( 'Config API', 'gshopping-wc-google-shopping' ) );
					printf( '<p>%s <a target="_blank" href="%s">%s</a></p>',
						esc_html__( 'The Feed you have choosed required Google API. You can follow this', 'gshopping-wc-google-shopping' ),
						esc_url( admin_url( 'admin.php?page=vi-product-feed#guide_credential' ) ),
						esc_html__( 'guide', 'gshopping-wc-google-shopping' ),
					);
					?>
                    <div class="vi-ui yellow message hidden"></div>
                </div>
                <div class="pfvi_wizard-step-main">
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
                                        Get this value in Google API Console
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
                                        Get this value in Google API Console
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
                                        Get this value in Google API Console
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
                                            <input value="<?php echo esc_attr( $params_config["redirect_uri"] ) ?>"
                                                   hidden>
                                        </button>
                                    </div>
                                    <div class="vi-ui pointing label">
                                        Paste this value to Google API Console
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--                <input name="submit_wizard_credential" hidden>-->
                <div class="pfvi_wizard-step-bottom">
                    <button class="vi-ui right floated blue basic button" step="wizard_api" type="button">
						<?php
						printf( '%s', esc_html__( 'Continue', 'gshopping-wc-google-shopping' ) );
						?>
                    </button>
                    <!--                    <button class="vi-ui right floated grey basic button" step="wizard_api" type="button">Skip</button>-->
                </div>
            </div>
        </form>
        <div id="oauth" step="wizard_oauth" class="pfvi_wizard-step">
            <div class="pfvi_wizard-step-top">
                <h1>Oauth</h1>
                <p>Oauth</p>
                <div class="vi-ui yellow message hidden"></div>
            </div>
            <div class="pfvi_wizard-step-main">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <a class="vi-ui button" type="button" id="getClient">
                                OAuth
                            </a>
                        </th>
                        <td>
                            <div class="vi-ui left pointing label">
                                Click here to Authenticate
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="pfvi_wizard-step-bottom">
                <button class="vi-ui right floated blue basic button" step="wizard_oauth" type="button">
					<?php
					printf( '%s', esc_html__( 'Continue', 'gshopping-wc-google-shopping' ) );
					?>
                </button>
                <!--                    <button class="vi-ui right floated grey basic button" step="wizard_product_data" type="button">Skip</button>-->
            </div>
        </div>
        <form class="vi-ui fluid form" method="post" id="wizard_data">
            <input type="hidden" name="pfvi_nonce" value="<?php echo wp_create_nonce( 'pfvi_nonce_data' ) ?>">
            <input type="hidden" name="action" value="pfvi_wizard_data">
            <div id="config_product_data" step="wizard_product_data" class="vi-ui pfvi_wizard-step">
                <div class="pfvi_wizard-step-top">
					<?php
					printf( '<h1>%s</h1>', esc_html__( 'Select attributes', 'gshopping-wc-google-shopping' ) );
					?>
					<?php
					printf( '<p>%s. <a target="_blank" href="%s">%s</a></p>',
						esc_html__( 'The product information you submit using these attributes is Merchant foundation for creating successful ads and free listings for your products', 'gshopping-wc-google-shopping' ),
						esc_url( 'https://support.google.com/merchants/answer/7052112' ),
						esc_html__( "Product data specification", 'gshopping-wc-google-shopping' ),
					);
					?>
                    <div class="vi-ui yellow message hidden"></div>
                </div>
                <div class="pfvi_wizard-step-main">
                    <table class="form-table">
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
                                <div class="pfvi_mt-1">
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
                </div>
                <div class="pfvi_wizard-step-bottom">
                    <button class="vi-ui right floated blue basic button" step="wizard_product_data" type="button">
						<?php
						printf( '%s', esc_html__( 'Continue', 'gshopping-wc-google-shopping' ) );
						?>
                    </button>
                    <!--                    <button class="vi-ui right floated grey basic button" step="wizard_product_data" type="button">Skip</button>-->
                </div>
            </div>
            <div id="config_google_merchant" step="wizard_feed_data" class="vi-ui pfvi_wizard-step">
                <div class="pfvi_wizard-step-top">
	                <?php
	                printf( '<h1>%s</h1>', esc_html__( 'Active', 'gshopping-wc-google-shopping' ) );
					printf( '<p>%s</p>', esc_html__( 'Enable field by language you want to push product into Merchant. You can\'t upload products if you don\'t enable', 'gshopping-wc-google-shopping' ) )
					?>
                    <div class="vi-ui yellow message hidden"></div>
                </div>
                <div class="pfvi_wizard-step-main">
                    <div class="pfvi_wizard-merchant-option" id="fetch_schedule">
                        <h4>Fetch Schedule</h4>
                        <p>You'll host a file on your website that contains data and schedule a regular time for
                            Google to fetch updates. Updates are only applied to your account when the fetch occurs.
                        </p>
                        <div class="vi-ui yellow message hidden"></div>
                        <table class="vi-ui compact celled table">
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
                    <div class="pfvi_wizard-merchant-option" id="google_sheet">
                        <h4>Google sheet</h4>
                        <p>You'll make updates to your product data in a Google Sheet, and they'll automatically be
                            applied to your account.</p>
                        <div class="vi-ui yellow message hidden"></div>
                        <table class="vi-ui compact celled table">
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
											<?php if ( ( $record["sheet_enable"] == "on" ) && ! empty( $record["sheet_id"] ) && ! empty( $record["sheet_range"] ) ) { ?>
                                                <div class="vi-ui buttons">
                                                    <a class="vi-ui small icon button"
                                                       data-tooltip="Access"
                                                       target="_blank"
                                                       href="<?php echo esc_attr( "https://docs.google.com/spreadsheets/d/" . $record["sheet_id"] . "/edit" ) ?>">
                                                        <i class="linkify icon"></i>
                                                    </a>
                                                    <div class="vi-ui small icon button push-all-sheet"
                                                         data-tooltip="Push all product">
                                                        <i class="angle double up icon"></i>
														<?php printf( '<input language="%s" value="%s" hidden>',
															esc_attr__( $languages_map[ $language ], 'gshopping-wc-google-shopping' ),
															esc_attr( $language ) ) ?>
                                                    </div>
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
										<?php if ( ( $record["sheet_enable"] == "on" ) && ! empty( $record["sheet_id"] ) && ! empty( $record["sheet_range"] ) ) { ?>
                                            <div class="vi-ui buttons">
                                                <a class="vi-ui small icon button"
                                                   data-tooltip="Access"
                                                   target="_blank"
                                                   href="<?php echo esc_attr( "https://docs.google.com/spreadsheets/d/" . $record["sheet_id"] . "/edit" ) ?>">
                                                    <i class="linkify icon"></i>
                                                </a>
                                                <div class="vi-ui small icon button push-all-sheet"
                                                     data-tooltip="Push all product">
                                                    <i class="angle double up icon"></i>
													<?php printf( '<input language="%s" value="%s" hidden>',
														esc_attr__( $languages_map[ $languages ], 'gshopping-wc-google-shopping' ),
														esc_attr( $languages ) ) ?>
                                                </div>
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
                    <div class="pfvi_wizard-merchant-option" id="merchant_api">
                        <h4>Merchant API</h4>
                        <p>The Content API for Shopping enables you to automatically upload product listings and so make
                            your products easily discoverable through Shopping ads.</p>
                        <div class="vi-ui yellow message hidden"></div>
                        <table class="vi-ui compact celled table">
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
                            <tfoot class="full-width">
                            <tr>
                                <th>Merchant ID*</th>
                                <th colspan="4">
                                    <input type="text"
                                           name="<?php echo esc_attr( "merchant_id" ); ?>"
                                           value="<?php echo esc_attr( $params_config["merchant_id"] ) ?>"
                                           placeholder="6666688888">
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="pfvi_wizard-step-bottom">
                    <button class="vi-ui right floated blue basic button" step="wizard_feed_data" type="button">
						<?php
						printf( '%s', esc_html__( 'Continue', 'gshopping-wc-google-shopping' ) );
						?>
                    </button>
                    <!--                    <button class="vi-ui right floated grey basic button" step="wizard_product_data" type="button">Skip</button>-->
                </div>
            </div>
        </form>
        <div id="complete_wizard" step="wizard_complete" class="vi-ui pfvi_wizard-step">
            <div class="pfvi_wizard-step-top">
				<?php
				printf( '<h1>%s</h1>', esc_html__( 'You\'re ready to push product to Merchant', 'gshopping-wc-google-shopping' ) );
				?>
            </div>
            <div class="pfvi_wizard-step-main">
                <div class="pfvi_wizard-complete-card">
                    <div class="pfvi_wizard-complete-card_item">
                        <div class="pfvi_wizard-complete-card_content">
							<?php
							printf( '<h3>%s</h3><p>%s</p>',
								esc_html__( 'Push all products', 'gshopping-wc-google-shopping' ),
								esc_html__( 'All products already exist on your website will be push to feed', 'gshopping-wc-google-shopping' ),
							);
							?>
                        </div>
                        <div class="pfvi_wizard-complete-card_button">
							<?php
							printf( '<a class="vi-ui olive button" href="%s">%s</a>',
								esc_url( admin_url( 'admin.php?page=vi-product-feed' ) ),
								esc_html__( 'GShopping page', 'gshopping-wc-google-shopping' )
							);
							?>

                        </div>
                    </div>
                    <div class="pfvi_wizard-complete-card_item">
                        <div class="pfvi_wizard-complete-card_content">
							<?php
							printf( '<h3>%s</h3><p>%s</p>',
								esc_html__( 'Push products by filter', 'gshopping-wc-google-shopping' ),
								esc_html__( 'Select products you want to push by filter of products page', 'gshopping-wc-google-shopping' ),
							);
							?>
                        </div>
                        <div class="pfvi_wizard-complete-card_button">
							<?php
							printf( '<a class="vi-ui olive basic button" href="%s">%s</a>',
								esc_url( admin_url( 'edit.php?post_type=product' ) ),
								esc_html__( 'Products page', 'gshopping-wc-google-shopping' )
							);
							?>
                        </div>
                    </div>
                    <div class="pfvi_wizard-complete-card_item">
						<?php
						printf( '<a class="vi-ui olive button" href="%s">%s</a>',
							esc_url( admin_url() ),
							esc_html__( 'Return to the Dashboard', 'gshopping-wc-google-shopping' )
						);
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery('body').addClass('pfvi_full-screen');
</script>