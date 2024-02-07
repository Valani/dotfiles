<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

if(isset($_REQUEST['changeLang'])){

}

$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(strpos($actual_link,'http://www.nawiteh.com.ua') !== false){
    header("Location: https://nawiteh.com.ua",TRUE,301);
    exit();
}
if(strpos($actual_link,'http://nawiteh.com.ua') !== false){
    header("Location: https://nawiteh.com.ua",TRUE,301);
    exit();
}

include '404to410.php';

$url = $_SERVER['REQUEST_URI'];

if(strpos($url,'/ru/') !== false || strpos($url,'/ru') !== false){
    header("Location: ".str_replace('/ru','/uk',$url),TRUE,301);
    exit();
}

if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
    header('Location: http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's':'').'://' . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);
    exit;
}

if($url == '/uk/' || $url == '/uk'){
    header("Location: https://nawiteh.com.ua/",TRUE,301);
    exit();
}

/**
 * @return mixed
 */
function getUserAgent()
{
    $agent = $_SERVER['HTTP_USER_AGENT'];

    return $agent;
}

/**
 * @return bool
 */
function checkIsBot()
{
    $agent = getUserAgent();
    $agents = array(
        "googlebot",
        "Chrome-Lighthouse",
        "Lighthouse",
        "bingbot",
        "yandex",
        "baiduspider",
        "facebookexternalhit",
        "twitterbot",
        "rogerbot",
        "linkedinbot",
        "embedly",
        "quora\ link\ preview",
        "showyoubot",
        "outbrain",
        "pinterest\/0\.",
        "pinterestbot",
        "slackbot",
        "vkShare",
        "W3C_Validator",
        "whatsapp",
        "Screaming Frog SEO"
    );

    foreach ($agents as $ag) {
        if (strpos($agent, $ag) !== false || strpos(strtolower($agent), $ag) !== false) return true;
    }

    return false;
}

ob_start();
require dirname(__FILE__).'/config/config.inc.php';
Dispatcher::getInstance()->dispatch();
$out = ob_get_contents();
ob_end_clean();

$out = str_replace('http:','https:',$out);
$out = str_replace('<head>','<head><link rel="canonical" href="https://nawiteh.com.ua'.$_SERVER['REQUEST_URI'].'"/>',$out);
$content_clean = str_replace('.png','.webp',$out);
$content_clean = str_replace('.jpg','.webp',$content_clean);
$content_clean = str_replace('.jpeg','.webp',$content_clean);
$content_clean = str_replace('.PNG','.webp',$content_clean);
$content_clean = str_replace('.JPG','.webp',$content_clean);

//auto alts

//if(isset($_REQUEST['test'])){
    preg_match_all('/<img [^>]+>/', $content_clean, $matches);

    $replace_arr = [];
    if(count($matches)){
        foreach($matches[0] as $item){
            if(strpos($item,'is_product_image') === false/* && strpos($item,'header-logo__logo') === false*/){
                $patterns = array("#(<img.*title=\")[^\"]*(\"[^>]*>)#", "#(<img.*alt=\")[^\"]*(\"[^>]*>)#");
                $replacements = array("\\1\\2", "\\1\\2");

                $item_2 = preg_replace($patterns, $replacements, $item);
                if(strpos($item,'header-logo__logo') !== false || strpos($item,'footer-logo') !== false){
                    if(Context::getContext()->language->id == 2){
                        $item_2 = str_replace('<img','<img alt="НАВІТЕХ - запчастини для JCB"',$item_2);
                    }else{
                        $item_2 = str_replace('<img','<img alt="NAWITEH - JCB Spare parts"',$item_2);
                    }
                }
                //$item_2 = str_replace('<img','<img alt="{auto_alt}"',$item_2);

                $replace_arr[] = [$item,$item_2];
            }
        }
    }

    if(count($replace_arr)){
        foreach($replace_arr as $rep){
            $content_clean = str_replace($rep[0],$rep[1],$content_clean);
        }
    }
