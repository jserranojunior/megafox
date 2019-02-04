<style>
    h3,h2{
        padding:0;
        margin:0;
    }
    .changelog{
        padding:10px;
    }
</style>
<?php
//
//$getThemeinfo = nl2br(file_get_contents("http://wpprofitbuilder.com/download/pbtheme/README.md"));
////$themeInfo = json_decode($getThemeinfo);
////echo "<pre>";
//echo "<h2>Theme Changelog</h2><div class='changelog'><p>";
//$getThemeinfo2 = explode("\r\n", $getThemeinfo);
//$html = null;
//foreach ($getThemeinfo2 as $info) {
//    $trim = trim($info);
//    if (!empty($trim)) {
//        if (preg_match('/#(.*)#/m', $info, $title)) {
//            $heading = "<h3>" . $title[1] . "</h3>";
//            $heading = str_replace("*", "", $heading);
//            $html .= str_replace("#", "", $heading);
//        } else {
//            $html .= $info . "\r\n";
//        }
//    }
//}
//echo $html;
//echo "</p></div>";
////echo $getThemeinfo;
//exit;
$getThemeinfo = file_get_contents("http://wpprofitbuilder.com/download/pbtheme/pbtheme.json");
$themeInfo = json_decode($getThemeinfo);
//echo "<pre>";
echo "<div class='changelog'><h3>Theme Changelog</h3><p>";
echo $themeInfo->sections->changelog;
echo "</p></div>";
//echo $getThemeinfo;
exit;
?>