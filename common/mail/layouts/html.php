<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <meta charset="UTF-8"></meta>
        <meta content="width=device-width, initial-scale=1" name="viewport"></meta>
        <meta content="telephone=no" name="format-detection"></meta>
        <style>
            /*
CONFIG STYLES
Please do not delete and edit CSS styles below
            */
            /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
            #outlook a {
                padding: 0;
            }

            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .es-button {
                mso-style-priority: 100 !important;
                text-decoration: none !important;
            }

            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }

            .es-desk-hidden {
                display: none;
                float: left;
                overflow: hidden;
                width: 0;
                max-height: 0;
                line-height: 0;
                mso-hide: all;
            }

            /*
            END OF IMPORTANT
            */
            html,
            body {
                width: 100%;
                font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }

            table {
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
            }

            table td,
            html,
            body,
            .es-wrapper {
                padding: 0;
                Margin: 0;
            }

            .es-content,
            .es-header,
            .es-footer {
                table-layout: fixed !important;
                width: 100%;
            }

            img {
                display: block;
                border: 0;
                outline: none;
                text-decoration: none;
                -ms-interpolation-mode: bicubic;
            }

            table tr {
                border-collapse: collapse;
            }

            p,
            hr {
                Margin: 0;
            }

            h1,
            h2,
            h3,
            h4,
            h5 {
                Margin: 0;
                line-height: 120%;
                mso-line-height-rule: exactly;
                font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif;
            }

            p,
            ul li,
            ol li,
            a {
                -webkit-text-size-adjust: none;
                -ms-text-size-adjust: none;
                mso-line-height-rule: exactly;
            }

            .es-left {
                float: left;
            }

            .es-right {
                float: right;
            }

            .es-p5 {
                padding: 5px;
            }

            .es-p5t {
                padding-top: 5px;
            }

            .es-p5b {
                padding-bottom: 5px;
            }

            .es-p5l {
                padding-left: 5px;
            }

            .es-p5r {
                padding-right: 5px;
            }

            .es-p10 {
                padding: 10px;
            }

            .es-p10t {
                padding-top: 10px;
            }

            .es-p10b {
                padding-bottom: 10px;
            }

            .es-p10l {
                padding-left: 10px;
            }

            .es-p10r {
                padding-right: 10px;
            }

            .es-p15 {
                padding: 15px;
            }

            .es-p15t {
                padding-top: 15px;
            }

            .es-p15b {
                padding-bottom: 15px;
            }

            .es-p15l {
                padding-left: 15px;
            }

            .es-p15r {
                padding-right: 15px;
            }

            .es-p20 {
                padding: 20px;
            }

            .es-p20t {
                padding-top: 20px;
            }

            .es-p20b {
                padding-bottom: 20px;
            }

            .es-p20l {
                padding-left: 20px;
            }

            .es-p20r {
                padding-right: 20px;
            }

            .es-p25 {
                padding: 25px;
            }

            .es-p25t {
                padding-top: 25px;
            }

            .es-p25b {
                padding-bottom: 25px;
            }

            .es-p25l {
                padding-left: 25px;
            }

            .es-p25r {
                padding-right: 25px;
            }

            .es-p30 {
                padding: 30px;
            }

            .es-p30t {
                padding-top: 30px;
            }

            .es-p30b {
                padding-bottom: 30px;
            }

            .es-p30l {
                padding-left: 30px;
            }

            .es-p30r {
                padding-right: 30px;
            }

            .es-p35 {
                padding: 35px;
            }

            .es-p35t {
                padding-top: 35px;
            }

            .es-p35b {
                padding-bottom: 35px;
            }

            .es-p35l {
                padding-left: 35px;
            }

            .es-p35r {
                padding-right: 35px;
            }

            .es-p40 {
                padding: 40px;
            }

            .es-p40t {
                padding-top: 40px;
            }

            .es-p40b {
                padding-bottom: 40px;
            }

            .es-p40l {
                padding-left: 40px;
            }

            .es-p40r {
                padding-right: 40px;
            }

            .es-menu td {
                border: 0;
            }

            .es-menu td a img {
                display: inline-block !important;
            }

            /*
            END CONFIG STYLES
            */
            a {
                font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif;
                font-size: 14px;
                text-decoration: underline;
            }

            h1 {
                font-size: 30px;
                font-style: normal;
                font-weight: normal;
                color: #333333;
            }

            h2 {
                font-size: 24px;
                font-style: normal;
                font-weight: normal;
                color: #333333;
            }

            h3 {
                font-size: 20px;
                font-style: normal;
                font-weight: normal;
                color: #333333;
            }

            p,
            ul li,
            ol li {
                font-size: 14px;
                font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif;
                line-height: 150%;
            }

            ul li,
            ol li {
                Margin-bottom: 15px;
            }

            .es-menu td a {
                text-decoration: none;
                display: block;
            }

            .es-wrapper {
                width: 100%;
                height: 100%;
                background-image: ;
                background-repeat: repeat;
                background-position: center top;
            }

            .es-wrapper-color {
                background-color: #cccccc;
            }

            .es-content-body {
                background-color: #ffffff;
            }

            .es-content-body p,
            .es-content-body ul li,
            .es-content-body ol li {
                color: #666666;
            }

            .es-content-body a {
                color: #4a7eb0;
            }

            .es-header {
                background-color: transparent;
                background-image: ;
                background-repeat: repeat;
                background-position: center top;
            }

            .es-header-body {
                background-color: #ffffff;
            }

            .es-header-body p,
            .es-header-body ul li,
            .es-header-body ol li {
                color: #cccccc;
                font-size: 14px;
            }

            .es-header-body a {
                color: #cccccc;
                font-size: 14px;
            }

            .es-footer {
                background-color: transparent;
                background-image: ;
                background-repeat: repeat;
                background-position: center top;
            }

            .es-footer-body {
                background-color: #efefef;
            }

            .es-footer-body p,
            .es-footer-body ul li,
            .es-footer-body ol li {
                color: #333333;
                font-size: 14px;
            }

            .es-footer-body a {
                color: #333333;
                font-size: 14px;
            }

            .es-infoblock,
            .es-infoblock p,
            .es-infoblock ul li,
            .es-infoblock ol li {
                line-height: 120%;
                font-size: 12px;
                color: #cccccc;
            }

            .es-infoblock a {
                font-size: 12px;
                color: #cccccc;
            }

            a.es-button {
                border-style: solid;
                border-color: #efefef;
                border-width: 10px 20px 10px 20px;
                display: inline-block;
                background: #efefef;
                border-radius: 0px;
                font-size: 18px;
                font-family: 'arial', 'helvetica neue', 'helvetica', 'sans-serif';
                font-weight: normal;
                font-style: normal;
                line-height: 120%;
                color: #4a7eb0;
                text-decoration: none;
                width: auto;
                text-align: center;
            }

            .es-button-border {
                border-style: solid solid solid solid;
                border-color: #4a7eb0 #4a7eb0 #4a7eb0 #4a7eb0;
                background: #2cb543;
                border-width: 0px 0px 0px 0px;
                display: inline-block;
                border-radius: 0px;
                width: auto;
            }

            /*
            RESPONSIVE STYLES
            Please do not delete and edit CSS styles below.
             
            If you don't need responsive layout, please delete this section.
            */
            @media only screen and (max-width: 600px) {

                p,
                ul li,
                ol li,
                a {
                    font-size: 16px !important;
                    line-height: 150% !important;
                }

                h1 {
                    font-size: 30px !important;
                    text-align: center;
                    line-height: 120% !important;
                }

                h2 {
                    font-size: 26px !important;
                    text-align: center;
                    line-height: 120% !important;
                }

                h3 {
                    font-size: 20px !important;
                    text-align: center;
                    line-height: 120% !important;
                }

                .es-menu td a {
                    font-size: 16px !important;
                }

                .es-header-body p,
                .es-header-body ul li,
                .es-header-body ol li,
                .es-header-body a {
                    font-size: 16px !important;
                }

                .es-footer-body p,
                .es-footer-body ul li,
                .es-footer-body ol li,
                .es-footer-body a {
                    font-size: 16px !important;
                }

                .es-infoblock p,
                .es-infoblock ul li,
                .es-infoblock ol li,
                .es-infoblock a {
                    font-size: 12px !important;
                }

                *[class="gmail-fix"] {
                    display: none !important;
                }

                .es-m-txt-c {
                    text-align: center !important;
                }

                .es-m-txt-r {
                    text-align: right !important;
                }

                .es-m-txt-l {
                    text-align: left !important;
                }

                .es-m-txt-r img,
                .es-m-txt-c img,
                .es-m-txt-l img {
                    display: inline !important;
                }

                .es-button-border {
                    display: block !important;
                }

                a.es-button {
                    font-size: 20px !important;
                    display: block !important;
                    border-width: 10px 0px 10px 0px !important;
                }

                .es-btn-fw {
                    border-width: 10px 0px !important;
                    text-align: center !important;
                }

                .es-adaptive table,
                .es-btn-fw,
                .es-btn-fw-brdr,
                .es-left,
                .es-right {
                    width: 100% !important;
                }

                .es-content table,
                .es-header table,
                .es-footer table,
                .es-content,
                .es-footer,
                .es-header {
                    width: 100% !important;
                    max-width: 600px !important;
                }

                .es-adapt-td {
                    display: block !important;
                    width: 100% !important;
                }

                .adapt-img {
                    width: 100% !important;
                    height: auto !important;
                }

                .es-m-p0 {
                    padding: 0px !important;
                }

                .es-m-p0r {
                    padding-right: 0px !important;
                }

                .es-m-p0l {
                    padding-left: 0px !important;
                }

                .es-m-p0t {
                    padding-top: 0px !important;
                }

                .es-m-p0b {
                    padding-bottom: 0 !important;
                }

                .es-m-p20b {
                    padding-bottom: 20px !important;
                }

                .es-mobile-hidden,
                .es-hidden {
                    display: none !important;
                }

                .es-desk-hidden {
                    display: table-row !important;
                    width: auto !important;
                    overflow: visible !important;
                    float: none !important;
                    max-height: inherit !important;
                    line-height: inherit !important;
                }

                .es-desk-menu-hidden {
                    display: table-cell !important;
                }

                table.es-table-not-adapt,
                .esd-block-html table {
                    width: auto !important;
                }

                table.es-social td {
                    display: inline-block !important;
                }

                table.es-social {
                    display: inline-block !important;
                }
            }

            /*
            END RESPONSIVE STYLES
            */
        </style>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="es-wrapper-color">
            <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td class="esd-email-paddings" valign="top">
                            <!--<head>-->

                            <!--Put your preheader text here START-->
                            <!--                            <table class="es-content es-preheader esd-header-popover" cellspacing="0" cellpadding="0" align="center">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="es-adaptive esd-stripe" align="center">
                                                                        <table class="es-content-body" style="background-color: rgb(239, 239, 239);" width="600" cellspacing="0" cellpadding="0" bgcolor="#efefef" align="center">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="esd-structure es-p10t es-p10b es-p40r es-p40l" esd-general-paddings-checked="true" align="left">
                                                                                        <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td class="esd-container-frame" width="250" align="left">
                                                                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td class="es-infoblock esd-block-text" align="left"> Put your preheader text here</td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                        <table class="es-right" cellspacing="0" cellpadding="0" align="right">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td class="esd-container-frame" width="250" align="left">
                                                                                                        <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td class="es-infoblock esd-block-text" align="right">
                                                                                                                        <p><a href="https://viewstripo.email/" target="_blank">View in browser</a></p>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>-->
                            <!--Put your preheader text here END-->

                            <table class="es-header" cellspacing="0" cellpadding="0" align="center">
                                <tbody>
                                    <tr>
                                        <td class="es-adaptive esd-stripe" align="center">
                                            <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-structure es-p20t es-p20b es-p40r es-p40l" esd-general-paddings-checked="true" align="left">
                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="esd-container-frame" width="520" valign="top" align="center">
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="esd-block-image es-m-p0l" align="center"><a href target="_blank"><img src="https://demo.stripocdn.email/content/guids/daed0757-2384-4c9f-ac1c-823f5be87bb7/images/20201567693450054.png" alt="Smart home logo" title="Smart home logo" width="118" style="display: block;"></a></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--</head>-->

                            <!--<body>-->
                            <?= $content ?>                                
                            <!--</body>-->

                            <!--<footer>-->
                            <table class="es-content" cellspacing="0" cellpadding="0" align="center">
                                <tbody>
                                    <tr></tr>
                                    <tr>
                                        <td class="esd-stripe" esd-custom-block-id="3104" align="center">
                                            <table class="es-footer-body" style="background-color: rgb(239, 239, 239);" width="600" cellspacing="0" cellpadding="0" bgcolor="#efefef" align="center">
                                                <tbody>
                                                    <tr>
                                                        <td class="esd-structure es-p20" align="left">
                                                            <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-m-p0r es-m-p20b esd-container-frame" width="174" align="center">
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="esd-block-image es-m-p0l es-p10b" align="left"><a href target="_blank"><img src="https://demo.stripocdn.email/content/guids/daed0757-2384-4c9f-ac1c-823f5be87bb7/images/93831567693710221.png" alt width="103" style="display: block;"></a></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td class="es-hidden" width="20"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table class="es-left" cellspacing="0" cellpadding="0" align="left">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="es-m-p0r es-m-p20b esd-container-frame" width="173" align="center">
                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="esd-block-text" esdev-links-color="#333333" align="left">
                                                                                            <p style="color: rgb(51, 51, 51);">
                                                                                                <a target="_blank" style="color: rgb(51, 51, 51);" href="<?= Url::base('https') . Url::to(['/contact']) ?>">Contact Us</a>
                                                                                            </p>

                                                                                            <p style="color: rgb(51, 51, 51);">
                                                                                                <a target="_blank" style="color: rgb(51, 51, 51);" href="<?= Url::base('https') . Url::to(['/about']) ?>">About Us</a><br>
                                                                                                    <a target="_blank" style="color: rgb(51, 51, 51);" href="<?= Url::base('https') . Url::to(['/terms']) ?>">Terms and Conditions</a><br>
                                                                                                        <a target="_blank" style="color: rgb(51, 51, 51);" href="<?= Url::base('https') . Url::to(['/policy']) ?>">Privacy Policy</a><br>
                                                                                                            </p>
                                                                                                            </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                            </table>
                                                                                                            </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                            </table>
                                                                                                            <table class="es-right" cellspacing="0" cellpadding="0" align="right">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td class="es-m-p0r es-m-p20b esd-container-frame" width="173" align="center">
                                                                                                                            <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td class="es-m-txt-с esd-block-text es-p10b es-m-txt-l" esdev-links-color="#333333" align="left">
                                                                                                                                            <p style="color: rgb(51, 51, 51);"><span style="font-size: 20px; line-height: 150%;">8 (800) 12-34-56<br></span></p>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    <tr>
                                                                                                                                        <td class="es-m-txt-с esd-block-text es-p10b" esdev-links-color="#333333" align="left">
                                                                                                                                            <div style="color: rgb(51, 51, 51);"><span style="font-size:14px;">support@ayc.com</span></div>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                    
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                            </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td class="esd-structure es-p15b es-p20r es-p20l" esd-general-paddings-checked="false" align="left">
<!--                                                                                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                                        <tbody>
                                                                                                                            <tr>
                                                                                                                                <td class="esd-container-frame" width="560" valign="top" align="center">
                                                                                                                                    <table width="100%" cellspacing="0" cellpadding="0">
                                                                                                                                        <tbody>
                                                                                                                                            <tr>
                                                                                                                                                <td class="esd-block-text" esdev-links-color="#333333" align="left">
                                                                                                                                                    <p style="font-size: 12px; line-height: 150%;">You are receiving this email because you have visited our site or asked us about regular newsletter.</p>
                                                                                                                                                    <p style="font-size: 12px; line-height: 150%;"><a target="_blank" href style="font-size: 12px;">Unsubscribe</a>&nbsp; | <a target="_blank" href style="font-size: 12px;">Update Preferences</a> | <a target="_blank" href style="font-size: 12px;">Customer Support</a></p>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>-->
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                            </table>
                                                                                                            </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                            </table>
                                                                                                            <!--</footer>-->
                                                                                                            </td>
                                                                                                            </tr>
                                                                                                            </tbody>
                                                                                                            </table>
                                                                                                            </div>
                                                                                                            <?php $this->endBody() ?>
                                                                                                            </body>
                                                                                                            </html>
                                                                                                            <?php $this->endPage() ?>
