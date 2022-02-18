<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once PFVI_INCLUDES . 'class-product-feed.php';

class Product_Feed_Config
{
    private $params;
    private $defaults;
    private $attributes;
    private $attributes_map;

    private $schedule;
    private $sheet;
    private $api;
    private $country;
    private $language;

    public function __construct()
    {
        $option = get_option(PFVI_PREFIX_META . 'woocommerce_google_shopping');

        $this->attributes_map = array(
            'age_group' => '',
            'color' => '',
            'gender' => '',
            'material' => '',
            'pattern' => '',
            'size' => '',
        );

        $this->schedule = array(
            "schedule_enable" => "on",
            "link_xml" => PFVI_URL_UPLOAD . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'feed-' . PFVI_LANGUAGE . '.xml',
        );

        $this->sheet = array(
            "sheet_enable" => "",
            "sheet_id" => "",
            "sheet_range" => "Sheet1",
        );

        $this->api = array(
            "api_enable" => "",
            "api_country" => "",
            "channel" => "online",
        );

        $this->defaults = array(
            "api_key" => esc_html(""),
            "client_id" => esc_html(""),
            "client_secret" => esc_html(""),
            "access_token" => esc_html(""),
            "refresh_token" => esc_html(""),
            "redirect_uri" => admin_url('admin.php?page=vi-product-feed&view=oauth'),
            "merchant_id" => esc_html(""),

            "schedule" => array(
                PFVI_LANGUAGE => $this->schedule
            ),

            "sheet" => array(
                PFVI_LANGUAGE => $this->sheet
            ),

            "api" => array(
                PFVI_LANGUAGE => $this->api
            ),

            "attributes" => array(
                "offer_id",
                "title",
                "description",
                "link",
                "image_link",
                "availability",
                "price",
                "condition",
                "item_group_id"
            ),

            "attributes_map" => array(
                'age_group' => '',
                'color' => '',
                'gender' => '',
                'material' => '',
                'pattern' => '',
                'size' => '',
            ),
        );

        $this->params = array(
            "api_key" => $option["api_key"] ?? "",
            "client_id" => $option["client_id"] ?? "",
            "client_secret" => $option["client_secret"] ?? "",
            "access_token" => $option["access_token"] ?? "",
            "refresh_token" => $option["refresh_token"] ?? "",
            "redirect_uri" => $option["redirect_uri"] ?? $this->defaults["redirect_uri"],
            "merchant_id" => $option["merchant_id"] ?? "",
            "schedule" => (isset($option["schedule"]) && !empty($option["schedule"])) ? $option["schedule"] : $this->defaults["schedule"],
            "sheet" => (isset($option["sheet"]) && !empty($option["sheet"])) ? $option["sheet"] : $this->defaults["sheet"],
            "api" => (isset($option["api"]) && !empty($option["api"])) ? $option["api"] : $this->defaults["api"],
            "attributes" => (isset($option["attributes"]) && !empty($option["attributes"])) ? $option["attributes"] : $this->defaults["attributes"],
            "attributes_map" => $option["attributes_map"] ?? $this->defaults["attributes_map"],
        );

        $this->country = array(
            "AC" => esc_html("Ascension Island"),
            "AD" => esc_html("Andorra"),
            "AE" => esc_html("United Arab Emirates"),
            "AF" => esc_html("Afghanistan"),
            "AG" => esc_html("Antigua &amp; Barbuda"),
            "AI" => esc_html("Anguilla"),
            "AL" => esc_html("Albania"),
            "AM" => esc_html("Armenia"),
            "AO" => esc_html("Angola"),
            "AQ" => esc_html("Antarctica"),
            "AR" => esc_html("Argentina"),
            "AS" => esc_html("American Samoa"),
            "AT" => esc_html("Austria"),
            "AU" => esc_html("Australia"),
            "AW" => esc_html("Aruba"),
            "AX" => esc_html("Åland Islands"),
            "AZ" => esc_html("Azerbaijan"),
            "BA" => esc_html("Bosnia"),
            "BB" => esc_html("Barbados"),
            "BD" => esc_html("Bangladesh"),
            "BE" => esc_html("Belgium"),
            "BF" => esc_html("Burkina Faso"),
            "BG" => esc_html("Bulgaria"),
            "BH" => esc_html("Bahrain"),
            "BI" => esc_html("Burundi"),
            "BJ" => esc_html("Benin"),
            "BL" => esc_html("St. Barthélemy"),
            "BM" => esc_html("Bermuda"),
            "BN" => esc_html("Brunei"),
            "BO" => esc_html("Bolivia"),
            "BQ" => esc_html("Caribbean Netherlands"),
            "BR" => esc_html("Brazil"),
            "BS" => esc_html("Bahamas"),
            "BT" => esc_html("Bhutan"),
            "BV" => esc_html("Bouvet Island"),
            "BW" => esc_html("Botswana"),
            "BY" => esc_html("Belarus"),
            "BZ" => esc_html("Belize"),
            "CA" => esc_html("Canada"),
            "CC" => esc_html("Cocos (Keeling) Islands"),
            "CD" => esc_html("Congo - Kinshasa"),
            "CF" => esc_html("Central African Republic"),
            "CG" => esc_html("Congo - Brazzaville"),
            "CH" => esc_html("Switzerland"),
            "CI" => esc_html("Ivory Coast"),
            "CK" => esc_html("Cook Islands"),
            "CL" => esc_html("Chile"),
            "CM" => esc_html("Cameroon"),
            "CN" => esc_html("China"),
            "CO" => esc_html("Colombia"),
            "CP" => esc_html("Clipperton Island"),
            "CR" => esc_html("Costa Rica"),
            "CU" => esc_html("Cuba"),
            "CV" => esc_html("Cape Verde"),
            "CW" => esc_html("Curaçao"),
            "CX" => esc_html("Christmas Island"),
            "CY" => esc_html("Cyprus"),
            "CZ" => esc_html("Czechia"),
            "DE" => esc_html("Germany"),
            "DG" => esc_html("Diego Garcia"),
            "DJ" => esc_html("Djibouti"),
            "DK" => esc_html("Denmark"),
            "DM" => esc_html("Dominica"),
            "DO" => esc_html("Dominican Republic"),
            "DZ" => esc_html("Algeria"),
            "EA" => esc_html("Ceuta &amp; Melilla"),
            "EC" => esc_html("Ecuador"),
            "EE" => esc_html("Estonia"),
            "EG" => esc_html("Egypt"),
            "EH" => esc_html("Western Sahara"),
            "ER" => esc_html("Eritrea"),
            "ES" => esc_html("Spain"),
            "ET" => esc_html("Ethiopia"),
            "EU" => esc_html("European Union"),
            "EZ" => esc_html("Eurozone"),
            "FI" => esc_html("Finland"),
            "FJ" => esc_html("Fiji"),
            "FK" => esc_html("Falkland Islands"),
            "FM" => esc_html("Micronesia"),
            "FO" => esc_html("Faroe Islands"),
            "FR" => esc_html("France"),
            "GA" => esc_html("Gabon"),
            "GB" => esc_html("United Kingdom"),
            "GD" => esc_html("Grenada"),
            "GE" => esc_html("Georgia"),
            "GF" => esc_html("French Guiana"),
            "GG" => esc_html("Guernsey"),
            "GH" => esc_html("Ghana"),
            "GI" => esc_html("Gibraltar"),
            "GL" => esc_html("Greenland"),
            "GM" => esc_html("Gambia"),
            "GN" => esc_html("Guinea"),
            "GP" => esc_html("Guadeloupe"),
            "GQ" => esc_html("Equatorial Guinea"),
            "GR" => esc_html("Greece"),
            "GS" => esc_html("South Georgia &amp; South Sandwich Islands"),
            "GT" => esc_html("Guatemala"),
            "GU" => esc_html("Guam"),
            "GW" => esc_html("Guinea-Bissau"),
            "GY" => esc_html("Guyana"),
            "HK" => esc_html("Hong Kong"),
            "HM" => esc_html("Heard &amp; McDonald Islands"),
            "HN" => esc_html("Honduras"),
            "HR" => esc_html("Croatia"),
            "HT" => esc_html("Haiti"),
            "HU" => esc_html("Hungary"),
            "IC" => esc_html("Canary Islands"),
            "ID" => esc_html("Indonesia"),
            "IE" => esc_html("Ireland"),
            "IL" => esc_html("Israel"),
            "IM" => esc_html("Isle of Man"),
            "IN" => esc_html("India"),
            "IO" => esc_html("British Indian Ocean Territory"),
            "IQ" => esc_html("Iraq"),
            "IR" => esc_html("Iran"),
            "IS" => esc_html("Iceland"),
            "IT" => esc_html("Italy"),
            "JE" => esc_html("Jersey"),
            "JM" => esc_html("Jamaica"),
            "JO" => esc_html("Jordan"),
            "JP" => esc_html("Japan"),
            "KE" => esc_html("Kenya"),
            "KG" => esc_html("Kyrgyzstan"),
            "KH" => esc_html("Cambodia"),
            "KI" => esc_html("Kiribati"),
            "KM" => esc_html("Comoros"),
            "KN" => esc_html("St. Kitts &amp; Nevis"),
            "KP" => esc_html("North Korea"),
            "KR" => esc_html("South Korea"),
            "KW" => esc_html("Kuwait"),
            "KY" => esc_html("Cayman Islands"),
            "KZ" => esc_html("Kazakhstan"),
            "LA" => esc_html("Laos"),
            "LB" => esc_html("Lebanon"),
            "LC" => esc_html("St. Lucia"),
            "LI" => esc_html("Liechtenstein"),
            "LK" => esc_html("Sri Lanka"),
            "LR" => esc_html("Liberia"),
            "LS" => esc_html("Lesotho"),
            "LT" => esc_html("Lithuania"),
            "LU" => esc_html("Luxembourg"),
            "LV" => esc_html("Latvia"),
            "LY" => esc_html("Libya"),
            "MA" => esc_html("Morocco"),
            "MC" => esc_html("Monaco"),
            "MD" => esc_html("Moldova"),
            "ME" => esc_html("Montenegro"),
            "MF" => esc_html("St. Martin"),
            "MG" => esc_html("Madagascar"),
            "MH" => esc_html("Marshall Islands"),
            "MK" => esc_html("North Macedonia"),
            "ML" => esc_html("Mali"),
            "MM" => esc_html("Myanmar"),
            "MN" => esc_html("Mongolia"),
            "MO" => esc_html("Macao"),
            "MP" => esc_html("Northern Mariana Islands"),
            "MQ" => esc_html("Martinique"),
            "MR" => esc_html("Mauritania"),
            "MS" => esc_html("Montserrat"),
            "MT" => esc_html("Malta"),
            "MU" => esc_html("Mauritius"),
            "MV" => esc_html("Maldives"),
            "MW" => esc_html("Malawi"),
            "MX" => esc_html("Mexico"),
            "MY" => esc_html("Malaysia"),
            "MZ" => esc_html("Mozambique"),
            "NA" => esc_html("Namibia"),
            "NC" => esc_html("New Caledonia"),
            "NE" => esc_html("Niger"),
            "NF" => esc_html("Norfolk Island"),
            "NG" => esc_html("Nigeria"),
            "NI" => esc_html("Nicaragua"),
            "NL" => esc_html("Netherlands"),
            "NO" => esc_html("Norway"),
            "NP" => esc_html("Nepal"),
            "NR" => esc_html("Nauru"),
            "NU" => esc_html("Niue"),
            "NZ" => esc_html("New Zealand"),
            "OM" => esc_html("Oman"),
            "PA" => esc_html("Panama"),
            "PE" => esc_html("Peru"),
            "PF" => esc_html("French Polynesia"),
            "PG" => esc_html("Papua New Guinea"),
            "PH" => esc_html("Philippines"),
            "PK" => esc_html("Pakistan"),
            "PL" => esc_html("Poland"),
            "PM" => esc_html("St. Pierre &amp; Miquelon"),
            "PN" => esc_html("Pitcairn Islands"),
            "PR" => esc_html("Puerto Rico"),
            "PS" => esc_html("Palestine"),
            "PT" => esc_html("Portugal"),
            "PW" => esc_html("Palau"),
            "PY" => esc_html("Paraguay"),
            "QA" => esc_html("Qatar"),
            "QO" => esc_html("Outlying Oceania"),
            "RE" => esc_html("Réunion"),
            "RO" => esc_html("Romania"),
            "RS" => esc_html("Serbia"),
            "RU" => esc_html("Russia"),
            "RW" => esc_html("Rwanda"),
            "SA" => esc_html("Saudi Arabia"),
            "SB" => esc_html("Solomon Islands"),
            "SC" => esc_html("Seychelles"),
            "SD" => esc_html("Sudan"),
            "SE" => esc_html("Sweden"),
            "SG" => esc_html("Singapore"),
            "SH" => esc_html("St. Helena"),
            "SI" => esc_html("Slovenia"),
            "SJ" => esc_html("Svalbard &amp; Jan Mayen"),
            "SK" => esc_html("Slovakia"),
            "SL" => esc_html("Sierra Leone"),
            "SM" => esc_html("San Marino"),
            "SN" => esc_html("Senegal"),
            "SO" => esc_html("Somalia"),
            "SR" => esc_html("Suriname"),
            "SS" => esc_html("South Sudan"),
            "ST" => esc_html("São Tomé &amp; Príncipe"),
            "SV" => esc_html("El Salvador"),
            "SX" => esc_html("Sint Maarten"),
            "SY" => esc_html("Syria"),
            "SZ" => esc_html("Swaziland"),
            "TA" => esc_html("Tristan da Cunha"),
            "TC" => esc_html("Turks &amp; Caicos Islands"),
            "TD" => esc_html("Chad"),
            "TF" => esc_html("French Southern Territories"),
            "TG" => esc_html("Togo"),
            "TH" => esc_html("Thailand"),
            "TJ" => esc_html("Tajikistan"),
            "TK" => esc_html("Tokelau"),
            "TL" => esc_html("Timor-Leste"),
            "TM" => esc_html("Turkmenistan"),
            "TN" => esc_html("Tunisia"),
            "TO" => esc_html("Tonga"),
            "TR" => esc_html("Turkey"),
            "TT" => esc_html("Trinidad &amp; Tobago"),
            "TV" => esc_html("Tuvalu"),
            "TW" => esc_html("Taiwan"),
            "TZ" => esc_html("Tanzania"),
            "UA" => esc_html("Ukraine"),
            "UG" => esc_html("Uganda"),
            "UM" => esc_html("U.S. Outlying Islands"),
            "UN" => esc_html("United Nations"),
            "US" => esc_html("United States"),
            "UY" => esc_html("Uruguay"),
            "UZ" => esc_html("Uzbekistan"),
            "VA" => esc_html("Vatican City"),
            "VC" => esc_html("St. Vincent &amp; Grenadines"),
            "VE" => esc_html("Venezuela"),
            "VG" => esc_html("British Virgin Islands"),
            "VI" => esc_html("U.S. Virgin Islands"),
            "VN" => esc_html("Vietnam"),
            "VU" => esc_html("Vanuatu"),
            "WF" => esc_html("Wallis &amp; Futuna"),
            "WS" => esc_html("Samoa"),
            "XA" => esc_html("Pseudo-Accents"),
            "XB" => esc_html("Pseudo-Bidi"),
            "XK" => esc_html("Kosovo"),
            "YE" => esc_html("Yemen"),
            "YT" => esc_html("Mayotte"),
            "ZA" => esc_html("South Africa"),
            "ZM" => esc_html("Zambia"),
            "ZW" => esc_html("Zimbabwe"),
        );

        $this->language = array(
            "aa" => esc_html("Afar"),
            "ab" => esc_html("Abkhazian"),
            "ae" => esc_html("Avestan"),
            "af" => esc_html("Afrikaans"),
            "ak" => esc_html("Akan"),
            "am" => esc_html("Amharic"),
            "an" => esc_html("Aragonese"),
            "ar" => esc_html("Arabic"),
            "as" => esc_html("Assamese"),
            "av" => esc_html("Avaric"),
            "ay" => esc_html("Aymara"),
            "az" => esc_html("Azerbaijani"),
            "ba" => esc_html("Bashkir"),
            "be" => esc_html("Belarusian"),
            "bg" => esc_html("Bulgarian"),
            "bh" => esc_html("Bihari languages"),
            "bi" => esc_html("Bislama"),
            "bm" => esc_html("Bambara"),
            "bn" => esc_html("Bengali"),
            "bo" => esc_html("Tibetan"),
            "br" => esc_html("Breton"),
            "bs" => esc_html("Bosnian"),
            "ca" => esc_html("Catalan; Valencian"),
            "ce" => esc_html("Chechen"),
            "ch" => esc_html("Chamorro"),
            "co" => esc_html("Corsican"),
            "cr" => esc_html("Cree"),
            "cs" => esc_html("Czech"),
            "cu" => esc_html("Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic"),
            "cv" => esc_html("Chuvash"),
            "cy" => esc_html("Welsh"),
            "da" => esc_html("Danish"),
            "de" => esc_html("German"),
            "dv" => esc_html("Divehi; Dhivehi; Maldivian"),
            "dz" => esc_html("Dzongkha"),
            "ee" => esc_html("Ewe"),
            "el" => esc_html("Greek, Modern"),
            "en" => esc_html("English"),
            "eo" => esc_html("Esperanto"),
            "es" => esc_html("Spanish; Castilian"),
            "et" => esc_html("Estonian"),
            "eu" => esc_html("Basque"),
            "fa" => esc_html("Persian"),
            "ff" => esc_html("Fulah"),
            "fi" => esc_html("Finnish"),
            "fj" => esc_html("Fijian"),
            "fo" => esc_html("Faroese"),
            "fr" => esc_html("French"),
            "fy" => esc_html("Western Frisian"),
            "ga" => esc_html("Irish"),
            "gd" => esc_html("Gaelic; Scottish Gaelic"),
            "gl" => esc_html("Galician"),
            "gn" => esc_html("Guarani"),
            "gu" => esc_html("Gujarati"),
            "gv" => esc_html("Manx"),
            "ha" => esc_html("Hausa"),
            "he" => esc_html("Hebrew"),
            "hi" => esc_html("Hindi"),
            "ho" => esc_html("Hiri Motu"),
            "hr" => esc_html("Croatian"),
            "ht" => esc_html("Haitian; Haitian Creole"),
            "hu" => esc_html("Hungarian"),
            "hy" => esc_html("Armenian"),
            "hz" => esc_html("Herero"),
            "ia" => esc_html("Interlingua (International Auxiliary Language Association)"),
            "id" => esc_html("Indonesian"),
            "ie" => esc_html("Interlingue; Occidental"),
            "ig" => esc_html("Igbo"),
            "ii" => esc_html("Sichuan Yi; Nuosu"),
            "ik" => esc_html("Inupiaq"),
            "io" => esc_html("Ido"),
            "is" => esc_html("Icelandic"),
            "it" => esc_html("Italian"),
            "iu" => esc_html("Inuktitut"),
            "ja" => esc_html("Japanese"),
            "jv" => esc_html("Javanese"),
            "ka" => esc_html("Georgian"),
            "kg" => esc_html("Kongo"),
            "ki" => esc_html("Kikuyu; Gikuyu"),
            "kj" => esc_html("Kuanyama; Kwanyama"),
            "kk" => esc_html("Kazakh"),
            "kl" => esc_html("Kalaallisut; Greenlandic"),
            "km" => esc_html("Central Khmer"),
            "kn" => esc_html("Kannada"),
            "ko" => esc_html("Korean"),
            "kr" => esc_html("Kanuri"),
            "ks" => esc_html("Kashmiri"),
            "ku" => esc_html("Kurdish"),
            "kv" => esc_html("Komi"),
            "kw" => esc_html("Cornish"),
            "ky" => esc_html("Kirghiz; Kyrgyz"),
            "la" => esc_html("Latin"),
            "lb" => esc_html("Luxembourgish; Letzeburgesch"),
            "lg" => esc_html("Ganda"),
            "li" => esc_html("Limburgan; Limburger; Limburgish"),
            "ln" => esc_html("Lingala"),
            "lo" => esc_html("Lao"),
            "lt" => esc_html("Lithuanian"),
            "lu" => esc_html("Luba-Katanga"),
            "lv" => esc_html("Latvian"),
            "mg" => esc_html("Malagasy"),
            "mh" => esc_html("Marshallese"),
            "mi" => esc_html("Maori"),
            "mk" => esc_html("Macedonian"),
            "ml" => esc_html("Malayalam"),
            "mn" => esc_html("Mongolian"),
            "mr" => esc_html("Marathi"),
            "ms" => esc_html("Malay"),
            "mt" => esc_html("Maltese"),
            "my" => esc_html("Burmese"),
            "na" => esc_html("Nauru"),
            "nb" => esc_html("Bokmål, Norwegian; Norwegian Bokmål"),
            "nd" => esc_html("Ndebele, North; North Ndebele"),
            "ne" => esc_html("Nepali"),
            "ng" => esc_html("Ndonga"),
            "nl" => esc_html("Dutch; Flemish"),
            "nn" => esc_html("Norwegian Nynorsk; Nynorsk, Norwegian"),
            "no" => esc_html("Norwegian"),
            "nr" => esc_html("Ndebele, South; South Ndebele"),
            "nv" => esc_html("Navajo; Navaho"),
            "ny" => esc_html("Chichewa; Chewa; Nyanja"),
            "oc" => esc_html("Occitan (post 1500); Provençal"),
            "oj" => esc_html("Ojibwa"),
            "om" => esc_html("Oromo"),
            "or" => esc_html("Oriya"),
            "os" => esc_html("Ossetian; Ossetic"),
            "pa" => esc_html("Panjabi; Punjabi"),
            "pi" => esc_html("Pali"),
            "pl" => esc_html("Polish"),
            "ps" => esc_html("Pushto; Pashto"),
            "pt" => esc_html("Portuguese"),
            "qu" => esc_html("Quechua"),
            "rm" => esc_html("Romansh"),
            "rn" => esc_html("Rundi"),
            "ro" => esc_html("Romanian; Moldavian; Moldovan"),
            "ru" => esc_html("Russian"),
            "rw" => esc_html("Kinyarwanda"),
            "sa" => esc_html("Sanskrit"),
            "sc" => esc_html("Sardinian"),
            "sd" => esc_html("Sindhi"),
            "se" => esc_html("Northern Sami"),
            "sg" => esc_html("Sango"),
            "si" => esc_html("Sinhala; Sinhalese"),
            "sk" => esc_html("Slovak"),
            "sl" => esc_html("Slovenian"),
            "sm" => esc_html("Samoan"),
            "sn" => esc_html("Shona"),
            "so" => esc_html("Somali"),
            "sq" => esc_html("Albanian"),
            "sr" => esc_html("Serbian"),
            "ss" => esc_html("Swati"),
            "st" => esc_html("Sotho, Southern"),
            "su" => esc_html("Sundanese"),
            "sv" => esc_html("Swedish"),
            "sw" => esc_html("Swahili"),
            "ta" => esc_html("Tamil"),
            "te" => esc_html("Telugu"),
            "tg" => esc_html("Tajik"),
            "th" => esc_html("Thai"),
            "ti" => esc_html("Tigrinya"),
            "tk" => esc_html("Turkmen"),
            "tl" => esc_html("Tagalog"),
            "tn" => esc_html("Tswana"),
            "to" => esc_html("Tonga (Tonga Islands)"),
            "tr" => esc_html("Turkish"),
            "ts" => esc_html("Tsonga"),
            "tt" => esc_html("Tatar"),
            "tw" => esc_html("Twi"),
            "ty" => esc_html("Tahitian"),
            "ug" => esc_html("Uighur; Uyghur"),
            "uk" => esc_html("Ukrainian"),
            "ur" => esc_html("Urdu"),
            "uz" => esc_html("Uzbek"),
            "ve" => esc_html("Venda"),
            "vi" => esc_html("Vietnamese"),
            "vo" => esc_html("Volapük"),
            "wa" => esc_html("Walloon"),
            "wo" => esc_html("Wolof"),
            "xh" => esc_html("Xhosa"),
            "yi" => esc_html("Yiddish"),
            "yo" => esc_html("Yoruba"),
            "za" => esc_html("Zhuang; Chuang"),
            "zh" => esc_html("Chinese"),
            "zu" => esc_html("Zulu"),
        );

        $this->attributes = array(
            "offer_id" => array(
                "support" => "6324405",
                "title" => esc_html__("ID", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product’s unique identifier.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 50,
                "placeholder" => "A2B4",
                "level" => 0,
            ),
            "title" => array(
                "support" => "6324415",
                "title" => esc_html__("Title", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product’s name.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 150,
                "placeholder" => "Mens Pique Polo Shirt",
                "level" => 0,
            ),
            "description" => array(
                "support" => "6324468",
                "title" => esc_html__("Description", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product’s description.", 'gshopping-wc-google-shopping'),
                "type" => "textarea",
                "max" => 5000,
                "placeholder" => "Made from 100% organic cotton, this classic red men’s polo has a slim fit and signature logo embroidered on the left chest. Machine wash cold; imported.",
                "level" => 0,
            ),
            "link" => array(
                "support" => "6324416",
                "title" => esc_html__("Link", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product’s landing page.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "placeholder" => "http://www.example.com/asp/sp.asp?cat=12&id=1030",
                "level" => 0,
            ),
            "image_link" => array(
                "support" => "6324350",
                "title" => esc_html__("Image link", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("The URL of your product’s main image.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "placeholder" => "http://www.example.com/image1.jpg",
                "level" => 0,
            ),
            "additional_image_links" => array(
                "support" => "6324370",
                "title" => esc_html__("Additional image link", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The URL of an additional image for your product.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 2000,
                "placeholder" => "http://www.example.com/image1.jpg",
                "level" => 0,
                "repeated" => true,
                "max_repeated" => 10
            ),
            "mobile_link" => array(
                "support" => "6324459",
                "title" => esc_html__("Mobile link", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product’s mobile-optimized landing page when you have a different URL for mobile and desktop traffic.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 2000,
                "placeholder" => "http://www.m.example.com/asp/sp.asp?cat=12id=1030",
                "level" => 0,
            ),
            "availability" => array(
                "support" => "6324448",
                "title" => esc_html__("Availability", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product's availability.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "in_stock",
                "option" => array(
                    "in_stock" => esc_html__("In stock", 'gshopping-wc-google-shopping'),
                    "out_of_stock" => esc_html__("Out of stock", 'gshopping-wc-google-shopping'),
                    "preorder" => esc_html__("Preorder", 'gshopping-wc-google-shopping'),
                    "backorder" => esc_html__("Backorder", 'gshopping-wc-google-shopping'),
                ),
                "level" => 0,
            ),
            "availability_date" => array(
                "support" => "6324470",
                "title" => esc_html__("Availability date", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("The date a preordered or backordered product becomes available for delivery.", 'gshopping-wc-google-shopping'),
                "type" => "datetime",
                "max" => 25,
                "placeholder" => "2016-02-24T11:07+0100",
                "level" => 0,
            ),
            "cost_of_goods_sold" => array(
                "support" => "9017895",
                "title" => esc_html__("Cost of goods sold", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The costs associated with the sale of a particular product as defined by the accounting convention you set up. These costs may include material, labor, freight, or other overhead expenses. By submitting the COGS for your products, you gain insights about other metrics, such as your gross margin and the amount of revenue generated by your ads and free listings.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "23.00",
                "level" => 0,
                "unit_fix" => "currency",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("10.01", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                    ),
                    "currency" => array(
                        "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("USD", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => true,
                    ),
                ),
            ),
            "expiration_date" => array(
                "support" => "6324499",
                "title" => esc_html__("Expiration date", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The date that your product should stop showing.", 'gshopping-wc-google-shopping'),
                "type" => "datetime",
                "max" => 25,
                "placeholder" => "2016-07-11T11:07+0100",
                "level" => 0,
            ),
            "price" => array(
                "support" => "6324371",
                "title" => esc_html__("Price", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("Your product’s price.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "15.00 USD",
                "level" => 0,
                "unit_fix" => "currency",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                    ),
                    "currency" => array(
                        "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => true,
                    ),
                )
            ),
            "sale_price" => array(
                "support" => "6324471",
                "title" => esc_html__("Sale price", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product's sale price.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "15.00 USD",
                "level" => 0,
                "unit_fix" => "currency",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                    ),
                    "currency" => array(
                        "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => true,
                    ),
                )
            ),
            "sale_price_effective_date" => array(
                "support" => "6324460",
                "title" => esc_html__("Sale price effective date", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The date range during which the sale price applies.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 51,
                "placeholder" => "2016-02-24T11:07+0100 / 2016-02-29T23:07+0100",
                "level" => 0,
            ),
            "unit_pricing_measure" => array(
                "support" => "6324455",
                "title" => esc_html__("Unit pricing measure", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The date range during which the sale price applies.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "1.5kg",
                "level" => 0,
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("750", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("ml", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "oz" => esc_html("oz"),
                            "lb" => esc_html("lb"),
                            "mg" => esc_html("mg"),
                            "g" => esc_html("g"),
                            "kg" => esc_html("kg"),
                            "floz" => esc_html("floz"),
                            "pt" => esc_html("pt"),
                            "qt" => esc_html("qt"),
                            "gal" => esc_html("gal"),
                            "ml" => esc_html("ml"),
                            "cl" => esc_html("cl"),
                            "l" => esc_html("l"),
                            "cbm" => esc_html("cbm"),
                            "in" => esc_html("in"),
                            "ft" => esc_html("ft"),
                            "yd" => esc_html("yd"),
                            "cm" => esc_html("cm"),
                            "m" => esc_html("m"),
                            "sqft" => esc_html("sqft"),
                            "sqm" => esc_html("sqm"),
                            "ct" => esc_html("ct"),
                        ),
                    ),
                )
            ),
            "unit_pricing_base_measure" => array(
                "support" => "6324490",
                "title" => esc_html__("Unit pricing base measure", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The product’s base measure for pricing (for example, 100ml means the price is calculated based on a 100ml units).", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "100g",
                "level" => 0,
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("100", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "1" => esc_html("1"),
                            "10" => esc_html("10"),
                            "100" => esc_html("100"),
                            "2" => esc_html("2"),
                            "4" => esc_html("4"),
                            "8" => esc_html("8"),
                            "75" => esc_html("75"),
                            "750" => esc_html("750"),
                            "50" => esc_html("50"),
                            "1000" => esc_html("1000"),
                        ),
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("g", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "oz" => esc_html("oz"),
                            "lb" => esc_html("lb"),
                            "mg" => esc_html("mg"),
                            "g" => esc_html("g"),
                            "kg" => esc_html("kg"),
                            "floz" => esc_html("floz"),
                            "pt" => esc_html("pt"),
                            "qt" => esc_html("qt"),
                            "gal" => esc_html("gal"),
                            "ml" => esc_html("ml"),
                            "cl" => esc_html("cl"),
                            "l" => esc_html("l"),
                            "cbm" => esc_html("cbm"),
                            "in" => esc_html("in"),
                            "ft" => esc_html("ft"),
                            "yd" => esc_html("yd"),
                            "cm" => esc_html("cm"),
                            "m" => esc_html("m"),
                            "sqft" => esc_html("sqft"),
                            "sqm" => esc_html("sqm"),
                            "ct" => esc_html("ct"),
                        ),
                    ),
                )
            ),
            "installment" => array(
                "support" => "6324474",
                "title" => esc_html__("Installment", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Details of an installment payment plan.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "placeholder" => "6,50 BRL",
                "level" => 0,
                "element" => array(
                    "months" => array(
                        "title" => esc_html__("Months", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("6", 'gshopping-wc-google-shopping'),
                        "type" => "number",
                        "level" => 1,
                        "required" => true,
                    ),
                    "amount" => array(
                        "title" => esc_html__("Amount", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("35.00", 'gshopping-wc-google-shopping'),
                        "type" => "element",
                        "level" => 1,
                        "required" => true,
                        "unit_fix" => "currency",
                        "separate" => " ",
                        "element" => array(
                            "value" => array(
                                "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("50", 'gshopping-wc-google-shopping'),
                                "type" => "float",
                                "level" => 2,
                                "required" => true,
                            ),
                            "currency" => array(
                                "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("USD", 'gshopping-wc-google-shopping'),
                                "type" => "text",
                                "level" => 2,
                                "required" => true,
                            ),
                        )
                    ),
                ),
            ),
            "subscription_cost" => array(
                "support" => "7437904",
                "title" => esc_html__("Subscription cost", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Details a monthly or annual payment plan that bundles a communications service contract with a wireless product.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "placeholder" => "month:12:35.00USD",
                "level" => 0,
                "element" => array(
                    "period" => array(
                        "title" => esc_html__("Period", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("month", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "month" => esc_html__("Month", 'gshopping-wc-google-shopping'),
                            "year" => esc_html__("Year", 'gshopping-wc-google-shopping'),
                        ),
                    ),
                    "period_length" => array(
                        "title" => esc_html__("Period length", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("12", 'gshopping-wc-google-shopping'),
                        "type" => "number",
                        "level" => 1,
                        "required" => true,
                    ),
                    "amount" => array(
                        "title" => esc_html__("Amount", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("35.00", 'gshopping-wc-google-shopping'),
                        "type" => "element",
                        "level" => 1,
                        "required" => true,
                        "unit_fix" => "currency",
                        "separate" => " ",
                        "element" => array(
                            "value" => array(
                                "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("35", 'gshopping-wc-google-shopping'),
                                "type" => "float",
                                "level" => 2,
                                "required" => true,
                            ),
                            "currency" => array(
                                "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("USD", 'gshopping-wc-google-shopping'),
                                "type" => "text",
                                "level" => 2,
                                "required" => true,
                            ),
                        ),
                    ),
                ),
            ),
            "loyalty_points" => array(
                "support" => "6324456",
                "title" => esc_html__("Loyalty points", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The number and type of loyalty points a customer receives when buying a product.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "placeholder" => "Program A:100:1.5",
                "level" => 0,
                "element" => array(
                    "name" => array(
                        "title" => esc_html__("Name", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Program A", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => false,
                    ),
                    "points_value" => array(
                        "title" => esc_html__("Points value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("100", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                    ),
                    "ratio" => array(
                        "title" => esc_html__("Ratio", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("0.5", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => false,
                    ),
                ),
            ),
            "google_product_category" => array(
                "support" => "6324436",
                "title" => esc_html__("Google product category", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Google-defined product category for your product.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "Apparel & Accessories > Clothing > Outerwear > Coats & Jackets",
                "option" => $this->get_google_categories(),
                "level" => 0,
            ),
            "product_type" => array(
                "support" => "6324406",
                "title" => esc_html__("Product type", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Product category that you define for your product.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 750,
                "placeholder" => "Home > Women > Dresses > Maxi Dresses",
                "level" => 0,
                "repeated" => true,
                "max_repeated" => 5
            ),
            "brand" => array(
                "support" => "6324351",
                "title" => esc_html__("Brand", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s brand name.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 70,
                "placeholder" => "Google",
                "level" => 0,
            ),
            "gtin" => array(
                "support" => "6324461",
                "title" => esc_html__("GTIN", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s Global Trade Item Number (GTIN).", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 50,
                "level" => 0,
                "placeholder" => "3234567890126",
            ),
            "mpn" => array(
                "support" => "6324482",
                "title" => esc_html__("MPN", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product’s Manufacturer Part Number (mpn).", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 70,
                "level" => 0,
                "placeholder" => "GO12345OOGLE",
            ),
            "identifier_exists" => array(
                "support" => "6324478",
                "title" => esc_html__("Identifier exists", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Use to indicate whether or not the unique product identifiers (UPIs) GTIN, MPN, and brand are available for your product.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "no",
                "option" => array(
                    "yes" => esc_html__("Yes", 'gshopping-wc-google-shopping'),
                    "no" => esc_html__("No", 'gshopping-wc-google-shopping'),
                )
            ),
            "condition" => array(
                "support" => "6324469",
                "title" => esc_html__("Condition", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("The condition of your product at time of sale.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "new",
                "default" => "new",
                "option" => array(
                    "new" => esc_html__("New", 'gshopping-wc-google-shopping'),
                    "refurbished" => esc_html__("Refurbished", 'gshopping-wc-google-shopping'),
                    "used" => esc_html__("Used", 'gshopping-wc-google-shopping'),
                ),
            ),
            "adult" => array(
                "support" => "6324508",
                "title" => esc_html__("Adult", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "yes",
                "option" => array(
                    "yes" => esc_html__("Yes", 'gshopping-wc-google-shopping'),
                    "no" => esc_html__("No", 'gshopping-wc-google-shopping'),
                ),
            ),
            "multipack" => array(
                "support" => "6324488",
                "title" => esc_html__("Multipack", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("The number of identical products sold within a merchant-defined multipack.", 'gshopping-wc-google-shopping'),
                "type" => "number",
                "placeholder" => "6",
                "level" => 0,
            ),
            "is_bundle" => array(
                "support" => "6324449",
                "title" => esc_html__("Bundle", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Indicates a product is a merchant-defined custom group of different products featuring one main product.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "yes",
                "option" => array(
                    "yes" => esc_html__("Yes", 'gshopping-wc-google-shopping'),
                    "no" => esc_html__("No", 'gshopping-wc-google-shopping'),
                ),
            ),
            "energy_efficiency_class" => array(
                "support" => "7562785",
                "title" => esc_html__("Energy efficiency class", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product’s energy label.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "A+",
                "option" => array(
                    "A+++" => esc_html__("A+++", 'gshopping-wc-google-shopping'),
                    "A++" => esc_html__("A++", 'gshopping-wc-google-shopping'),
                    "A+" => esc_html__("A+", 'gshopping-wc-google-shopping'),
                    "A" => esc_html__("A", 'gshopping-wc-google-shopping'),
                    "B" => esc_html__("B", 'gshopping-wc-google-shopping'),
                    "C" => esc_html__("C", 'gshopping-wc-google-shopping'),
                    "D" => esc_html__("D", 'gshopping-wc-google-shopping'),
                    "E" => esc_html__("E", 'gshopping-wc-google-shopping'),
                    "F" => esc_html__("F", 'gshopping-wc-google-shopping'),
                    "G" => esc_html__("G", 'gshopping-wc-google-shopping'),
                ),
            ),
            "min_energy_efficiency_class" => array(
                "support" => "7562785",
                "title" => esc_html__("Minimum energy efficiency class", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product’s energy label.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "A+++",
                "option" => array(
                    "A+++" => esc_html__("A+++", 'gshopping-wc-google-shopping'),
                    "A++" => esc_html__("A++", 'gshopping-wc-google-shopping'),
                    "A+" => esc_html__("A+", 'gshopping-wc-google-shopping'),
                    "A" => esc_html__("A", 'gshopping-wc-google-shopping'),
                    "B" => esc_html__("B", 'gshopping-wc-google-shopping'),
                    "C" => esc_html__("C", 'gshopping-wc-google-shopping'),
                    "D" => esc_html__("D", 'gshopping-wc-google-shopping'),
                    "E" => esc_html__("E", 'gshopping-wc-google-shopping'),
                    "F" => esc_html__("F", 'gshopping-wc-google-shopping'),
                    "G" => esc_html__("G", 'gshopping-wc-google-shopping'),
                ),
            ),
            "max_energy_efficiency_class" => array(
                "support" => "7562785",
                "title" => esc_html__("Maximum energy efficiency class", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product’s energy label.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "D",
                "option" => array(
                    "A+++" => esc_html__("A+++", 'gshopping-wc-google-shopping'),
                    "A++" => esc_html__("A++", 'gshopping-wc-google-shopping'),
                    "A+" => esc_html__("A+", 'gshopping-wc-google-shopping'),
                    "A" => esc_html__("A", 'gshopping-wc-google-shopping'),
                    "B" => esc_html__("B", 'gshopping-wc-google-shopping'),
                    "C" => esc_html__("C", 'gshopping-wc-google-shopping'),
                    "D" => esc_html__("D", 'gshopping-wc-google-shopping'),
                    "E" => esc_html__("E", 'gshopping-wc-google-shopping'),
                    "F" => esc_html__("F", 'gshopping-wc-google-shopping'),
                    "G" => esc_html__("G", 'gshopping-wc-google-shopping'),
                ),
            ),
            "age_group" => array(
                "support" => "6324463",
                "title" => esc_html__("Age group", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("The demographic for which your product is intended.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "infant",
                "level" => 0,
                "option" => array(
                    "newborn" => esc_html__("Newborn", 'gshopping-wc-google-shopping'),
                    "infant" => esc_html__("infant", 'gshopping-wc-google-shopping'),
                    "toddler" => esc_html__("Toddler", 'gshopping-wc-google-shopping'),
                    "kids" => esc_html__("Kids", 'gshopping-wc-google-shopping'),
                    "adult" => esc_html__("Adult", 'gshopping-wc-google-shopping'),
                ),
            ),
            "color" => array(
                "support" => "6324487",
                "title" => esc_html__("Color", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s color(s).", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "placeholder" => "Black",
                "max" => 100,
                "level" => 0,
            ),
            "gender" => array(
                "support" => "6324479",
                "title" => esc_html__("Gender", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("The gender for which your product is intended.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "Unisex",
                "option" => array(
                    "male" => esc_html__("Male", 'gshopping-wc-google-shopping'),
                    "female" => esc_html__("Female", 'gshopping-wc-google-shopping'),
                    "unisex" => esc_html__("Unisex", 'gshopping-wc-google-shopping'),
                ),
                "level" => 0,
            ),
            "material" => array(
                "support" => "6324410",
                "title" => esc_html__("Material", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s fabric or material.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 200,
                "placeholder" => "leather",
                "level" => 0,
            ),
            "pattern" => array(
                "support" => "6324483",
                "title" => esc_html__("Pattern", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s pattern or graphic print.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "placeholder" => "striped polka dot paisley",
                "level" => 0,
            ),
            "size" => array(
                "support" => "6324492",
                "title" => esc_html__("Size", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s size.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "placeholder" => "XL",
                "level" => 0,
            ),
            "size_type" => array(
                "support" => "6324497",
                "title" => esc_html__("Size type", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your apparel product’s cut.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "maternity",
                "option" => array(
                    "regular" => esc_html__("Regular", 'gshopping-wc-google-shopping'),
                    "petite" => esc_html__("Petite", 'gshopping-wc-google-shopping'),
                    "maternity" => esc_html__("Maternity", 'gshopping-wc-google-shopping'),
                    "big" => esc_html__("Big", 'gshopping-wc-google-shopping'),
                    "tall" => esc_html__("Tall", 'gshopping-wc-google-shopping'),
                    "plus" => esc_html__("Plus", 'gshopping-wc-google-shopping'),
                ),
                "level" => 0,
            ),
            "size_system" => array(
                "support" => "6324502",
                "title" => esc_html__("Size system", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The country of the size system used by your product.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "US",
                "option" => array(
                    "US" => esc_html__("US", 'gshopping-wc-google-shopping'),
                    "UK" => esc_html__("UK", 'gshopping-wc-google-shopping'),
                    "EU" => esc_html__("EU", 'gshopping-wc-google-shopping'),
                    "DE" => esc_html__("DE", 'gshopping-wc-google-shopping'),
                    "FR" => esc_html__("FR", 'gshopping-wc-google-shopping'),
                    "JP" => esc_html__("JP", 'gshopping-wc-google-shopping'),
                    "CN" => esc_html__("CN", 'gshopping-wc-google-shopping'),
                    "IT" => esc_html__("IT", 'gshopping-wc-google-shopping'),
                    "BR" => esc_html__("BR", 'gshopping-wc-google-shopping'),
                    "MEX" => esc_html__("MEX", 'gshopping-wc-google-shopping'),
                    "AU" => esc_html__("AU", 'gshopping-wc-google-shopping'),
                ),
            ),
            "item_group_id" => array(
                "support" => "6324507",
                "title" => esc_html__("Item group ID", 'gshopping-wc-google-shopping'),
                "required" => true,
                "description" => esc_html__("ID for a group of products that come in different versions (variants).", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 50,
                "placeholder" => "AB12345",
                "level" => 0,
            ),
            "product_length" => array(
                "support" => "11018531",
                "title" => esc_html__("Product length", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product's length.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "max" => 3000
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "cm" => esc_html("cm"),
                            "in" => esc_html("in"),
                        ),
                    ),
                ),
            ),
            "product_width" => array(
                "support" => "11018531",
                "title" => esc_html__("Product width", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product's width.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "max" => 3000
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "cm" => esc_html("cm"),
                            "in" => esc_html("in"),
                        ),
                    ),
                ),
            ),
            "product_height" => array(
                "support" => "11018531",
                "title" => esc_html__("Product height", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product's height.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "max" => 3000
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "cm" => esc_html("cm"),
                            "in" => esc_html("in"),
                        ),
                    ),
                ),
            ),
            "product_weight" => array(
                "support" => "11018531",
                "title" => esc_html__("Product weight", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Your product's weight.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "3.5",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "weight",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("3.5", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "max" => 2000
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("lb", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "option" => array(
                            "lb" => esc_html("lb"),
                            "oz" => esc_html("oz"),
                            "g" => esc_html("g"),
                            "kg" => esc_html("kg"),
                        ),
                    ),
                ),
            ),
            "product_detail" => array(
                "support" => "9218260",
                "title" => esc_html__("Product detail", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Technical specifications or additional details of your product.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "placeholder" => "General:Product Type:Digital player",
                "level" => 0,
                "repeated" => true,
                "max_repeated" => 1000,
                "element" => array(
                    "section_name" => array(
                        "title" => esc_html__("Section name", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("General, Display", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "max" => 140,
                        "level" => 1,
                        "required" => false,
                    ),
                    "attribute_name" => array(
                        "title" => esc_html__("Attribute name", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Product Type, Digital Player Type", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "max" => 140,
                        "level" => 1,
                        "required" => true,
                    ),
                    "attribute_value" => array(
                        "title" => esc_html__("Attribute value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Digital player, Flash based", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "max" => 1000,
                        "level" => 1,
                        "required" => true,
                    ),
                ),
            ),
            "product_highlight" => array(
                "support" => "9216100",
                "title" => esc_html__("Product highlight", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The most relevant highlights of your products.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 150,
                "level" => 0,
                "placeholder" => "Supports thousands of apps, including Netflix, YouTube, and HBO Max",
                "repeated" => true,
                "max_repeated" => 150,
            ),
            "ads_redirect" => array(
                "support" => "6324450",
                "title" => esc_html__("Ads redirect", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A URL used to specify additional parameters for your product page. Customers will be sent to this URL rather than the value that you submit for the link [link] or mobile link [mobile_link] attributes.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 2000,
                "level" => 0,
                "placeholder" => "http://www.example.com/product.html",
            ),
            "custom_label_0" => array(
                "support" => "6324473",
                "title" => esc_html__("Custom label 1", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help organize bidding and reporting in Shopping campaigns.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "summer",
            ),
            "custom_label_1" => array(
                "support" => "6324473",
                "title" => esc_html__("Custom label 2", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help organize bidding and reporting in Shopping campaigns.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "summer",
            ),
            "custom_label_2" => array(
                "support" => "6324473",
                "title" => esc_html__("Custom label 3", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help organize bidding and reporting in Shopping campaigns.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "summer",
            ),
            "custom_label_3" => array(
                "support" => "6324473",
                "title" => esc_html__("Custom label 4", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help organize bidding and reporting in Shopping campaigns.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "summer",
            ),
            "custom_label_4" => array(
                "support" => "6324473",
                "title" => esc_html__("Custom label 5", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help organize bidding and reporting in Shopping campaigns.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "summer",
            ),
            "promotion_id" => array(
                "support" => "7050148",
                "title" => esc_html__("Promotion ID", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("An identifier that allows you to match products to promotions.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 50,
                "level" => 0,
                "placeholder" => "ABC123, ABC124",
                "repeated" => true,
                "max_repeated" => 10
            ),
            "excluded_destination" => array(
                "support" => "6324486",
                "title" => esc_html__("Excluded destination", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A setting that you can use to exclude a product from participating in a specific type of advertising campaign.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "Shopping_ads",
                "repeated" => true,
                "option" => array(
                    "Shopping_ads" => esc_html__("Shopping_ads", 'gshopping-wc-google-shopping'),
                    "Buy_on_Google_listings" => esc_html__("Buy_on_Google_listings", 'gshopping-wc-google-shopping'),
                    "Display_ads" => esc_html__("Display_ads", 'gshopping-wc-google-shopping'),
                    "Local_inventory_ads" => esc_html__("Local_inventory_ads", 'gshopping-wc-google-shopping'),
                    "Free_listings" => esc_html__("Free_listings", 'gshopping-wc-google-shopping'),
                    "Free_local_listings" => esc_html__("Free_local_listings", 'gshopping-wc-google-shopping'),
                ),
            ),
            "included_destination" => array(
                "support" => "7501026",
                "title" => esc_html__("Included destination", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A setting that you can use to include a product in a specific type of advertising campaign.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "level" => 0,
                "placeholder" => "Shopping_ads",
                "repeated" => true,
                "option" => array(
                    "Shopping_ads" => esc_html__("Shopping_ads", 'gshopping-wc-google-shopping'),
                    "Buy_on_Google_listings" => esc_html__("Buy_on_Google_listings", 'gshopping-wc-google-shopping'),
                    "Display_ads" => esc_html__("Display_ads", 'gshopping-wc-google-shopping'),
                    "Local_inventory_ads" => esc_html__("Local_inventory_ads", 'gshopping-wc-google-shopping'),
                    "Free_listings" => esc_html__("Free_listings", 'gshopping-wc-google-shopping'),
                    "Free_local_listings" => esc_html__("Free_local_listings", 'gshopping-wc-google-shopping'),
                ),
            ),
            "shopping_ads_excluded_country" => array(
                "support" => "9837523",
                "title" => esc_html__("Excluded countries for Shopping ads", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A setting that allows you to exclude countries where your products are advertised on Shopping ads.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "DE",
                "level" => 0,
                "option" => $this->country,
                "repeated" => true,
                "max_repeated" => 100,
            ),
            "shipping" => array(
                "support" => "6324484",
                "title" => esc_html__("Shipping", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product's shipping cost, shipping speeds, and the locations your product ships to.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "level" => 0,
                "placeholder" => "US:CA:Overnight:16.00 USD:1:1:2:3",
                "element" => array(
                    "country" => array(
                        "title" => esc_html__("Country", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Country", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "US",
                    ),
                    "region" => array(
                        "title" => esc_html__("Region / Postal code / Location ID", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Region / Postal code / Location ID", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => false,
                        "placeholder" => "MA",
                    ),
//					"service"           => array(
//						"title"       => esc_html__( "Service", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Service", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "Express",
//					),
                    "price" => array(
                        "title" => esc_html__("Price", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Price", 'gshopping-wc-google-shopping'),
                        "type" => "element",
                        "level" => 1,
                        "required" => true,
                        "separate" => " ",
                        "unit_fix" => "currency",
                        "element" => array(
                            "value" => array(
                                "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                                "type" => "float",
                                "level" => 2,
                                "required" => true,
                                "placeholder" => "15.99",
                            ),
                            "currency" => array(
                                "title" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                                "placeholder" => esc_html__("Currency", 'gshopping-wc-google-shopping'),
                                "type" => "text",
                                "level" => 2,
                                "required" => true,
                                "placeholder" => "USD",
                            ),
                        ),
                    ),
//					"min_handling_time" => array(
//						"title"       => esc_html__( "Min handling time", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Min handling time", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "1",
//					),
//					"max_handling_time" => array(
//						"title"       => esc_html__( "Max handling time", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Max handling time", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "3",
//					),
//					"min_transit_time"  => array(
//						"title"       => esc_html__( "Min transit time", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Min transit time", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "2",
//					),
//					"max_transit_time"  => array(
//						"title"       => esc_html__( "Max transit time", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Max transit time", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "5",
//					),
                )
            ),
            "shipping_label" => array(
                "support" => "6324504",
                "title" => esc_html__("Shipping label", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help assign correct shipping costs in Merchant Center account settings.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "level" => 0,
                "placeholder" => "perishable",
            ),
            "shipping_weight" => array(
                "support" => "6324503",
                "title" => esc_html__("Shipping weight", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The weight of the product used to calculate the shipping cost.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "3 kg",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "weight",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "3",
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "kg",
                        "option" => array(
                            "lb" => esc_html("lb"),
                            "oz" => esc_html("oz"),
                            "g" => esc_html("g"),
                            "kg" => esc_html("kg"),
                        ),
                    ),
                ),
            ),
            "shipping_length" => array(
                "support" => "6324498",
                "title" => esc_html__("Shipping length", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The length of the product used to calculate the shipping cost by dimensional weight.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20 in",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                        "option" => array(
                            "in" => esc_html("in"),
                            "cm" => esc_html("cm"),
                        ),
                    ),
                ),
            ),
            "shipping_width" => array(
                "support" => "6324498",
                "title" => esc_html__("Shipping width", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The width of the product used to calculate the shipping cost by dimensional weight.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20 in",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                        "option" => array(
                            "in" => esc_html("in"),
                            "cm" => esc_html("cm"),
                        ),
                    ),
                ),
            ),
            "shipping_height" => array(
                "support" => "6324498",
                "title" => esc_html__("Shipping height", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The height of the product used to calculate the shipping cost by dimensional weight.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => " ",
                "placeholder" => "20 in",
                "level" => 0,
                "unit_fix" => "unit",
                "unit" => "dimension",
                "element" => array(
                    "value" => array(
                        "title" => esc_html__("Value", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("20", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                    ),
                    "unit" => array(
                        "title" => esc_html__("Unit", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("in", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "",
                        "option" => array(
                            "in" => esc_html("in"),
                            "cm" => esc_html("cm"),
                        ),
                    ),
                ),
            ),
            "ships_from_country" => array(
                "support" => "9837936",
                "title" => esc_html__("Ships from country", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A setting that allows you to provide the country from which your product will typically ship.", 'gshopping-wc-google-shopping'),
                "type" => "select",
                "placeholder" => "DE",
                "level" => 0,
                "option" => $this->country,
            ),
            "transit_time_label" => array(
                "support" => "9298965",
                "title" => esc_html__("Transit time label", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("Label that you assign to a product to help assign different transit times in Merchant Center account settings.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "placeholder" => "From Seattle",
                "level" => 0,
            ),
            "max_handling_time" => array(
                "support" => "7388496",
                "title" => esc_html__("Maximum handling time", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The longest amount of time between when an order is placed for a product and when the product ships.", 'gshopping-wc-google-shopping'),
                "type" => "number",
                "min" => 0,
                "placeholder" => "3",
                "level" => 0,
            ),
            "min_handling_time" => array(
                "support" => "7388496",
                "title" => esc_html__("Minimum handling time", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("The shortest amount of time between when an order is placed for a product and when the product ships.", 'gshopping-wc-google-shopping'),
                "type" => "number",
                "min" => 0,
                "placeholder" => "1",
                "level" => 0,
            ),
            "tax" => array(
                "support" => "6324454",
                "title" => esc_html__("Tax", 'gshopping-wc-google-shopping'),
                "required" => "condition",
                "description" => esc_html__("Your product’s sales tax rate in percent.", 'gshopping-wc-google-shopping'),
                "type" => "element",
                "separate" => ":",
                "level" => 0,
                "placeholder" => "US:CA:5.00:yes",
                "element" => array(
                    "country" => array(
                        "title" => esc_html__("Country", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("US", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => false,
                        "placeholder" => "US",
                    ),
                    "region" => array(
                        "title" => esc_html__("Region", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("CA", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => false,
                        "placeholder" => "MA",
                    ),
                    "postal_code" => array(
                        "title" => esc_html__("Postal code", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("5", 'gshopping-wc-google-shopping'),
                        "type" => "text",
                        "level" => 1,
                        "required" => false,
                        "placeholder" => "80302",
                    ),
//					"location_id" => array(
//						"title"       => esc_html__( "Location ID", 'gshopping-wc-google-shopping' ),
//						"placeholder" => esc_html__( "Location ID", 'gshopping-wc-google-shopping' ),
//						"type"        => "text",
//						"level"       => 1,
//						"required"    => false,
//						"placeholder" => "",
//					),
                    "tax_ship" => array(
                        "title" => esc_html__("Tax ship", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("yes", 'gshopping-wc-google-shopping'),
                        "type" => "select",
                        "level" => 1,
                        "required" => false,
                        "placeholder" => "",
                        "option" => array(
                            "yes" => esc_html__("Yes", 'gshopping-wc-google-shopping'),
                            "no" => esc_html__("No", 'gshopping-wc-google-shopping'),
                        ),
                    ),
                    "rate" => array(
                        "title" => esc_html__("Rate", 'gshopping-wc-google-shopping'),
                        "placeholder" => esc_html__("Rate", 'gshopping-wc-google-shopping'),
                        "type" => "float",
                        "level" => 1,
                        "required" => true,
                        "placeholder" => "9.5",
                    ),
                ),
            ),
            "tax_category" => array(
                "support" => "7569847",
                "title" => esc_html__("Tax category", 'gshopping-wc-google-shopping'),
                "required" => false,
                "description" => esc_html__("A category that classifies your product by specific tax rules.", 'gshopping-wc-google-shopping'),
                "type" => "text",
                "max" => 100,
                "placeholder" => "Apparel",
                "level" => 0,
            ),
        );
    }

    public function get_params($name)
    {
        if (!$name) {
            return $this->params;
        } elseif (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return false;
        }
    }

    public function get_schedule()
    {
        return $this->schedule;
    }

    public function get_sheet()
    {
        return $this->sheet;
    }

    public function get_api()
    {
        return $this->api;
    }

    public function update_param($name, $value)
    {
        $current = get_option(PFVI_PREFIX_META . 'woocommerce_google_shopping');
        $current[$name] = $value;
        update_option(PFVI_PREFIX_META . 'woocommerce_google_shopping', $current, false);
    }

    public function get_languages($name)
    {
        if (!$name) {
            return $this->language;
        } elseif (isset($this->language[$name])) {
            return $this->language[$name];
        } else {
            return false;
        }
    }

    public function get_countries($name)
    {
        if (!$name) {
            return $this->country;
        } elseif (isset($this->country[$name])) {
            return $this->country[$name];
        } else {
            return false;
        }
    }

    public function get_default($name)
    {
        if (!$name) {
            return $this->defaults;
        } elseif (isset($this->defaults[$name])) {
            return $this->defaults[$name];
        } else {
            return false;
        }
    }

    public function get_attributes($name)
    {
        if (!$name) {
            return $this->attributes;
        } elseif (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } else {
            return false;
        }
    }

    public function mapping_attr_config()
    {
        $attrs_config = $this->get_params("attributes");
        $result = array();
        foreach ($attrs_config as $attr) {
            $result[$attr] = $this->get_attributes($attr);
        }

        return $result;
    }

    public function get_google_categories()
    {
        $locale = str_replace('_', '-', get_locale());
//		$url        = 'https://www.google.com/basepages/producttype/taxonomy-with-ids.' . $locale . '.txt';
        $url = 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt';
        $categories = file($url);
        unset($categories[0]);
        $categories_split = array();

        foreach ($categories as $category) {
            $category = explode(" - ", $category);
            $categories_split[trim($category[0])] = trim($category[1]);
        }

        return $categories_split;
    }

    public function underscoreToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    public function get_token()
    {
        $client_id = $this->get_params('client_id');
        $client_secret = $this->get_params('client_secret');
        $redirect_uri = $this->get_params('redirect_uri');
        $access_token = $this->get_params('access_token');
        $refresh_token = $this->get_params('refresh_token');
        $list_params = $this->get_params("");

        $timelife_refresh = 200;
        //		200 days

        $url_page_config = admin_url("admin.php?page=vi-product-feed") . '#config_credential';
        if (empty($client_id)) {
            $error = esc_html("Please config client ID, <a href='$url_page_config'>click here</a>");
            add_settings_error('oauth-error', 'error-client-id', $error);

            echo "<script>window.close()</script>";

            return false;
        }
        if (empty($client_secret)) {
            $error = esc_html("Please config client secret, <a href='$url_page_config'>click here</a>");
            add_settings_error('oauth-error', 'error-client-secret', $error);

            echo "<script>window.close()</script>";

            return false;
        }

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->addScope('https://www.googleapis.com/auth/spreadsheets');
        $client->addScope('https://www.googleapis.com/auth/content');
        $client->setRedirectUri($redirect_uri);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setIncludeGrantedScopes(true);

        if ((!empty($access_token)) && (!isset($_GET['code']))) {
            $client->setAccessToken($access_token);
            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else if (!empty($refresh_token)) {
                    $datetime_expire_refresh = intval($refresh_token['created']) + ($timelife_refresh * 24 * 60 * 60);
                    if ($datetime_expire_refresh > time()) {
                        $client->fetchAccessTokenWithRefreshToken($refresh_token['refresh_token']);
                    } else {
                        // Request authorization from the user.
                        $auth_url = esc_url_raw($client->createAuthUrl());
                        if (isset($_GET['view']) && ($_GET['view'] == 'oauth')) {
                            header("Location: $auth_url");
                        } else {
                            echo sanitize_url($auth_url);
                            die();
                        }
//					header("Location: $auth_url");
                    }
                }
            }
        } else {
            if (isset($_GET['code'])) {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

                $client->setAccessToken($token);

                $list_params['refresh_token'] = array(
                    'refresh_token' => $token['refresh_token'],
                    'created' => $token['created'],
                );

                if (array_key_exists('error', $token)) {
                    throw new Exception(json(',', $token));
                }
            } else {
                $auth_url = esc_url_raw($client->createAuthUrl());
                if (isset($_GET['view']) && ($_GET['view'] == 'oauth')) {
                    header("Location: $auth_url");
                } else {
                    echo sanitize_url($auth_url);
                    die();
                }
//				header("Location: $auth_url");
            }
        }

        // Save the token to a config.
        $list_params['access_token'] = $client->getAccessToken();
        update_option(PFVI_PREFIX_META . 'woocommerce_google_shopping', $list_params, false);

        echo "<script>window.close()</script>";
//		return $client;
    }

    public function check_token_expire()
    {
        $access_token = $this->get_params('access_token');
        if (!empty($access_token) && is_array($access_token)) {
            $expires_in = $access_token['expires_in'];
            $created = $access_token['created'];
            $end = $expires_in + $created;
            $now = time();
            if ($now > $end) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function delete_config()
    {
        delete_option(PFVI_PREFIX_META . 'woocommerce_google_shopping');
    }

    public function currency_unit()
    {
        return get_option('woocommerce_currency');
    }

    public function convert_repeated($arr = [])
    {
        $count = 0;
        $result = array();
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                $arr[$key] = explode(",", $value);
                $count_value = count($arr[$key]);
                if ($count_value > $count) {
                    $count = $count_value;
                }
            }

            $keys = array_keys($arr);

            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $result[$i] = array();
                    foreach ($keys as $key) {
                        $result[$i][$key] = sanitize_text_field(($arr[$key][$i]) ?? "");
                    }
                }
            }
        }

        return $result;

    }

    public function reconvert_repeated($arr = [])
    {
        $result = array();
        if(isset($arr['type']) && ($arr['type'] == 'multiple')){
            unset($arr['type']);
        }
        if (!empty($arr)) {
            foreach ($arr as $count => $values) {
                foreach ($values as $key => $value) {
                    if (!empty($value)) {
                        $result[$key][$count] = $value;
                    }
                }
            }
        }

        foreach ($result as $key => $value) {
            $result[$key] = implode(",", $value);
        }

        return $result;
    }
}