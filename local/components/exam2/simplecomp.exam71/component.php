<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock")) {
	  ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	  return;
}

if(empty($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] == 36000000;
}

$arParams['PRODUCT_PROPERTY_CODE'] = trim($arParams['PRODUCT_PROPERTY_CODE']);

global $USER;

if($this->startResultCache(false , array($USER->GetGroups()))) {

    //в массив arClassifier соберем список элементов, а в массив arClassifierId - список идентификаторов
    $arClassifier = array();
    $arClassifierId = array();
    $arResult["COUNT"] = 0;

	  $arSelect = array (
		    "ID",
		    "IBLOCK_ID",
		    "NAME",
	  );

    //получим список активных элементов из инфоблока фирма-производитель
	  $arFilter = array (
		    "IBLOCK_ID" => $arParams["CLASSIFIER_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
		    "ACTIVE" => "Y"
	  );
	    
	  $rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);


	  while($arElement = $rsElements->GetNext()) {
		    $arClassifier[$arElement['ID']] = $arElement;
        $arClassifierId[] = $arElement['ID'];
	  }

    $arResult["COUNT"] = count($arClassifierId);


	  $arSelectProd = array (
        "ID",
        "IBLOCK_ID",
			  "IBLOCK_SECTION_ID",
			  "NAME",
			  "DETAIL_PAGE_URL"
    );

    //получим элементы из инфоблока продукция, у которых есть привязки к элементам инфоблока фирма-производитель
    $arFilterProd = array (
        "IBLOCK_ID" => $arParams["CAT_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
		    "PROPERTY_" . $arParams["PRODUCT_PROPERTY_CODE"] => $arClassifierId,
        "ACTIVE" => "Y"
    );

    $rsElements = CIBlockElement::GetList(array(), $arFilterProd, false,  false, $arSelectProd);

    while($arElement = $rsElements->GetNextElement()) {

	      $arField = $arElement->GetFields();
	      $arField["PROPERTY"] = $arElement->GetProperties();

	      foreach($arField["PROPERTY"]["FIRMA"]['VALUE'] as $val) {
	          $arClassifier[$val]['ELEMENTS'][$arField['ID']] = $arField;
	      }
	  }

	  $arResult['CLASSIFIER'] = $arClassifier;
	  $this->SetResultCacheKeys(array("COUNT"));
    $this->includeComponentTemplate();
} else {
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("COUNT_71") . $arResult["COUNT"]);