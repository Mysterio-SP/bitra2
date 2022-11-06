<?
if($arParams["SPECIALDATE"] == "Y") {                                       //если установлено свойство страницы specialdate
	  $arResult["DATE_FIRST_NEWS"] = $arResult["ITEMS"][0]["ACTIVE_FROM"];    //то добавляем в arResult ключ DATE_FIRST_NEWS со значением даты первой новости
	  $this->__component->SetResultCacheKeys(array("DATE_FIRST_NEWS"));       //добавляем ключ DATE_FIRST_NEWS в кеш
}