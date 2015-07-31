<?php

/*
+---------------------------------------------------------------------------+
| Revive Adserver                                                           |
| http://www.revive-adserver.com                                            |
|                                                                           |
| Copyright: See the COPYRIGHT.txt file.                                    |
| License: GPLv2 or later, see the LICENSE.txt file.                        |
+---------------------------------------------------------------------------+
*/

require_once MAX_PATH . '/lib/OA.php';
require_once MAX_PATH . '/lib/max/Plugin/Common.php';
require_once LIB_PATH . '/Extension/bannerTypeHtml/bannerTypeHtml.php';
require_once MAX_PATH . '/lib/max/Plugin/Translation.php';

class Plugins_BannerTypeHTML_terraTemplateHtml_terraTemplateHtmlComponent extends Plugins_BannerTypeHTML
{
    /**
     * Return type of plugin
     *
     * @return string A string describing the type of plugin.
     */
    function getOptionDescription()
    {
        return $this->translate("My HTML Banner");
    }

     function buildForm(&$form, &$row)
    {
        $form->setAttribute("onSubmit", "return max_formValidateHtml(this.banner)");
        $header = $form->createElement('header', 'header_html', $GLOBALS['strHTMLBanner']);
        $header->setAttribute('icon', 'icon-banner-html.gif');
        $form->addElement($header);

        $adPlugins = OX_Component::getComponents('3rdPartyServers');
        $adPluginsNames = OX_Component::callOnComponents($adPlugins, 'getName');

        $adPluginsList = array();
        $adPluginsList[''] = $GLOBALS['strAdserverTypeGeneric'];
        $adPluginsList['none'] = $GLOBALS['strDoNotAlterHtml'];
        foreach($adPluginsNames as $adPluginKey => $adPluginName) {
            $adPluginsList[$adPluginKey] = $adPluginName;
        }

        $form->addElement('select', 'adserver', $this->translate($GLOBALS['strAlterHTML']),$adPluginsList,$aSelectAttributes);
        $form->addElement('header', 'header_b_parameters',  $this->translate("Banner display"));
        $form->addElement('text', 'p_link_url', $this->translate($GLOBALS['strURL']));
        $form->addElement('text', 'p_link_text', $this->translate("Link text"));
        $form->addElement('text', 'p_title', $this->translate("Title"));
        $form->addElement('text', 'p_description', $this->translate("Description"));
        $form->addElement('text', 'p_image_url', $this->translate("Url Image"));
        $form->addElement('text', 'p_click_url_unesc', $this->translate("Click url Unesc"));
        $form->addElement('hidden', 'ext_bannertype', $this->getComponentIdentifier());

        $bannerId = $row['bannerid'];
        if ($bannerId){
            $doBanners = OA_Dal::factoryDO('my_banners');
            $doBanners->bannerid = $bannerId;
            $doBanners->find(true);
            $row['terra_link_url'] = $doBanners->terra_link_url;
            $row['terra_link_text'] = $doBanners->terra_link_text;
            $row['terra_title'] = $doBanners->terra_title;
            $row['terra_description'] = $doBanners->terra_description;
            $row['terra_image_url'] = $doBanners->terra_image_url;
            $row['terra_click_url_unesc'] = $doBanners->terra_click_url_unesc;
            $form->setDefaults(array(
                    'p_link_url'=>$doBanners->terra_link_url,
                    'p_link_text'=>$doBanners->terra_link_text,
                    'p_title'=>$doBanners->terra_title,
                    'p_description'=>$doBanners->terra_description,
                    'p_image_url'=>$doBanners->terra_image_url,
                    'p_click_url_unesc'=>$doBanners->terra_click_url_unesc,
                    ));
        }
    }

    function validateForm(&$form){
        return true;
    }

    /**
     * collate the parameters template data
     */
    function preprocessForm($insert, $bannerid, &$aFields, &$aVariables)
    {
        $htmlTemplate = $this->getMyTemplate();
        $aVariables['width']            = $htmlTemplate['template_width'];
        $aVariables['height']           = $htmlTemplate['template_height'];
        $aVariables['htmltemplate']     = $this->buildHtmlTemplate($htmlTemplate['template_code'],
                                                                    array(
                                                                          'p_link_url'=> $aFields['p_link_url'],
                                                                          'p_link_text' => $aFields['p_link_text'],
                                                                          'p_title' => $aFields['p_title'],
                                                                          'p_description' => $aFields['p_description'],
                                                                          'p_image_url' => $aFields['p_image_url'],
                                                                          'p_click_url_unesc' => $aFields['p_click_url_unesc'],
                                                                         )
                                                                  );

        return true;
    }