//}
//$content_clean = str_replace('<img','<img alt="{auto_alt}"',$content_clean);
preg_match("/<title>(.+)<\/title>/siU", $content_clean, $matches);
$title = $matches[1];

$expPage = explode('{auto_alt}',$content_clean);
$content_clean = '';

$lang = Context::getContext()->language->id;
$lang_label = ($lang == 1)?'image':'зображення';

foreach($expPage as $key=>$it){
    if($key != (count($expPage) - 1)){
        $content_clean .= $it.$title.' - '.$lang_label.' {n_number}';
    }else{
        $content_clean .= $it;
    }
};

if(isset($_REQUEST['test'])){

}
$expPage33 = explode('{n_number}',$content_clean);
$content_clean = '';

foreach($expPage33 as $key=>$it){
    if($key != (count($expPage33) - 1)){
        $content_clean .= $it.($key+1);
    }else{
        $content_clean .= $it;
    }
}

//$content_clean = str_replace('/uk/','/',$content_clean);

//auto gen titles, descriptions, h1's
$pagesWhereAutoGEN = array(
    'category' => '/cat',
    'blog' => 'blog',
    'filters' => 's:',
    'product' => '/prod'
);

//lang hrefs
$url_without_prefix = str_replace('en/','',str_replace('uk/','',$_SERVER['REQUEST_URI']));
$url_without_prefix = explode('?',$url_without_prefix)[0];

$canonical = explode('?',$_SERVER['REQUEST_URI'])[0];

/*$out = str_replace('<head>','
    <head>
        <link rel="canonical" href="https://napoli.ua'.$canonical.'" />
        <link rel="alternate" hreflang="ru" href="https://napoli.ua'.$url_without_prefix.'" />
        <link rel="alternate" hreflang="uk" href="https://napoli.ua/uk'.$url_without_prefix.'" />
', $out);*/

$lang_id = Context::getContext()->language->id;

$is_category = ((isset($_GET['controller'])) && $_GET['controller'] == 'category')?true:false;

