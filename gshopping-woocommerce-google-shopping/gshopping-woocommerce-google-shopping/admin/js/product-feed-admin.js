jQuery(document).ready(function (jQuery) {
    /**
     *
     */
    jQuery('.menu .item').tab({
        history: true,
        historyType: 'hash',
    });
    jQuery('.vi-ui.accordion').accordion();
    jQuery('.vi-ui.dropdown').dropdown({
        clearable: true
    });

    jQuery('#product_attributes_select').on('click', '#select-all', function () {
        let options = jQuery('#product_attributes_select select > option').toArray().map(
            (obj) => obj.value
        );
        jQuery('#product_attributes_select select').dropdown('set exactly', options);
    });

    jQuery('#product_attributes_select').on('click', '#clear-all', function () {
        jQuery('#product_attributes_select select').dropdown('clear');
    })
    // end vi-ui js

    jQuery("#posts-filter").on("click", '.button.action[type="submit"]', function (event) {
        let bulkOption = "";
        if (jQuery(this).attr('id') === "doaction") {
            bulkOption = jQuery("#posts-filter #bulk-action-selector-top").val();
        } else {
            bulkOption = jQuery("#posts-filter #bulk-action-selector-bottom").val();
        }

        /**
         * Add to XML
         */
        if (bulkOption === "add_to_xml") {
            event.preventDefault();
            jQuery('#wpbody').append(pfvi_addLoading());
            let productIdChecked = pfvi_getProductChecked();

            pfvi_pushToXml(1, productIdChecked);
        }

        /**
         * Push to google sheet
         */
        if (bulkOption === "add_to_sheet") {
            event.preventDefault();

            // check config api key?
            if (pfvi_checkCredentialsConfig() === false) {
                return false;
            }
            if (pfvi_checkAccessToken()) {
                jQuery('#wpbody').append(pfvi_addLoading());

                // get data of products
                let productIdChecked = pfvi_getProductChecked();
                pfvi_makeApiCallAppendSheet(1, productIdChecked);
            } else {
                showAuthWindow({
                    path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                    callback: function () {
                        jQuery('#wpbody').append(pfvi_addLoading());

                        // get data of products
                        let productIdChecked = pfvi_getProductChecked();
                        pfvi_makeApiCallAppendSheet(1, productIdChecked);
                    }
                });
            }
        }

        /**
         * push to merchant
         */
        if (bulkOption === "push_to_merchant") {
            event.preventDefault();

            // check config api key?
            if (pfvi_checkCredentialsConfig() === false) {
                return false;
            }
            if (pfvi_checkAccessToken()) {
                jQuery('#wpbody').append(pfvi_addLoading());

                // get data of products
                let productIdChecked = pfvi_getProductChecked();
                pfvi_preparePushMerchant(1, productIdChecked);
            } else {
                showAuthWindow({
                    path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                    callback: function () {
                        jQuery('#wpbody').append(pfvi_addLoading());

                        // get data of products
                        let productIdChecked = pfvi_getProductChecked();
                        pfvi_preparePushMerchant(1, productIdChecked);
                    }
                });
            }
        }
    });

    jQuery("#pfvi_config_form").on("click", 'button[name="pfvi_reset_save_setting_feed"]', function () {
        if (confirm("Are you sure you want to Reset all data?") === true) {
            return true;
        } else {
            return false;
        }
    });

    /**
     * push all product
     */
    jQuery('.wrap').on('click','.push-all-schedule',function () {
        let languageText = jQuery(this).find('input').attr('language');
        if (confirm('Do you want to upload all ' + languageText + ' products?')) {
            jQuery('#wpbody').append(pfvi_addLoading());
            let language = jQuery(this).find('input').val();

            let data = {};
            data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
            data['action'] = "pfvi_get_all_product";
            data['language'] = language;
            jQuery.ajax({
                url: pfvi_woo_admin_products_js.ajax_url,
                type: 'GET',
                data: data,
                dataType: 'json',
                success(res) {
                    console.log(res);
                    pfvi_pushToXml(1, res);
                },
                error(error) {
                    console.log("error");
                    console.log(error);
                },
            });
        }
    });

    jQuery('.wrap').on('click','.push-all-sheet', function () {
        let languageText = jQuery(this).find('input').attr('language');

        if (confirm(`Do you want to upload all ${languageText} products?`)) {
            // check config api key?
            if (pfvi_checkCredentialsConfig() === false) {
                return false;
            }
            if (pfvi_checkAccessToken()) {
                jQuery('#wpbody').append(pfvi_addLoading());

                let language = jQuery(this).find('input').val();

                let data = {};
                data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
                data['action'] = "pfvi_get_all_product";
                data['language'] = language;
                jQuery.ajax({
                    url: pfvi_woo_admin_products_js.ajax_url,
                    type: 'GET',
                    data: data,
                    success: function (res) {
                        pfvi_makeApiCallAppendSheet(1, res);
                    },
                    error(error) {
                        console.log(error);
                    },
                });
            } else {
                showAuthWindow({
                    path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                    callback: function () {
                        jQuery('#wpbody').append(pfvi_addLoading());

                        let language = jQuery(this).find('input').val();

                        let data = {};
                        data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
                        data['action'] = "pfvi_get_all_product";
                        data['language'] = language;
                        jQuery.ajax({
                            url: pfvi_woo_admin_products_js.ajax_url,
                            type: 'GET',
                            data: data,
                            success: function (res) {
                                pfvi_makeApiCallAppendSheet(1, res)
                            },
                            error(error) {
                                console.log(error);
                            },
                        });
                    }
                });
            }
        }
    });

    jQuery('.wrap').on('click','.push-all-api', function () {
        let languageText = jQuery(this).find('input').attr('language');
        if (confirm(`Do you want to upload all ${languageText} products?`)) {
            // check config api key?
            if (pfvi_checkCredentialsConfig() === false) {
                return false;
            }
            if (pfvi_checkAccessToken()) {
                jQuery('#wpbody').append(pfvi_addLoading());

                let language = jQuery(this).find('input').val();

                let data = {};
                data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
                data['action'] = "pfvi_get_all_product";
                data['language'] = language;
                jQuery.ajax({
                    url: pfvi_woo_admin_products_js.ajax_url,
                    type: 'GET',
                    data: data,
                    success: function (res) {
                        pfvi_preparePushMerchant(1, res)
                    },
                    error(error) {
                        console.log(error);
                    },
                });
            } else {
                showAuthWindow({
                    path: pfvi_woo_admin_products_js.params_config.redirect_uri,
                    callback: function () {
                        jQuery('#wpbody').append(pfvi_addLoading());

                        let language = jQuery(this).find('input').val();

                        let data = {};
                        data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
                        data['action'] = "pfvi_get_all_product";
                        data['language'] = language;
                        jQuery.ajax({
                            url: pfvi_woo_admin_products_js.ajax_url,
                            type: 'GET',
                            data: data,
                            success: function (res) {
                                pfvi_preparePushMerchant(1, res)
                            },
                            error(error) {
                                console.log(error);
                            },
                        });
                    }
                });
            }
        }
    });

    jQuery('body').on('click', '#getClient',function () {
        jQuery(this).addClass('loading');
        showAuthWindow({
            path: pfvi_woo_admin_products_js.params_config.redirect_uri,
            callback: function () {
                jQuery('#getClient').removeClass('loading');
            }
        });
    });

    /**
     * mapping attributes
     */
    // jQuery('#map_attributes').css('display', 'none');
    // let attributes_map = pfvi_woo_admin_products_js.params_config.attributes_map;
    // jQuery.each(attributes_map, function (k, v) {
    //     let length = jQuery(`#product_attributes_select a[data-value=${k}]`).length;
    //     console.log(length);
    // });
});

