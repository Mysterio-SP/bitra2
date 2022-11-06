<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент71");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp.exam71", 
	".default", 
	array(
		"CACHE_GROUPS" => "Y",
		"CACHE_NOTES" => "",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CAT_IBLOCK_ID" => "2",
		"CLASSIFIER_IBLOCK_ID" => "7",
		"LINK_TEMPLATE_DETAIL" => "products/#SECTION_ID#/#CODE#/",
		"PRODUCT_PROPERTY_CODE" => "FIRMA",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>