$match = false;
if(count($pagesWhereAutoGEN)) {
    $title_gen = '';
    $description_gen = '';
    $h1_gen = '';

    foreach($pagesWhereAutoGEN as $k=>$item){
        if(strpos($url_without_prefix,$item) !== false || (!$match && $is_category) || Context::getContext()->controller->php_self == 'product'){
            if(Context::getContext()->controller->php_self == 'product'){
                $k = 'product';
            }
            $match = true;
            if($is_category && $k != 'filters' && $k != 'blog'){
                $k = 'category';
            }

            switch($k){
                case 'category':
                    $page = (isset($_GET['page']))?$_GET['page']:1;
                    $get_category = Db::getInstance()->getRow("SELECT * FROM `ps_category_lang` WHERE `id_category` = '".$_GET['id_category']."' AND id_lang=".$lang_id);

                    $page_str = '';
                    if($page > 1){
                        if($lang_id == 2){
                            $page_str = 'Сторінка '.$page.' | ';
                        }else{
                            $page_str = 'Page '.$page.' | ';
                        }
                    }

                    if($get_category){
                        if($lang_id == 2){
                            $title_gen = $page_str.$get_category['name'].' для спецтехніки JCB - Оригінали та якісні заміни | Nawiteh';
                            $description_gen = 'Купуйте надійні '.$get_category['name'].' для всіх моделей спецтехніки JCB. Широкий асортимент, гарантія якості. Доставка по Україні. Оптимальні ціни та професійний підхід!';
                            $h1_gen = $page_str.$get_category['name'].' для спецтехніки JCB';
                        }else{
                            $title_gen = $page_str.$get_category['name'].' for JCB - Originals and Quality Replacements | Nawiteh';
                            $description_gen = 'Buy reliable '.$get_category['name'].' for all models of JCB heavy machinery. Wide assortment, quality guarantee. Delivery across Ukraine. Competitive prices and a professional approach!';
                            $h1_gen = $page_str.$get_category['name'].' for JCB';
                        }
                    }
                    break;
                case 'product':
                    if(!isset($_GET['id_category'])){
                        $title_gen = '';
                        $description_gen = '';
                        $h1_gen = '';


                        $get_product = Db::getInstance()->getRow("SELECT * FROM `ps_product_lang` WHERE `id_product` = '".$_GET['id_product']."' AND id_lang=".$lang_id);
                        if($get_product){
                            if(!$get_product['name']){
                                if($lang_id == 1){
                                    $get_product = Db::getInstance()->getRow("SELECT * FROM `ps_product_lang` WHERE `id_product` = '".$_GET['id_product']."' AND id_lang=1");
                                }else{
                                    $get_product = Db::getInstance()->getRow("SELECT * FROM `ps_product_lang` WHERE `id_product` = '".$_GET['id_product']."' AND id_lang=2");
                                }
                            }
                        }
                        if($get_product){
                            $get_prod_price = Db::getInstance()->getRow("SELECT * FROM `ps_product` WHERE `id_product` = ".$_GET['id_product']);
                            if($get_prod_price){
                                $price = (int)ceil($get_prod_price['price']);
                                $product_name = $get_product['name'];

                                if(strpos($out,": CAT") !== false){
                                    if($lang_id == 2){
                                        $h1_gen = $product_name.' '.$get_prod_price['reference'];
                                        $title_gen = $get_prod_price['reference'].' '.$product_name.' | Nawiteh';
                                        $description_gen = 'Купити '.$get_prod_price['reference'].' '.$product_name.' ✓ Кращі ціни ✓ Гарантія якості ✓  Доставка по Львову та всій Україні Тел. для уточнення інфо +38 (097) 100 00 11';
                                    }else{
                                        $h1_gen = $product_name.' '.$get_prod_price['reference'];
                                        $title_gen = $get_prod_price['reference'].' '.$product_name.' | Nawiteh';
                                        $description_gen = 'Buy '.$get_prod_price['reference'].' '.$product_name.' ✓ Best Prices ✓ Quality Guarantee ✓ Delivery in Lviv and all over Ukraine. Call for details +38 (097) 100 00 11';
                                    }
                                }else{
                                    if($lang_id == 2){
                                        $h1_gen = $product_name.' '.$get_prod_price['reference'].' для JCB';
                                        $title_gen = $get_prod_price['reference'].' '.$product_name.' для JCB | Nawiteh';
                                        $description_gen = 'Купити '.$get_prod_price['reference'].' '.$product_name.' для JCB ✓ Кращі ціни ✓ Гарантія якості ✓  Доставка по Львову та всій Україні Тел. для уточнення інфо +38 (097) 100 00 11';
                                    }else{
                                        $h1_gen = $product_name.' '.$get_prod_price['reference'].' for JCB';
                                        $title_gen = $get_prod_price['reference'].' '.$product_name.' for JCB  | Nawiteh';
                                        $description_gen = 'Buy '.$get_prod_price['reference'].' '.$product_name.' for JCB ✓ Best Prices ✓ Quality Guarantee ✓ Delivery in Lviv and all over Ukraine. Call for details +38 (097) 100 00 11';
                                    }
                                }
                            }
                        }
                    }
                    break;
            }
        }
    }

    if($title_gen && $description_gen){
        $content_clean = preg_replace(array('/<title>(.*)<\/title>/i', '/<meta name="description" content="(.*)"\/>/i'), array('<title>' . $title_gen . '</title>', '<meta name="description" content="' . htmlspecialchars($description_gen) . '">'), $content_clean);

        $ex1 = explode('<meta name="description" content="',$content_clean);
        $fpart = $ex1[0];
        $ex2 = explode('">',$ex1[1]);
        $spart = '';
        foreach($ex2 as $k=>$it){
            if($k > 0 && $k != (count($ex2) - 1)){
                $spart .= $it.'">';
            }elseif($k == (count($ex2) - 1)){
                $spart .= $it;
            }
        }

        $content_clean = $fpart.'<meta name="description" content="' . htmlspecialchars($description_gen) . '">'.$spart;
    }
    if($h1_gen){
        $content_clean = preg_replace(array('/<h1([^>]*)>(.*)<\/h1>/i'), array('<h1>'.$h1_gen.'</h1>'), $content_clean);
    }
}