    function processForm($insert, $bannerid, $aFields){

        $my_banners = OA_Dal::factoryDO('my_banners');
        $my_banners->terra_link_url           = $aFields['p_link_url'];
        $my_banners->terra_link_text          = $aFields['p_link_text'];
        $my_banners->terra_title              = $aFields['p_title'];
        $my_banners->terra_description        = $aFields['p_description'];
        $my_banners->terra_image_url          = $aFields['p_image_url'];
        $my_banners->terra_click_url_unesc    = $aFields['p_click_url_unesc'];

        if ($insert){
            $my_banners->bannerid                 = $bannerid;
            return $my_banners->insert();
        }
        else{
            $my_banners->whereAdd('bannerid='.$bannerid, 'AND');
            return $my_banners->update(DB_DATAOBJECT_WHEREADD_ONLY);
        }
    }


    function getMyTemplate(){
         $code_template = '<style>

                     @font-face{
                                     font-family: "ubuntu";
                                     src: url("//s1.trrsf.com/fe/zaz-morph/fonts/ubuntu/ubuntu-regular.eot") format("embedded-opentype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/ubuntu/ubuntu-regular.woff") format("woff"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/ubuntu/ubuntu-regular.ttf") format("truetype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/ubuntu/ubuntu-regular.svg") format("svg"); }

                     @font-face{
                                     font-family: "opensanssemibold";
                                     src: url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/semibold/opensans-semibold-webfont.eot") format("embedded-opentype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/semibold/opensans-semibold-webfont.woff") format("woff"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/semibold/opensans-semibold-webfont.ttf") format("truetype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/semibold/opensans-semibold-webfont.svg") format("svg"); }

                     @font-face{
                                     font-family: "opensansregular";
                                     src: url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/opensans-regular.eot") format("embedded-opentype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/opensans-regular.woff") format("woff"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/opensans-regular.ttf") format("truetype"),
                                     url("//s1.trrsf.com/fe/zaz-morph/fonts/opensans/opensans-regular.svg") format("svg"); }


                     body{
                                     margin: 0;
                     }

                     a {
                                     text-decoration:none;
                     }

                     .card-ads {
                         background-color: #FFFFFF;
                         float: left;
                         height: 260px;
                         position: relative;
                         text-align: center;
                         width: 120px;
                     }

                     .img-ads-terraads {
                         height: 72px;
                         width: 120px;
                     }

                     .ttl-card-ads {
                         color: #191917;
                         font-family: OpenSansSemiBold;
                         font-size: 14px;
                         line-height: 16px;
                         overflow: hidden;
                         padding: 2px 0 5px;
                         text-align: left;
                     }


                     .url-card-terraads {
                         color: #FF9900;
                         font-family: OpenSansSemiBold;
                         font-size: 11px;
                         line-height: 13px;
                         padding-bottom: 1px;
                         text-align: left;
                         word-wrap: break-word;
                     }

                     .lbl-card-ads-info {
                         font-size: 14px;
                     }
                     .lbl-card-ads-info, .lbl-card-ads-info-2, .lbl-card-terraads-nopic {
                         color: #65655D;
                         font-family: OpenSansRegular;
                         line-height:12px;
                         padding: 5px 0 0;
                         text-align: left;
                     }

                     .lbl-card-ads-info-2 {
                         font-size: 12px;
                     }

                     .lbl-cards-ads-price-2 {
                         font-size: 20px;
                         margin-top: -1px;
                     }

                     .lbl-cards-ads-price, .lbl-cards-ads-price-2 {
                         color: #FF7212;
                         font-family: AllerLight;
                         height: 37px;
                         letter-spacing: -1px;
                         margin-bottom: 9px;
                         overflow: hidden;
                         text-align: left;
                     }

                     .lbl-card-ads-rs {
                         font-size: 18px;
                     }

                     .img-card-ads {
                                     height: 91px;
                     }

                     .img-card-ads-brand {
                         bottom: 0;
                         float: right;
                         position: absolute;
                         right: 0;
                     }

                     .img-card-ads-brand-terraads {
                         height: 12px;
                         width: 59px;
                     }

                     img {
                         border: 0 none;
                     }

                     </style>

                     <div class="card-ads">
                     <a target="_blank" href="{P_LINK_URL}">
                                     <div class="img-card-ads"><img class="img-ads-terraads" src="{P_IMAGE_URL}" style="height: 72px;" border="0"></div>
                                     <div class="ttl-card-ads">{P_TITLE}</div>
                                     <div class="url-card-terraads">{P_LINK_TEXT}</div>
                                     <div class="lbl-card-ads-info-2">{P_DESCRIPTION}</div>
                     </a>

                     <a href="{P_CLICK_URL_UNESC}" target="_blank">
                     <img class="img-card-ads-brand img-card-ads-brand-terraads" border="0" alt="terraads" src="http://www.terra.com.br/ads2/adops/img/terraads_minilogo_transparent.png">
                     </a>
                     </div>';

        return  array(
                    'template_height'=>200,
                    'template_width'=>200,
                    'template_code'=>$code_template

                );

    }

    function buildHtmlTemplate($code_template,$aFields)
    {
        foreach($aFields as $key => $value){
            $code_template = str_replace('{'.strtoupper($key).'}', $value, $code_template);
        }
        return $code_template;
    }

}