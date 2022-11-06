<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"PRODUCTS_IBLOCK_ID_PROPERTY" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_PROPERTY_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
    //параметр по заданию [ex2-81]
    "TEMPLATE_LINK_DETAIL" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_TEMPLATE_LINK_DETAIL_81"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
      "DEFAULT" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#"
		),
    //параметр по заданию [ex2-60]
    "ELEMENTS_PER_PAGE" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_ELEMENTS_PER_PAGE_60"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
      "DEFAULT" => 2
		),
		"CACHE_TIME" => array("DEFAULT" => 36000000),
	),
);