function pfvi_getProductChecked() {
    // get data of products
    let productChecked = jQuery('#the-list input[name="post\[\]"]:checked');
    let productIdChecked = [];

    jQuery.each(productChecked, function (key, value) {
        productIdChecked.push(jQuery(value).val())
    });

    return productIdChecked;
}

/**
 * Access api
 */
function pfvi_pushToXml(currentRequest, productIdChecked) {
    let data = {};

    let productIds = productIdChecked.length;
    let productPerRequest = 30;
    let countRequest = Math.ceil(productIds / productPerRequest);

    if (countRequest < 1) {
        countRequest = 1;
    }

    data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
    data['action'] = "pfvi_push_xml";

    let startRecord = (currentRequest - 1) * productPerRequest;
    let endRecord = startRecord + (productPerRequest - 1);
    if (endRecord > productIds) {
        endRecord = productIds - 1;
    }
    let listIdRequest = [];
    for (let record = startRecord; record <= endRecord; record++) {
        listIdRequest.push(productIdChecked[record]);
    }
    data['productIds'] = listIdRequest;

    jQuery.ajax({
        url: pfvi_woo_admin_products_js.ajax_url,
        type: 'GET',
        data: data,
        success(response) {
            console.log(JSON.parse(response));
            if (currentRequest < countRequest) {
                pfvi_pushToXml(++currentRequest, productIdChecked)
            }
        },
        error(error) {
            jQuery('#pfvi_loading_page').remove();
        },
        complete() {
            pfvi_progress(currentRequest, countRequest);
        }
    });
};

