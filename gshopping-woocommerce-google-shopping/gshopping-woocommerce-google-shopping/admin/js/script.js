jQuery(document).ready(function (jQuery) {
    jQuery('.vi-ui.form').on('click', '.copy-xml', function () {
        let val = jQuery(this).find('input').val();
        navigator.clipboard.writeText(val);
        jQuery(this).attr('data-tooltip', 'Copied');
    });

    jQuery('.vi-ui.form').on('mouseover', '.copy-xml', function () {
        jQuery('.copy-xml').attr('data-tooltip', 'Copy url');
    });

    jQuery('.vi-ui.form').on('click', '.clear-xml', function () {
        if (confirm("Do you want clear all data this file XML?")) {
            jQuery(this).find('i').addClass('loading');
            let language = jQuery(this).find('input').val();

            let data = {};
            data['_ajax_nonce'] = pfvi_destination.nonce;
            data['action'] = "pfvi_clear_data_xml";
            data['language'] = language;
            jQuery.ajax({
                url: pfvi_destination.ajax_url,
                type: 'POST',
                data: data,
                success(response) {
                    console.log(response);
                },
                error(error) {
                    console.log(error);
                },
                complete(res) {
                    console.log(res.responseText);
                    jQuery('.clear-xml i').removeClass('loading');
                }
            });
        }
    });

    jQuery('.vi-ui.form').on('click', '.generate-xml', function () {
        jQuery(this).find('i').removeClass('plus');
        jQuery(this).find('i').addClass('notched circle loading');
        let language = jQuery(this).find('input').val();

        let data = {};
        data['_ajax_nonce'] = pfvi_destination.nonce;
        data['action'] = "pfvi_generate_file_xml";
        data['language'] = language;
        jQuery.ajax({
            url: pfvi_destination.ajax_url,
            type: 'POST',
            data: data,
            success(response) {
                // console.log(response);
                jQuery("#schedule-" + language + " .pfvi_action-schedule").html(JSON.parse(response));
            },
            error(error) {
                jQuery(`.generate-xml input[value="${language}"]`).prev('i').addClass('plus');
                console.log(error);
            },
            complete() {
                jQuery(`.generate-xml input[value="${language}"]`).prev('i').removeClass('notched circle loading');
            }
        });
    });

    jQuery('body').on('click', '.create-sheet', function () {
        console.log("create sheet");
        let language = jQuery(this).find('input').val();
        let languageName = jQuery(this).find('input').attr('language');
        console.log(languageName);
        let id = "#sheet-" + language;
        // check config api key?
        if (pfvi_checkCredentialsConfig_html() === false) {
            return false;
        }

        if (pfvi_checkAccessToken()) {
            jQuery(id + ' .create-sheet i').removeClass('plus');
            jQuery(id + ' .create-sheet i').addClass('redo loading');

            let data = {};
            data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
            data['action'] = "pfvi_create_sheet";
            data['language'] = languageName;
            jQuery.ajax({
                url: pfvi_woo_admin_products_js.ajax_url,
                type: 'GET',
                data: data,
                success: function (res) {
                    console.log(res);
                    let response = JSON.parse(res);
                    console.log(response);
                    jQuery(id + ' .field-sheet-id').val(response.spreadsheetId);
                    jQuery(id + ' .field-sheet-range').val("Sheet1");
                },
                error(error) {
                    console.log("error");
                    console.log(error);
                },
                complete() {
                    jQuery(id + ' .pfvi_action-sheet .create-sheet i').removeClass('redo loading').addClass('plus');
                }
            });
        } else {
            showAuthWindow({
                path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                callback: function () {
                    jQuery(id + ' .create-sheet i').removeClass('plus');
                    jQuery(id + ' .create-sheet i').addClass('redo loading');

                    let data = {};
                    data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
                    data['action'] = "pfvi_create_sheet";
                    data['language'] = languageName;
                    jQuery.ajax({
                        url: pfvi_woo_admin_products_js.ajax_url,
                        type: 'GET',
                        data: data,
                        success: function (res) {
                            console.log(res);
                            let response = JSON.parse(res)
                            console.log(response);
                            jQuery(id + ' .field-sheet-id').val(response.spreadsheetId);
                            jQuery(id + ' .field-sheet-range').val("Sheet1");
                        },
                        error(error) {
                            console.log("error");
                            console.log(error);
                        },
                        complete() {
                            jQuery(id + ' .pfvi_action-sheet .create-sheet i').removeClass('redo loading').addClass('plus');
                        }
                    });
                }
            });
        }
    });
});