<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(isset($arResult["MIN_PRICE"]) && isset($arResult["MAX_PRICE"])) {
	$html = '<div style="color:red; margin: 34px 15px 35px 15px">#TEXT#</div>';

	$sText = GetMessage("MIN_PRICE") . $arResult["MIN_PRICE"] . "</br>" . GetMessage("MAX_PRICE") . $arResult["MAX_PRICE"];

	$finaleText = str_replace("#TEXT#", $sText, $html);

	$APPLICATION->AddViewContent("prices", $finaleText);
}
