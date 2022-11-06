<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock")) {
	  ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	  return;
}


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if(!isset($arParams["PRODUCTS_IBLOCK_ID"]))
	  $arParams["PRODUCTS_IBLOCK_ID"] = 0;

if(!isset($arParams["NEWS_IBLOCK_ID"]))
	  $arParams["NEWS_IBLOCK_ID"] = 0;

global $USER;
if ($USER->IsAuthorized()) {
    $arButtons = CIBlock::GetPanelButtons($arParams["PRODUCTS_IBLOCK_ID"]);

    $this->AddIncludeAreaIcons(
        array(
            array(
                "ID" => "linklb",
                "TITLE" => GetMessage("IBLOCK_ADMIN"),
                "URL" => $arButtons["submenu"]["element_list"]["ACTION_URL"],
                "IN_PARAMS_MENU" => true,
            )
        )
    );
}

//код по заданию [ex2-81]
$cFilter = false;

if(isset($_REQUEST["F"]))
    $cFilter = true;
//конец части кода по заданию [ex2-81]


//код по заданию [ex2-107]
global $CACHE_MANAGER;
//конец части кода по заданию [ex2-107]


//код по заданию [ex2-60]
$arNavigation = CDBResult::GetNavParams();
//конец части кода по заданию [ex2-60]



if ($this->startResultCache(false, array($cFilter, $arNavigation), "/servicesIblock")) {    //передаём переменную cFilter по заданию [ex2-81] и переменную arNavigation по заданию [ex2-60], а также третий параметр-место хранения кеша по заданию [ex2-107]

    //код по заданию [ex2-107] - указываем отслеживать изменения в инфоблоке Услуги
    $CACHE_MANAGER->RegisterTag("iblock_id_3");
    //конец части кода по заданию [ex2-107]

	  $arNews = array();
	  $arNewsID = array();

	  $obNews = CIBlockElement::GetList(
		    array(),
		    array(
		        "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
		        "ACTIVE" => "Y"
		    ),
		    false,
		    array(                                                //передаём параметры постраничной навигации по заданию [ex2-60]
          "nPageSize" => $arParams["ELEMENTS_PER_PAGE"],
          "bShowAll" => true
        ),                               
		    array("ID", "NAME", "ACTIVE_FROM")
		);

    $arResult["NAV_STRING"] = $obNews->GetPageNavString(GetMessage("PAGE_TITLE"));      //получаем вёрстку полученной постраничной навигации по заданию [ex2-60]

		while($newsElements = $obNews->Fetch()) {
		    $arNewsID[] = $newsElements["ID"];
		    $arNews[$newsElements["ID"]] = $newsElements;
		}

		$arSections = array();
		$arSectionsID = array();

		$obSection = CIBlockSection::GetList(
			  array(),
			  array(
				    "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
				    "ACTIVE",
				    $arParams["PRODUCTS_IBLOCK_ID_PROPERTY"] => $arNewsID
			  ),
			  false,
			  array("NAME", "IBLOCK_ID", "ID", $arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]),
			  false,
		);

		while($arSectionCatalog = $obSection->Fetch()) {
		    $arSectionsID[] = $arSectionCatalog["ID"];
		    $arSections[$arSectionCatalog["ID"]] = $arSectionCatalog;
		}


    $arFilterElements = array(
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE" => "Y",
        "SECTION_ID" => $arSectionsID
    );


    //добавляем дополнительное условие в фильтр по заданию [ex2-81]
    if($cFilter) {
        $arFilterElements[] = array(
            array("<=PROPERTY_PRICE" => 1700, "PROPERTY_MATERIAL" => "Дерево, ткань"),
            array("<PROPERTY_PRICE" => 1500, "PROPERTY_MATERIAL" => "Металл, пластик"),
            "LOGIC" => "OR"
        );
        $this->abortResultCache();
    }

		$obProduct = CIBlockElement::GetList(
			  array(
            "NAME" => 'asc',           //добавляем сортировку по заданию [ex2-81]
            "SORT" => "asc",
        ),
			  $arFilterElements,
			  false,
			  false,
			  array( 
            "NAME",
			      "IBLOCK_SECTION_ID",
			      "ID",
			      "IBLOCK_ID", 
			      "PROPERTY_ARTNUMBER", 
			      "PROPERTY_MATERIAL", 
			      "PROPERTY_PRICE",
            "CODE"
        )
		);

    $obProduct->SetUrlTemplates($arParams["TEMPLATE_LINK_DETAIL"]);   //добавляем ссылку на детальную страницу по заданию [ex2-81]

		$arResult["PRODUCT_CNT"] = 0;
		while($arProduct = $obProduct->GetNext()) {


        // Добавляем Эрмитаж - ниже код по заданию [ex2-58]
        $arButtons = CIBlock::GetPanelButtons(
            $arParams["PRODUCTS_IBLOCK_ID"],
            $arProduct["ID"],
            0,
            array("SECTION_BUTTONS" => false, "SESSID" => false)
        );
    
        $arProduct["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arProduct["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
    
        $arResult["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
        $arResult["IBLOCK_ID"] = $arParams["PRODUCTS_IBLOCK_ID"];
        // конец кода по заданию [ex2-58]

        
		    $arResult["PRODUCT_CNT"]++;
			  foreach($arSections[$arProduct["IBLOCK_SECTION_ID"]][$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $newsId) {
			      if (isset($arNews[$newsId]))
				        $arNews[$newsId]["PRODUCTS"][] = $arProduct;
			  }
		}

	  foreach($arSections as $arSection) {
	
		    foreach($arSection[$arParams["PRODUCTS_IBLOCK_ID_PROPERTY"]] as $newId) {
          if (isset($arNews[$newsId]))
			      $arNews[$newId]["SECTIONS"][] = $arSection["NAME"];
		    }
	  }
    $arResult["NEWS"] = $arNews;
	$this->SetResultCacheKeys(array("PRODUCT_CNT"));
    $this->includeComponentTemplate();

} else {
		$this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("NUMBER_OF_ELEMENTS") . $arResult["PRODUCT_CNT"]);