// Check api_key and client_id exist?
function pfvi_checkCredentialsConfig() {
    if (pfvi_isEmpty(pfvi_woo_admin_products_js.params_config.api_key) || pfvi_isEmpty(pfvi_woo_admin_products_js.params_config.client_id) || pfvi_isEmpty(pfvi_woo_admin_products_js.params_config.client_secret)) {
        if (confirm("You have not config Credentials. Do you want going to config?")) {
            window.location.href = pfvi_woo_admin_products_js.url_page_config + "#config_credential";
            return false;
        } else {
            return false;
        }
    }
}

function pfvi_checkCredentialsConfig_html() {
    let client_id = jQuery('input[name=client_id]').val();
    let client_secret = jQuery('input[name=client_secret]').val();
    let api_key = jQuery('input[name=api_key]').val();
    if (pfvi_isEmpty(client_id) || pfvi_isEmpty(client_secret) || pfvi_isEmpty(api_key)) {
        if (confirm("You have not config Credentials. Do you want going to config?")) {
            window.location.href = pfvi_woo_admin_products_js.url_page_config + "#config_credential";
            return false;
        } else {
            return false;
        }
    }
}

function pfvi_isEmpty(str) {
    return ((!str) || (str.length === 0) || (str === null) || (str === ""))
}

/**
 * api google sheet
 */
// add product data to Sheet
function pfvi_makeApiCallAppendSheet(currentRequest, productIdChecked) {
    if (productIdChecked.length > 0) {
        let data = {};

        let productIds = productIdChecked.length;
        let productPerRequest = 30;
        let countRequest = Math.ceil(productIds / productPerRequest);
        if (countRequest < 1) {
            countRequest = 1;
        }

        let startRecord = (currentRequest - 1) * productPerRequest;
        let endRecord = startRecord + (productPerRequest - 1);

        if (endRecord > productIds) {
            endRecord = productIds - 1;
        }
        let listIdRequest = [];
        for (let record = startRecord; record <= endRecord; record++) {
            listIdRequest.push(productIdChecked[record]);
        }

        data['productIds'] = listIdRequest;
        data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
        data['action'] = "pfvi_push_sheet";

        jQuery.ajax({
            url: pfvi_woo_admin_products_js.ajax_url,
            type: 'GET',
            data: data,
            success(res) {
                console.log(res);
                // console.log(JSON.parse(res));
                // recursive
                if (currentRequest < countRequest) {
                    pfvi_makeApiCallAppendSheet(++currentRequest, productIdChecked);
                }
            },
            error(error) {
                console.log(error)
                jQuery('#pfvi_loading_page').remove();
            },
            complete() {
                pfvi_progress(currentRequest, countRequest);
            }
        });
    } else {
        jQuery('#pfvi_loading_page').remove();
    }
}

