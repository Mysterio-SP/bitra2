<?
if(!empty($arParams["ID_IBLOCK_CANONICAL"])) {   //если есть свойство ID_IBLOCK_CANONICAL

    //то получим элемент инфоблока Canonical
	  $arSelect = array(
		    "ID",
		    "IBLOCK_ID", 
		    "NAME", 
		    "PROPERTY_NEWS"
    );

	  $arFilter = array(
		    "IBLOCK_ID"=> $arParams["ID_IBLOCK_CANONICAL"], 
		    "PROPERTY_NEWS"=>$arResult["ID"], 
		    "ACTIVE"=>"Y"
	  );

	  $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

    //если элемент был получен
	  if ($ob = $res->GetNextElement()) {
 		    $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();

        //то устанавливаем в качестве CANONICAL_LINK название элемента из ИБ Canonical
		    $arResult["CANONICAL_LINK"] = $arFields["NAME"];

        //и добавляем его в кеш
		    $this->__component->SetResultCacheKeys(array("CANONICAL_LINK"));
	  }
}