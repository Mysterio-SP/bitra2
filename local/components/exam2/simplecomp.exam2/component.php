<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if (empty($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

global $USER;

if ($USER->IsAuthorized()) {
	$arResult["COUNT"] = 0;
	$currentUserId = $USER->GetID();
	$currentUserType = Cuser::GetList(
     	($by="id"),
        ($order="asc"),
        array("ID" => $currentUserId),
        array("SELECT" => array($arParams["PROPERTY_UF"]))
    )->Fetch()[$arParams["PROPERTY_UF"]];


	    if ($this->StartResultCache(false , array($currentUserType, $currentUserId))) {

				$arOrderUser = array("id");
				$sortOrder = "asc";
				$arFilterUser = array("ACTIVE" => "Y");

				$arResult["USERS"] = array();
				$rsUsers = CUser::GetList(
            		($by = "id"),
            		($order = "asc"),
            		array($arParams["PROPERTY_UF"] => $currentUserType),
            		array("SELECT" => array("LOGIN", "ID"))
        		);

        		while($arUser = $rsUsers->Fetch()) {
            		$userList[$arUser["ID"]] = array("LOGIN" => $arUser["LOGIN"]);
            		$userListId[] = $arUser["ID"];
        		}

       			$arNewsAuthor = array();
        		$arNewsList = array();
        		$rsElements = CIBlockElement::GetList(
            		array(),
            		array(
                		"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
                		"PROPERTY_".$arParams["EXAM2_PROPERTY"] => $userListId,
            		),
            		false,
            		false,
            		array(
                		"NAME",
                		"ACTIVE_FROM",
                		"ID",
                		"IBLOCK_ID",
                		"PROPERTY_".$arParams["EXAM2_PROPERTY"]
            		)
        		);

        		$arNewsId = array();
        		while($arElement = $rsElements->Fetch()) {
            		$arNewsAuthor[$arElement["ID"]][] = $arElement["PROPERTY_".$arParams["EXAM2_PROPERTY"]."_VALUE"];

            		if (empty($arNewsList[$arElement["ID"]])) {
                		$arNewsList[$arElement["ID"]] = $arElement;
            		}

            		if ($arElement["PROPERTY_".$arParams["EXAM2_PROPERTY"]."_VALUE"] != $currentUserId) {
                		$arNewsList[$arElement["ID"]]["AUTHORS"][] = $arElement["PROPERTY_".$arParams["EXAM2_PROPERTY"]."_VALUE"];
            		}
        		}

        		foreach ($arNewsList as $key => $value) {

            		if (in_array($currentUserId, $arNewsAuthor[$value["ID"]]))
               		continue;

            		foreach ($value["AUTHORS"] as $authorId) {
                		$userList[$authorId]["NEWS"][] = $value;
                		$arNewsId[$value["ID"]] = $value["ID"];
            		}
        		}

        		unset($userList[$currentUserId]);

        		$arResult["AUTHORS"] = $userList;
        		$arResult["COUNT"] = count($arNewsId);
        		$this->SetResultCacheKeys(array("COUNT"));
        		$this->includeComponentTemplate();

    	} else {
        	$this->abortResultCache();
    	}

   		$APPLICATION->SetTitle(GetMessage("COUNT").$arResult["COUNT"]);


}

