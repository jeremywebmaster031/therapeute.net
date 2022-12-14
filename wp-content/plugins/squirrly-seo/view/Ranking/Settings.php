<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<div id="sq_wrap">
    <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockToolbar')->init(); ?>
    <?php do_action('sq_notices'); ?>
    <div id="sq_content" class="d-flex flex-row bg-white my-0 p-0 m-0">
        <?php
        if (!SQ_Classes_Helpers_Tools::userCan('sq_manage_focuspages')) {
            echo '<div class="col-12 alert alert-success text-center m-0 p-3">'. esc_html__("You do not have permission to access this page. You need Squirrly SEO Admin role.", 'squirrly-seo').'</div>';
            return;
        }
        ?>
        <?php $view->show_view('Blocks/Menu'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-light m-0 p-0">
            <div class="flex-grow-1 sq_flex m-0 py-0 px-5">
                <form method="POST">
                    <?php do_action('sq_form_notices'); ?>
                    <?php SQ_Classes_Helpers_Tools::setNonce('sq_ranking_settings', 'sq_nonce'); ?>
                    <input type="hidden" name="action" value="sq_ranking_settings"/>

                    <div class="col-12 p-0 m-0">

                        <div class="sq_breadcrumbs my-4"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_rankings/settings') ?></div>
                        <h3 class="mt-4">
                            <?php echo esc_html__("Rankings Settings", 'squirrly-seo'); ?>
                            <div class="sq_help_question d-inline">
                                <a href="https://howto12.squirrly.co/kb/ranking-serp-checker/#ranking_settings" target="_blank"><i class="fa-solid fa-question-circle m-0 p-0"></i></a>
                            </div>
                        </h3>

                        <div id="sq_seosettings" class="col-12 m-0 p-0 border-0">
                            <div class="col-12 m-0 p-0">
                                <div class="col-12 row p-0 m-0 my-5">
                                    <div class="col-4 p-0 pr-3 font-weight-bold">
                                        <div class="font-weight-bold"><?php echo esc_html__("Google Country", 'squirrly-seo'); ?>:</div>
                                        <div class="small text-black-50 my-1 pr-3"><?php echo esc_html__("Select the Country for which Squirrly will check the Google rank.", 'squirrly-seo'); ?></div>
                                    </div>
                                    <div class="col-8 p-0 input-group">
                                        <select name="sq_google_country" class="form-control bg-input mb-1">
                                            <option value="com"><?php echo esc_html__("Default", 'squirrly-seo'); ?> - Google.com (https://www.google.com/)</option>
                                            <option value="as"><?php echo "American Samoa"; ?> (https://www.google.as/)</option>
                                            <option value="off.ai"><?php echo "Anguilla"; ?> (https://www.google.off.ai/)</option>
                                            <option value="com.ag"><?php echo "Antigua and Barbuda"; ?> (https://www.google.com.ag/)</option>
                                            <option value="com.ar"><?php echo "Argentina"; ?> (https://www.google.com.ar/)</option>
                                            <option value="com.au"><?php echo "Australia"; ?> (https://www.google.com.au/)</option>
                                            <option value="at"><?php echo "Austria"; ?> (https://www.google.at/)</option>
                                            <option value="az"><?php echo "Azerbaijan"; ?> (https://www.google.az/)</option>
                                            <option value="be"><?php echo "Belgium"; ?> (https://www.google.be/)</option>
                                            <option value="com.br"><?php echo "Brazil"; ?> (https://www.google.com.br/)</option>
                                            <option value="vg"><?php echo "British Virgin Islands"; ?> (https://www.google.vg/)</option>
                                            <option value="bi"><?php echo "Burundi"; ?> (https://www.google.bi/)</option>
                                            <option value="bg"><?php echo "Bulgaria"; ?> (https://www.google.bg/)</option>
                                            <option value="ca"><?php echo "Canada"; ?> (https://www.google.ca/)</option>
                                            <option value="td"><?php echo "Chad"; ?> (https://www.google.td/)</option>
                                            <option value="cl"><?php echo "Chile"; ?> (https://www.google.cl/)</option>
                                            <option value="com.co"><?php echo "Colombia"; ?> (https://www.google.com.co/)</option>
                                            <option value="co.cr"><?php echo "Costa Rica"; ?> (https://www.google.co.cr/)</option>
                                            <option value="ci"><?php echo "C??te d\'Ivoire"; ?> (https://www.google.ci/)</option>
                                            <option value="com.cu"><?php echo "Cuba"; ?> (https://www.google.com.cu/)</option>
                                            <option value="cz"><?php echo "Czech Republic"; ?> (https://www.google.cz/)</option>
                                            <option value="cd"><?php echo "Dem. Rep. of the Congo"; ?> (https://www.google.cd/)</option>
                                            <option value="dk"><?php echo "Denmark"; ?> (https://www.google.dk/)</option>
                                            <option value="dj"><?php echo "Djibouti"; ?> (https://www.google.dj/)</option>
                                            <option value="com.do"><?php echo "Dominican Republic"; ?> (https://www.google.com.do/)</option>
                                            <option value="com.ec"><?php echo "Ecuador"; ?> (https://www.google.com.ec/)</option>
                                            <option value="com.eg"><?php echo "Egypt"; ?> (https://www.google.com.eg/)</option>
                                            <option value="com.sv"><?php echo "El Salvador"; ?> (https://www.google.com.sv/)</option>
                                            <option value="ee"><?php echo "Estonia"; ?> (https://www.google.ee/)</option>
                                            <option value="fm"><?php echo "Federated States of Micronesia"; ?> (https://www.google.fm/)</option>
                                            <option value="com.fj"><?php echo "Fiji"; ?> (https://www.google.com.fj/)</option>
                                            <option value="fi"><?php echo "Finland"; ?> (https://www.google.fi/)</option>
                                            <option value="fr"><?php echo "France"; ?> (https://www.google.fr/)</option>
                                            <option value="gm"><?php echo "The Gambia"; ?> (https://www.google.gm/)</option>
                                            <option value="ge"><?php echo "Georgia"; ?> (https://www.google.ge/)</option>
                                            <option value="de"><?php echo "Germany"; ?> (https://www.google.de/)</option>
                                            <option value="com.gh"><?php echo "Ghana "; ?> (https://www.google.com.gh/)</option>
                                            <option value="com.gi"><?php echo "Gibraltar"; ?> (https://www.google.com.gi/)</option>
                                            <option value="com.gr"><?php echo "Greece"; ?> (https://www.google.com.gr/)</option>
                                            <option value="gl"><?php echo "Greenland"; ?> (https://www.google.gl/)</option>
                                            <option value="com.gt"><?php echo "Guatemala"; ?> (https://www.google.com.gt/)</option>
                                            <option value="gg"><?php echo "Guernsey"; ?> (https://www.google.gg/)</option>
                                            <option value="hn"><?php echo "Honduras"; ?> (https://www.google.hn/)</option>
                                            <option value="com.hk"><?php echo "Hong Kong"; ?> (https://www.google.com.hk/)</option>
                                            <option value="co.hu"><?php echo "Hungary"; ?> (https://www.google.co.hu/)</option>
                                            <option value="co.in"><?php echo "India"; ?> (https://www.google.co.in/)</option>
                                            <option value="co.id"><?php echo "Indonesia"; ?> (https://www.google.co.id/)</option>
                                            <option value="ie"><?php echo "Ireland"; ?> (https://www.google.ie/)</option>
                                            <option value="co.im"><?php echo "Isle of Man"; ?> (https://www.google.co.im/)</option>
                                            <option value="co.il"><?php echo "Israel"; ?> (https://www.google.co.il/)</option>
                                            <option value="it"><?php echo "Italy"; ?> (https://www.google.it/)</option>
                                            <option value="com.jm"><?php echo "Jamaica"; ?> (https://www.google.com.jm/)</option>
                                            <option value="co.jp"><?php echo "Japan"; ?> (https://www.google.co.jp/)</option>
                                            <option value="co.je"><?php echo "Jersey"; ?> (https://www.google.co.je/)</option>
                                            <option value="kz"><?php echo "Kazakhstan"; ?> (https://www.google.kz/)</option>
                                            <option value="kz"><?php echo "Kazakhstan"; ?> (https://www.google.kz/)</option>
                                            <option value="co.ke"><?php echo "Kenya"; ?> (https://www.google.co.ke/)</option>
                                            <option value="lv"><?php echo "Latvia"; ?> (https://www.google.lv/)</option>
                                            <option value="co.ls"><?php echo "Lesotho"; ?> (https://www.google.co.ls/)</option>
                                            <option value="li"><?php echo "Liechtenstein"; ?> (https://www.google.li/)</option>
                                            <option value="lt"><?php echo "Lithuania"; ?> (https://www.google.lt/)</option>
                                            <option value="lu"><?php echo "Luxembourg"; ?> (https://www.google.lu/)</option>
                                            <option value="mw"><?php echo "Malawi"; ?> (https://www.google.mw/)</option>
                                            <option value="com.my"><?php echo "Malaysia"; ?> (https://www.google.com.my/)</option>
                                            <option value="com.mt"><?php echo "Malta"; ?> (https://www.google.com.mt/)</option>
                                            <option value="mu"><?php echo "Mauritius"; ?> (https://www.google.mu/)</option>
                                            <option value="com.mx"><?php echo "M??xico"; ?> (https://www.google.com.mx/)</option>
                                            <option value="ms"><?php echo "Montserrat"; ?> (https://www.google.ms/)</option>
                                            <option value="com.na"><?php echo "Namibia"; ?> (https://www.google.com.na/)</option>
                                            <option value="com.np"><?php echo "Nepal"; ?> (https://www.google.com.np/)</option>
                                            <option value="nl"><?php echo "Netherlands"; ?> (https://www.google.nl/)</option>
                                            <option value="co.nz"><?php echo "New Zealand"; ?> (https://www.google.co.nz/)</option>
                                            <option value="com.ni"><?php echo "Nicaragua"; ?> (https://www.google.com.ni/)</option>
                                            <option value="com.ng"><?php echo "Nigeria"; ?> (https://www.google.com.ng/)</option>
                                            <option value="com.nf"><?php echo "Norfolk Island"; ?> (https://www.google.com.nf/)</option>
                                            <option value="no"><?php echo "Norway"; ?> (https://www.google.no/)</option>
                                            <option value="com.pk"><?php echo "Pakistan"; ?> (https://www.google.com.pk/)</option>
                                            <option value="com.pa"><?php echo "Panam??"; ?> (https://www.google.com.pa/)</option>
                                            <option value="com.py"><?php echo "Paraguay"; ?> (https://www.google.com.py/)</option>
                                            <option value="com.pe"><?php echo "Per??"; ?> (https://www.google.com.pe/)</option>
                                            <option value="com.ph"><?php echo "Philippines"; ?> (https://www.google.com.ph/)</option>
                                            <option value="pn"><?php echo "Pitcairn Islands"; ?> (https://www.google.pn/)</option>
                                            <option value="pl"><?php echo "Poland"; ?> (https://www.google.pl/)</option>
                                            <option value="pt"><?php echo "Portugal"; ?> (https://www.google.pt/)</option>
                                            <option value="com.pr"><?php echo "Puerto Rico"; ?> (https://www.google.com.pr/)</option>
                                            <option value="cg"><?php echo "Rep. of the Congo"; ?> (https://www.google.cg/)</option>
                                            <option value="ro"><?php echo "Romania"; ?> (https://www.google.ro/)</option>
                                            <option value="ru"><?php echo "Russia"; ?> (https://www.google.ru/)</option>
                                            <option value="rw"><?php echo "Rwanda"; ?> (https://www.google.rw/)</option>
                                            <option value="sh"><?php echo "Saint Helena"; ?> (https://www.google.sh/)</option>
                                            <option value="sm"><?php echo "San Marino"; ?> (https://www.google.sm/)</option>
                                            <option value="com.sa"><?php echo "Saudi Arabia"; ?> (https://www.google.com.sa/)</option>
                                            <option value="com.sg"><?php echo "Singapore"; ?> (https://www.google.com.sg/)</option>
                                            <option value="sk"><?php echo "Slovakia"; ?> (https://www.google.sk/)</option>
                                            <option value="si"><?php echo "Slovenia"; ?> (https://www.google.si/)</option>
                                            <option value="co.za"><?php echo "South Africa"; ?> (https://www.google.co.za/)</option>
                                            <option value="es"><?php echo "Spain"; ?> (https://www.google.es/)</option>
                                            <option value="lk"><?php echo "Sri Lanka"; ?> (https://www.google.lk/)</option>
                                            <option value="se"><?php echo "Sweden"; ?> (https://www.google.se/)</option>
                                            <option value="ch"><?php echo "Switzerland"; ?> (https://www.google.ch/)</option>
                                            <option value="com.tw"><?php echo "Taiwan"; ?> (https://www.google.com.tw/)</option>
                                            <option value="co.th"><?php echo "Thailand"; ?> (https://www.google.co.th/)</option>
                                            <option value="tt"><?php echo "Trinidad and Tobago"; ?> (https://www.google.tt/)</option>
                                            <option value="com.tr"><?php echo "Turkey"; ?> (https://www.google.com.tr/)</option>
                                            <option value="com.ua"><?php echo "Ukraine"; ?> (https://www.google.com.ua/)</option>
                                            <option value="ae"><?php echo "United Arab Emirates"; ?> (https://www.google.ae/)</option>
                                            <option value="co.uk"><?php echo "United Kingdom"; ?> (https://www.google.co.uk/)</option>
                                            <option value="us"><?php echo "United States"; ?> (https://www.google.us/)</option>
                                            <option value="com.uy"><?php echo "Uruguay"; ?> (https://www.google.com.uy/)</option>
                                            <option value="uz"><?php echo "Uzbekistan"; ?> (https://www.google.uz/)</option>
                                            <option value="vu"><?php echo "Vanuatu"; ?> (https://www.google.vu/)</option>
                                            <option value="co.ve"><?php echo "Venezuela"; ?> (https://www.google.co.ve/)</option>
                                            <option value="com.vn"><?php echo "Vietnam"; ?> (https://www.google.com.vn/)</option>
                                        </select>
                                        <script>jQuery('select[name=sq_google_country]').val('<?php echo SQ_Classes_Helpers_Tools::getOption('sq_google_country')?>').attr('selected', true);</script>

                                    </div>
                                </div>

                                <div class="col-12 row p-0 m-0 my-5">
                                    <div class="col-4 p-0 pr-3 font-weight-bold">
                                        <div class="font-weight-bold"><?php echo esc_html__("Google Language", 'squirrly-seo'); ?>:</div>
                                        <div class="small text-black-50 my-1 pr-3"><?php echo esc_html__("Select the Language for which Squirrly will check the Google rank.", 'squirrly-seo'); ?></div>
                                    </div>
                                    <div class="col-8 p-0 input-group">
                                        <select name="sq_google_language" class="form-control bg-input mb-1">
                                            <option value="af">Afrikaans</option>
                                            <option value="sq">Albanian - shqip</option>
                                            <option value="am">Amharic - ????????????</option>
                                            <option value="ar">Arabic - ??????????????</option>
                                            <option value="an">Aragonese - aragon??s</option>
                                            <option value="hy">Armenian - ??????????????</option>
                                            <option value="ast">Asturian - asturianu</option>
                                            <option value="az">Azerbaijani - az??rbaycan dili</option>
                                            <option value="eu">Basque - euskara</option>
                                            <option value="be">Belarusian - ????????????????????</option>
                                            <option value="bn">Bengali - ???????????????</option>
                                            <option value="bs">Bosnian - bosanski</option>
                                            <option value="br">Breton - brezhoneg</option>
                                            <option value="bg">Bulgarian - ??????????????????</option>
                                            <option value="ca">Catalan - catal??</option>
                                            <option value="ckb">Central Kurdish - ?????????? (???????????????? ????????????)</option>
                                            <option value="zh">Chinese - ??????</option>
                                            <option value="zh_HK">Chinese (Hong Kong) - ??????????????????</option>
                                            <option value="zh_CN">Chinese (Simplified) - ??????????????????</option>
                                            <option value="zh_TW">Chinese (Traditional) - ??????????????????</option>
                                            <option value="co">Corsican</option>
                                            <option value="hr">Croatian - hrvatski</option>
                                            <option value="cs">Czech - ??e??tina</option>
                                            <option value="da">Danish - dansk</option>
                                            <option value="nl">Dutch - Nederlands</option>
                                            <option value="en">English</option>
                                            <option value="en_AU">English (Australia)</option>
                                            <option value="en_CA">English (Canada)</option>
                                            <option value="en_IN">English (India)</option>
                                            <option value="en_NZ">English (New Zealand)</option>
                                            <option value="en_ZA">English (South Africa)</option>
                                            <option value="en_GB">English (United Kingdom)</option>
                                            <option value="en_US">English (United States)</option>
                                            <option value="eo">Esperanto - esperanto</option>
                                            <option value="et">Estonian - eesti</option>
                                            <option value="fo">Faroese - f??royskt</option>
                                            <option value="fil">Filipino</option>
                                            <option value="fi">Finnish - suomi</option>
                                            <option value="fr">French - fran??ais</option>
                                            <option value="fr_CA">French (Canada) - fran??ais (Canada)</option>
                                            <option value="fr_FR">French (France) - fran??ais (France)</option>
                                            <option value="fr_CH">French (Switzerland) - fran??ais (Suisse)</option>
                                            <option value="gl">Galician - galego</option>
                                            <option value="ka">Georgian - ?????????????????????</option>
                                            <option value="de">German - Deutsch</option>
                                            <option value="de_AT">German (Austria) - Deutsch (??sterreich)</option>
                                            <option value="de_DE">German (Germany) - Deutsch (Deutschland)</option>
                                            <option value="de_LI">German (Liechtenstein) - Deutsch (Liechtenstein)</option>
                                            <option value="de_CH">German (Switzerland) - Deutsch (Schweiz)</option>
                                            <option value="el">Greek - ????????????????</option>
                                            <option value="gn">Guarani</option>
                                            <option value="gu">Gujarati - ?????????????????????</option>
                                            <option value="ha">Hausa</option>
                                            <option value="haw">Hawaiian - ????lelo Hawai??i</option>
                                            <option value="he">Hebrew - ??????????</option>
                                            <option value="hi">Hindi - ??????????????????</option>
                                            <option value="hu">Hungarian - magyar</option>
                                            <option value="is">Icelandic - ??slenska</option>
                                            <option value="id">Indonesian - Indonesia</option>
                                            <option value="ia">Interlingua</option>
                                            <option value="ga">Irish - Gaeilge</option>
                                            <option value="it">Italian - italiano</option>
                                            <option value="it_IT">Italian (Italy) - italiano (Italia)</option>
                                            <option value="it_CH">Italian (Switzerland) - italiano (Svizzera)</option>
                                            <option value="ja">Japanese - ?????????</option>
                                            <option value="kn">Kannada - ???????????????</option>
                                            <option value="kk">Kazakh - ?????????? ????????</option>
                                            <option value="km">Khmer - ???????????????</option>
                                            <option value="ko">Korean - ?????????</option>
                                            <option value="ku">Kurdish - Kurd??</option>
                                            <option value="ky">Kyrgyz - ????????????????</option>
                                            <option value="lo">Lao - ?????????</option>
                                            <option value="la">Latin</option>
                                            <option value="lv">Latvian - latvie??u</option>
                                            <option value="ln">Lingala - ling??la</option>
                                            <option value="lt">Lithuanian - lietuvi??</option>
                                            <option value="mk">Macedonian - ????????????????????</option>
                                            <option value="ms">Malay - Bahasa Melayu</option>
                                            <option value="ml">Malayalam - ??????????????????</option>
                                            <option value="mt">Maltese - Malti</option>
                                            <option value="mr">Marathi - ???????????????</option>
                                            <option value="mn">Mongolian - ????????????</option>
                                            <option value="ne">Nepali - ??????????????????</option>
                                            <option value="no">Norwegian - norsk</option>
                                            <option value="nb">Norwegian Bokm??l - norsk bokm??l</option>
                                            <option value="nn">Norwegian Nynorsk - nynorsk</option>
                                            <option value="oc">Occitan</option>
                                            <option value="or">Oriya - ???????????????</option>
                                            <option value="om">Oromo - Oromoo</option>
                                            <option value="ps">Pashto - ????????</option>
                                            <option value="fa">Persian - ??????????</option>
                                            <option value="pl">Polish - polski</option>
                                            <option value="pt">Portuguese - portugu??s</option>
                                            <option value="pt_BR">Portuguese (Brazil) - portugu??s (Brasil)</option>
                                            <option value="pt_PT">Portuguese (Portugal) - portugu??s (Portugal)</option>
                                            <option value="pa">Punjabi - ??????????????????</option>
                                            <option value="qu">Quechua</option>
                                            <option value="ro">Romanian - rom??n??</option>
                                            <option value="mo">Romanian (Moldova) - rom??n?? (Moldova)</option>
                                            <option value="rm">Romansh - rumantsch</option>
                                            <option value="ru">Russian - ??????????????</option>
                                            <option value="gd">Scottish Gaelic</option>
                                            <option value="sr">Serbian - ????????????</option>
                                            <option value="sh">Serbo-Croatian - Srpskohrvatski</option>
                                            <option value="sn">Shona - chiShona</option>
                                            <option value="sd">Sindhi</option>
                                            <option value="si">Sinhala - ???????????????</option>
                                            <option value="sk">Slovak - sloven??ina</option>
                                            <option value="sl">Slovenian - sloven????ina</option>
                                            <option value="so">Somali - Soomaali</option>
                                            <option value="st">Southern Sotho</option>
                                            <option value="es">Spanish - espa??ol</option>
                                            <option value="es_AR">Spanish (Argentina) - espa??ol (Argentina)</option>
                                            <option value="es_419">Spanish (Latin America) - espa??ol (Latinoam??rica)</option>
                                            <option value="es_MX">Spanish (Mexico) - espa??ol (M??xico)</option>
                                            <option value="es_ES">Spanish (Spain) - espa??ol (Espa??a)</option>
                                            <option value="es_US">Spanish (United States) - espa??ol (Estados Unidos)</option>
                                            <option value="su">Sundanese</option>
                                            <option value="sw">Swahili - Kiswahili</option>
                                            <option value="sv">Swedish - svenska</option>
                                            <option value="tg">Tajik - ????????????</option>
                                            <option value="ta">Tamil - ???????????????</option>
                                            <option value="tt">Tatar</option>
                                            <option value="te">Telugu - ??????????????????</option>
                                            <option value="th">Thai - ?????????</option>
                                            <option value="ti">Tigrinya - ????????????</option>
                                            <option value="to">Tongan - lea fakatonga</option>
                                            <option value="tr">Turkish - T??rk??e</option>
                                            <option value="tk">Turkmen</option>
                                            <option value="tw">Twi</option>
                                            <option value="uk">Ukrainian - ????????????????????</option>
                                            <option value="ur">Urdu - ????????</option>
                                            <option value="ug">Uyghur</option>
                                            <option value="uz">Uzbek - o???zbek</option>
                                            <option value="vi">Vietnamese - Ti???ng Vi???t</option>
                                            <option value="wa">Walloon - wa</option>
                                            <option value="cy">Welsh - Cymraeg</option>
                                            <option value="fy">Western Frisian</option>
                                            <option value="xh">Xhosa</option>
                                            <option value="yi">Yiddish</option>
                                            <option value="yo">Yoruba - ??d?? Yor??b??</option>
                                            <option value="zu">Zulu - isiZulu</option>
                                        </select>
                                        <script>jQuery('select[name=sq_google_language]').val('<?php echo SQ_Classes_Helpers_Tools::getOption('sq_google_language')?>').attr('selected', true);</script>

                                    </div>
                                </div>

                                <div class="col-12 row p-0 m-0 my-5">
                                    <div class="col-4 p-0 pr-3 font-weight-bold">
                                        <div class="font-weight-bold"><?php echo esc_html__("Device", 'squirrly-seo'); ?>:</div>
                                        <div class="small text-black-50 my-1 pr-3"><?php echo esc_html__("Select the Device for which Squirrly will check the Google rank.", 'squirrly-seo'); ?></div>
                                    </div>
                                    <div class="col-8 p-0 input-group">
                                        <select name="sq_google_device" class="form-control bg-input mb-1">
                                            <option value="desktop">Desktop</option>
                                            <option value="tablet">Tablet</option>
                                            <option value="mobile">Mobile</option>
                                        </select>
                                        <script>jQuery('select[name=sq_google_device]').val('<?php echo SQ_Classes_Helpers_Tools::getOption('sq_google_device')?>').attr('selected', true);</script>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-12 m-0 p-0">
                        <button type="submit" class="btn rounded-0 btn-primary btn-lg py-2 px-5"><?php echo esc_html__("Save Settings", 'squirrly-seo'); ?></button>
                    </div>
                </form>

                <div class="sq_tips col-12 m-0 p-0 my-5">
                    <h5 class="text-left my-3 font-weight-bold"><i class="fa-solid fa-exclamation-circle" ></i> <?php echo esc_html__("Tips and Tricks", 'squirrly-seo'); ?></h5>
                    <ul class="mx-4 my-1">
                        <li class="text-left small"><?php echo esc_html__("Complete the Mastery Tasks you see on the right side of your screen to make the most out of the Rankings section of Squirrly SEO.", 'squirrly-seo'); ?></li>
                        <li class="text-left small"><?php echo esc_html__("Follow the instructions to mark every task as Completed.", 'squirrly-seo'); ?></li>
                    </ul>
                </div>

                <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockKnowledgeBase')->init(); ?>

            </div>
            <div class="sq_col_side bg-white">
                <div class="col-12 m-0 p-0 sq_sticky">
                    <?php echo SQ_Classes_ObjController::getClass('SQ_Core_BlockAssistant')->init(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
