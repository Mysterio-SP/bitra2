<?
if(isset($arResult["DATE_FIRST_NEWS"])) {                                       //если в arResult есть ключ DATE_FIRST_NEWS
	  $APPLICATION->SetPageProperty("specialdate", $arResult["DATE_FIRST_NEWS"]);      //то устанавливаем значение свойства specialdate для данной страницы
}
