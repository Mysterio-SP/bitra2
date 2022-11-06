<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock")) {
	  ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	  return;
}

if (empty($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

global $USER;

if ($USER->IsAuthorized()) {

    //в дальнейшем будем записывать количество уникальных новостей как COUNT
	  $arResult["COUNT"] = 0;

    //получаем Id текущего пользователя
	  $currentUserId = $USER->GetID();

    $params = [];
    $params["SELECT"] = array($arParams["PROPERTY_AUTHOR_TYPE"]);

    //получаем профиль текущего пользователя
	  $currentUser = Cuser::GetList(
     	  '',
        '',
        array("ID" => $currentUserId),
        $params
    )->Fetch();


	  if ($this->StartResultCache(false , array($currentUser, $currentUserId))) {

        //получаем пользователей, у которых тип авторства совпадает с текущим пользователем
				$rsUsers = CUser::GetList(
            '',
            '',
            array($arParams["PROPERTY_AUTHOR_TYPE"] => $currentUser[$arParams["PROPERTY_AUTHOR_TYPE"]]),
            array("SELECT" => array("LOGIN", "ID", $arParams["PROPERTY_AUTHOR_TYPE"]))
        );

        while($arUser = $rsUsers->Fetch()) {
            $usersList[$arUser['ID']] = $arUser;
        }

        //получим список новостей
        $rsElements = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            ),
            false,
            false,
            array(
                "NAME",
                "ACTIVE_FROM",
                "ID",
                "IBLOCK_ID",
                "PROPERTY_".$arParams["AUTHOR_PROPERTY"]
            )
        );

        //в массив badIds будем записывать ID новостей в авторстве которых есть текущий пользователь
		    $badIds = [];

        while($arElement = $rsElements->Fetch()) {

			      $allNews[] = $arElement;

			      if($arElement["PROPERTY_AUTHOR_VALUE"] == $currentUser["ID"])
			          $badIds[] = $arElement["ID"];
        }

        //в массив arNewsId будем записвать ID уникальных новостей
		    $arNewsId = [];

		    foreach($allNews as $news) {

            //если у перебираемой новости ID совпадает с ID новостей авторства текущего пользователя, то пропускаем шаг
		        if(in_array($news["ID"], $badIds)) 
		            continue;

            //если в массиве usersList существует пользователь с ID, равным ID автора перебираемой новости
			      if($usersList[$news["PROPERTY_AUTHOR_VALUE"]]) {

                //то данному автору добавляем ещё один вложенный массив с ключем NEWS, который будет хранить его новости
				        $usersList[$news["PROPERTY_AUTHOR_VALUE"]]["NEWS"][] = $news;

                //также добавляем в массив с ID уникальных новостей перебираемую новость, если её там ещё нет
				        if(!in_array($news["ID"], $arNewsId)) 
				            $arNewsId[] = $news["ID"];
			      }
			  }

        $arResult["AUTHORS"] = $usersList;
        $arResult["COUNT"] = count($arNewsId);
        $this->SetResultCacheKeys(array("COUNT"));    //надо ли добавлять в кеш $arResult["AUTHORS"] ?
        $this->includeComponentTemplate();

    } else {
        $this->abortResultCache();
    }

   	$APPLICATION->SetTitle(GetMessage("COUNT").$arResult["COUNT"]);
}