$content_clean = str_replace('"A"','"/img/p/uk-default-home_default.webp"',$content_clean);



$noindex_rules = [
    '/login',
    '?',
    '/new-product',
    '/best-sales',
    '/my-account',
    '/2-vsi-tovari?',
    '/11-zapchastini?'
];

$set_noindex = false;
foreach($noindex_rules as $item){
    if(strpos($_SERVER['REQUEST_URI'],$item)){
        if(!isset($_REQUEST['page'])){
            $set_noindex = true;
        }else{
            if(strlen($item) > 2){
                $set_noindex = true;
            }
        }
    }
}
if($set_noindex){
        $content_clean = str_replace('<head>','
    <head>
        <meta name="robots" content="noindex">
', $content_clean);
}

$now_url = $_SERVER['REQUEST_URI'];
$getCustomSeo = Db::getInstance()->getRow('SELECT * FROM pr_ww_vp_customseo WHERE url="'.$now_url.'" ORDER BY id ASC');
if($getCustomSeo) {
    if(strpos($content_clean,'INDEX,FOLLOW') === false){
        $content_clean = str_replace('<head>','
    <head>
        <meta name="robots" content="INDEX,FOLLOW"/>
', $content_clean);

    }

    $title_seo = $getCustomSeo['title'];
    $description_seo = $getCustomSeo['description'];
    $h1_seo = $getCustomSeo['h1'];
    $seo_text_seo = $getCustomSeo['seo_text'];

    if ($title_seo) {
        $content_clean = preg_replace(array('/<title>(.*)<\/title>/i'), array('<title>' . $title_seo . '</title>'), $content_clean);
    }
    if ($description_seo) {
        $content_clean = preg_replace(array('/<meta name="description" content="(.*)">/i'), array('<meta name="description" content="' . $description_seo . '">'), $content_clean);
    }
    if ($h1_seo) {
        $content_clean = preg_replace(array('/<h1([^>]*)>(.*)<\/h1>/i'), array('<h1>' . $h1_seo . '</h1>'), $content_clean);
    }

    if ($seo_text_seo) {
        $content_clean = str_replace('{seo_text}', $seo_text_seo, $content_clean);
    }
}else{
    $content_clean = str_replace('{seo_text}', '', $content_clean);
}

$out = $content_clean;

if(strpos($out,'page-not-found__sub-title') !== false && $is_category == true){
    header("Location: ".explode("?",$_SERVER['REQUEST_URI'])[0],TRUE,301);
    exit();
}


$out = str_replace('src="https://nawiteh.com.ua/modules/hometiles/images/"','src="https://nawiteh.com.ua/modules/hometiles/images/b1ccdb70c1f3453afe35a29534797f514edbde5d_%D0%A1%D0%B2%D0%B5%D1%82%D0%BB%D1%8B%D0%B9%20%D0%A1%D0%B8%D0%BD%D0%B8%D0%B9%20%D0%B8%20%D0%91%D0%B5%D0%BB%D1%8B%D0%B9%20%D0%9F%D0%BE%D0%BB%D1%83%D0%B6%D0%B8%D1%80%D0%BD%D1%8B%D0%B9%20%D0%9F%D1%80%D0%BE%D1%81%D1%82%D0%BE%D0%B9%20%D0%9F%D1%80%D0%B5%D0%B7%D0%B5%D0%BD%D1%82%D0%B0%D1%86%D0%B8%D1%8F%20(5).webp"',$out);
echo $out;
