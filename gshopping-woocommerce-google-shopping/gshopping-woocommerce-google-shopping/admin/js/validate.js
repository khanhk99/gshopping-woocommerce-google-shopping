jQuery(document).ready(function (jQuery) {
    /**
     * Fetch schedule
     */
    let scheduleConfig = pfvi_woo_admin_products_js.params_config.schedule;

    jQuery.each(scheduleConfig, function (k, v) {
        let id = "#schedule-" + k;
        jQuery('#fetch_schedule').on('change', id + " .pfvi_enable input", function (e) {
            let condition1 = jQuery(id + " .pfvi_action-schedule .generate-xml");
            if (condition1.length != 0) {
                jQuery('#fetch_schedule ' + id + " .pfvi_enable input").prop('checked', false);
                let msg = "Please generate file before enable";
                jQuery('#fetch_schedule .vi-ui.message').html(msg);
                jQuery('#fetch_schedule .vi-ui.message').show().delay(5000).fadeOut();
            }
        });
    });

    /**
     * Sheet
     */
    let sheetConfig = pfvi_woo_admin_products_js.params_config.sheet;
    jQuery.each(sheetConfig, function (k, v) {
        let id = "#sheet-" + k;
        // enable
        jQuery('#google_sheet').on('change', id + " .pfvi_enable input", function (e) {
            let sheetId = jQuery(id + ' .field-sheet-id').val();
            let sheetRange = jQuery(id + ' .field-sheet-range').val();
            if (pfvi_isEmpty(sheetId) || pfvi_isEmpty(sheetRange)) {
                jQuery('#google_sheet ' + id + " .pfvi_enable input").prop('checked', false);
                let msg = "Please enter a value of <b>Spreadsheet ID</b> and <b>Range</b>";
                jQuery('#google_sheet .vi-ui.message').html(msg);
                jQuery('#google_sheet .vi-ui.message').show().delay(5000).fadeOut();
            }
        });

        // Change link access sheet
        jQuery('#google_sheet').on('input', id + ' .field-sheet-id', function (e) {
            let sheetId = jQuery(id + ' .field-sheet-id').val();
            jQuery(id + ' .pfvi_action-sheet a').attr('href', `https://docs.google.com/spreadsheets/d/${sheetId}/edit`)
        });
    });

    /**
     * API
     */
    let apiConfig = pfvi_woo_admin_products_js.params_config.api;
    jQuery.each(apiConfig, function (k, v) {
        let id = "#api-" + k;
        jQuery('#merchant_api').on('change', id + " .pfvi_enable input", function (e) {
            let merchantId = jQuery('#merchant_api input[name=merchant_id]').val();
            let country = jQuery(id + ' .field-api-country').find(":selected").text();
            if ((merchantId.length == 0)) {
                jQuery('#merchant_api ' + id + " .pfvi_enable input").prop('checked', false);
                let msg = "Please enter value of <b>Merchant ID</b>";
                jQuery('#merchant_api .vi-ui.message').html(msg);
                jQuery('#merchant_api .vi-ui.message').show().delay(5000).fadeOut();
            }
            if ((country.length == 0)) {
                jQuery('#merchant_api ' + id + " .pfvi_enable input").prop('checked', false);
                let msg = "Please select <b>Target country</b>";
                jQuery('#merchant_api .vi-ui.message').html(msg);
                jQuery('#merchant_api .vi-ui.message').show().delay(5000).fadeOut();
            }
        });
    });

    /**
     * Authentication
     */
    jQuery('#config_credential').on('click', '#getClient', function (e) {
        if(pfvi_isEmpty(pfvi_woo_admin_products_js.params_config.client_id) || pfvi_isEmpty(pfvi_woo_admin_products_js.params_config.client_secret)){
            e.stopPropagation();
            let msg = "Please enter then save value of <b>Client ID</b> and <b>Client secret</b>";
            jQuery('#config_credential .message.yellow').html(msg);
            jQuery('#config_credential .message.yellow').show().delay(5000).fadeOut();
        }
    })
});