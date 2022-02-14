jQuery(document).ready(function (jQuery) {
    // setup wizard
    jQuery('.pfvi_wizard-main').on('change', '.pfvi_wizard-card input[type=checkbox]', function () {
        if (jQuery(this).is(':checked')) {
            jQuery(this).parent().addClass('active');
        } else {
            jQuery(this).parent().removeClass('active');
        }
    });

    jQuery('.pfvi_wizard-main').on('click', 'button[step=wizard_feed]', function () {
        let feed = [];
        jQuery.each(jQuery('input[name=feed]'), function (k, v) {
            if (jQuery(v).is(":checked")) {
                feed.push(jQuery(v).attr('id'));
            }
        });
        if (pfvi_isEmpty(feed)) {
            let msg = "Please choose at least 1 feed";
            jQuery('.pfvi_wizard-step[step=wizard_feed] .vi-ui.message').html(msg);
            jQuery('.pfvi_wizard-step[step=wizard_feed] .vi-ui.message').show().delay(5000).fadeOut();
        } else {
            sessionStorage.setItem("pfviFeedType", feed);
            jQuery('.pfvi_wizard-step').removeClass('active');
            if (feed.includes('feed_sheet') || feed.includes('feed_api')) {
                jQuery('#config_credential').addClass('active');
            } else {
                jQuery('#config_product_data').addClass('active');
            }
        }
    });

    jQuery('.pfvi_wizard-main').on('click', 'button[step=wizard_api]', function (e) {
        //validate
        let client_id = jQuery('input[name=client_id]').val();
        let client_secret = jQuery('input[name=client_secret]').val();
        let api_key = jQuery('input[name=api_key]').val();
        if (pfvi_isEmpty(client_id) || pfvi_isEmpty(client_secret) || pfvi_isEmpty(api_key)) {
            e.stopPropagation();
            let msg = "Please enter full value";
            jQuery('.pfvi_wizard-step[step=wizard_api] .message.yellow').html(msg);
            jQuery('.pfvi_wizard-step[step=wizard_api] .message.yellow').show().delay(5000).fadeOut();
        } else {
            jQuery(this).addClass('loading');

            let dataForm = jQuery("#wizard_credential").serializeArray();
            let data = {};
            jQuery.each(dataForm, function (k, v) {
                data[v.name] = v.value;
            });

            data['_ajax_nonce'] = jQuery('input[name=pfvi_nonce_credential]').val();
            data['action'] = "pfvi_wizard_credential";
            jQuery.ajax({
                url: pfvi_woo_admin_products_js.ajax_url,
                type: 'POST',
                data: data,
                success(response) {
                    jQuery('#getClient').addClass('loading');
                    showAuthWindow({
                        path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                        callback: function () {
                            jQuery('#getClient').removeClass('loading');
                            jQuery('.pfvi_wizard-step').removeClass('active');
                            jQuery('#config_product_data').addClass('active');
                        }
                    });
                },
                error(error) {
                    console.log(error);
                    jQuery('.pfvi_wizard-main button[step=wizard_api]').removeClass('loading');
                },
            });
        }
    });

    jQuery('.pfvi_wizard-main').on('click', 'button[step=wizard_product_data]', function () {
        jQuery('.pfvi_wizard-step').removeClass('active');
        let typeFeed = sessionStorage.getItem("pfviFeedType");
        if (typeFeed.includes('feed_sheet')) {
            jQuery('#google_sheet table').addClass('active');
        }
        if(typeFeed.includes('feed_api')){
            jQuery('#merchant_api table').addClass('active');
        }
        if(typeFeed.includes('feed_schedule')){
            jQuery('#fetch_schedule table').addClass('active');
        }
        jQuery('#config_google_merchant').addClass('active');
    });

    jQuery('.pfvi_wizard-main').on('click', 'button[step=wizard_feed_data]', function (e) {
        jQuery(this).addClass('loading');
        jQuery.ajax({
            url: pfvi_woo_admin_products_js.ajax_url,
            type: 'POST',
            // data: data,
            data: new FormData(jQuery("#wizard_data")[0]),
            contentType: false,
            processData: false,
            success(response) {
                console.log("success");
                jQuery('.pfvi_wizard-main button[step=wizard_feed_data]').removeClass('loading');
                jQuery('.pfvi_wizard-step').removeClass('active');
                jQuery('#complete_wizard').addClass('active');
            },
            error(error) {
                console.log("error");
                console.log(error);
                jQuery('.pfvi_wizard-main button[step=wizard_feed_data]').removeClass('loading');
            },
        });
    });
});