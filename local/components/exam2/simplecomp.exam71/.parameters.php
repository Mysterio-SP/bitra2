<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"CAT_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"CLASSIFIER_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CLASS_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
		"LINK_TEMPLATE_DETAIL" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_LINK_TEMPLATE_DETAIL"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
    "PRODUCT_PROPERTY_CODE" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_PRODUCT_PROP_CODE"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
	),
);