// end api google sheet

/**
 * API Merchant
 */
// add product data to Merchant
function pfvi_preparePushMerchant(currentRequest, productIdChecked) {
    if (productIdChecked.length > 0) {
        let data = {};

        let productIds = productIdChecked.length;
        let productPerRequest = 30;
        let countRequest = Math.ceil(productIds / productPerRequest);
        if (countRequest < 1) {
            countRequest = 1;
        }

        let startRecord = (currentRequest - 1) * productPerRequest;
        let endRecord = startRecord + (productPerRequest - 1);

        if (endRecord > productIds) {
            endRecord = productIds - 1;
        }
        let listIdRequest = [];
        for (let record = startRecord; record <= endRecord; record++) {
            listIdRequest.push(productIdChecked[record]);
        }

        data['productIds'] = listIdRequest;
        data['_ajax_nonce'] = pfvi_woo_admin_products_js.nonce;
        data['action'] = "pfvi_push_merchant";

        jQuery.ajax({
            url: pfvi_woo_admin_products_js.ajax_url,
            type: 'GET',
            data: data,
            success(res) {
                // recursive
                if (currentRequest < countRequest) {
                    pfvi_preparePushMerchant(++currentRequest, productIdChecked);
                }
            },
            error(error) {
                jQuery('#pfvi_loading_page').remove();
            },
            complete() {
                pfvi_progress(currentRequest, countRequest);
            }
        });
    } else {
        jQuery('#pfvi_loading_page').remove();
    }
}

// end api merchant
function pfvi_runProgress(current, percent, process, total) {
    jQuery('.pfvi_progress .pfvi_bar').css('width', current + "%");
    jQuery('.pfvi_progress .pfvi_progress-count').html(current);

    if ((current === 100) && (process === total)) {
        setTimeout(function () {
            jQuery('#pfvi_loading_page').remove();
        }, 2000)
    }
    current++;
    if (current <= percent) {
        setTimeout(function () {
            pfvi_runProgress(current, percent, process, total)
        }, pfvi_getRandomArbitrary(10, total * 50));
    }
}

function pfvi_progress(process, total) {
    let percent = parseInt(parseInt(process) / parseInt(total) * 100);
    let pre = parseInt((parseInt(process) - 1) / parseInt(total) * 100);
    let current = parseInt(jQuery('.pfvi_progress .pfvi_progress-count').html());
    let start;
    if (current > pre) {
        start = current;
    } else {
        start = pre;
    }
    if (!current) {
        current = 0;
    }

    pfvi_runProgress(start, percent, process, total);
}

function pfvi_addLoading() {
    return '<div id="pfvi_loading_page"><div class="pfvi_progress"><div class="pfvi_bar"><div class="pfvi_progress-calculate"><span class="pfvi_progress-count">0</span><span class="pfvi_progress-unit"> %</span></div></div></div></div>';
}

function pfvi_removeLoading(currentRequest, countRequest) {
    if (currentRequest === countRequest) {
        jQuery('#pfvi_loading_page').remove();
    }
}

function pfvi_getRandomArbitrary(min, max) {
    let number = Math.random() * (max - min) + min;
    return number;
}

function pfvi_checkAccessToken() {
    let access_token = pfvi_woo_admin_products_js.params_config.access_token;
    if (!pfvi_isEmpty(access_token)) {
        let expires_in = access_token['expires_in'];
        let created = access_token['created'];
        let end = expires_in + created;
        let now = (Date.now()) / 1000;

        if (now > end) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

//Authorization popup window code
function showAuthWindow(options) {
    options.windowName = options.windowName || 'ConnectWithOAuth'; // should not include space for IE
    options.windowOptions = options.windowOptions || 'width=600,height=600';
    options.callback = options.callback || function () {
        window.location.reload();
    };

    var that = this;

    that._oauthWindow = window.open(options.path, options.windowName, options.windowOptions);
    that._oauthInterval = window.setInterval(function () {
        if (that._oauthWindow.closed) {
            window.clearInterval(that._oauthInterval);
            options.callback();
        }
    }, 1000);
}