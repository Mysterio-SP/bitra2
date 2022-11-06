<?
IncludeModuleLangFile(__FILE__);

AddEventHandler("main", "OnBeforeEventAdd", array("MyClass", "OnBeforeFeedbackHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("MyClass", "MenuBuilder"));

//обработчик события по заданию [Ex2-50]
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("MyClass", "OnBeforeIBlockElementUpdateHandler50"));

//обработчик события по заданию [Ex2-93]
AddEventHandler("main", "OnEpilog", array("MyClass", "NonExistingPageOpenedHandler"));

//обработчик события по заданию [Ex2-94]
AddEventHandler("main", "OnBeforeProlog", array("MyClass", "MyOnBeforePrologHandler94"));

class MyClass
{
    function OnBeforeFeedbackHandler(&$event, &$lid, &$arFields) {
		    if($event == "FEEDBACK_FORM") {
			      global $USER;
			      if($USER->isAuthorized()) {
				        $arFields["AUTHOR"] = GetMessage("AUTH_USER", array(
					          "#ID#" =>$USER->GetID(), 
					          "#LOGIN#" =>$USER->GetLogin(),
					          "#NAME#" => $USER->GetFullName(),
					          "#NAME_FORM#" => $arFields["AUTHOR"]
				        ));
			      } else {
				        $arFields["AUTHOR"] = GetMessage("AUTH_USER_NOT", array(
					              "#NAME_FORM#" => $arFields["AUTHOR"]
				        ));
            }
		    }

		    CEventLog::Add(array(
		        "SEVERITY" => "SECURITY",
            "AUDIT_TYPE_ID" => GetMessage("REPLACE") . $arFields["AUTHOR"],
            "MODULE_ID" => "main",
            "ITEM_ID" => $event,
            "DESCRIPTION" => GetMessage("REPLACE") . $arFields["AUTHOR"],
		    ));
    }


    function MenuBuilder (&$aGlobalMenu, &$aModuleMenu) {

        $isAdmin = false;
        $isManager = false;

        global $USER;
        $userGroup = CUSER::GetUserGroupList($USER->GetID());

        $contentGroupID = CGroup::GetList(
            $by = "c_sort",
            $order = "asc",
            array("STRING_ID" => "content_editor")
        )->Fetch()["ID"];

        while ($group = $userGroup->Fetch()) {

            if ($group["GROUP_ID"] == 1) {
                $isAdmin = true;
            }

            if ($group["GROUP_ID"] == $contentGroupID) {
                $isManager = true;
            }
        }

        if (!$isAdmin && $isManager) {

            foreach ($aModuleMenu as $key => $item) {

                if ($item["items_id"] == "menu_iblock_/news") {

                    $aModuleMenu = [$item];

                    foreach ($item["items"] as $childItem) {

                        if ($childItem["items_id"] == "menu_iblock_/news/1") {
                            $aModuleMenu[0]["items"] = [$childItem];
                            break;
                        }
                    }
                break;
                }
            }
            $aGlobalMenu  = ["global_menu_content" => $aGlobalMenu["global_menu_content"]];
        }
    }


  //метод по отмене деактивации элемента из инфоблока продукция в случае, если количество его просмотров больше 2
    function OnBeforeIBlockElementUpdateHandler50(&$arFields) {

			  //задаём константу равной ID инфоблока продукция
			  define("IBLOCK_PRODUCTS_ID", 2);

        //добавим константу, равную максимальному количеству просмотров по условию
        define("MAX_COUNT", 2);
			  
        //добавим условие - если будем менять элемент инфоблока продукция и если это деактивация элемента
		    if(IBLOCK_PRODUCTS_ID == $arFields['IBLOCK_ID'] && $arFields['ACTIVE'] == 'N') {

	          $arSelect = array("ID", "IBLOCK_ID", "NAME", "SHOW_COUNTER");

            //будем фильтровать по ID инфоблока и по ID элемента
            $arFilter = array(
                "IBLOCK_ID"=>IBLOCK_PRODUCTS_ID, 
                "ID"=>$arFields['ID'],
            );

            $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

            $arItem = $res->Fetch();

            //если колисевтпо просмотров больше просмотров по условию задачи, то отменяем редактирование и выводим ошибку
            if($arItem["SHOW_COUNTER"] > MAX_COUNT) {

                global $APPLICATION;
                $text = GetMessage("CANT_DEACTIVATE_PRODUCT", array("#count#" => $arItem["SHOW_COUNTER"]));
                $APPLICATION->throwException($text);
                return false;
            }
		    }
    }

    function NonExistingPageOpenedHandler () {
        if(defined("ERROR_404") && ERROR_404 == "Y") {
            global $APPLICATION;

            //сбросим буфер
            $APPLICATION->RestartBuffer();

            //подключим файлы для отображения страницы 404 если страница не найдена
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php";
            include $_SERVER["DOCUMENT_ROOT"] . "/404.php";
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php";

            //записываем в журнал событий
            CEventLog::Add(array(
              "SEVERITY" => "INFO",
              "AUDIT_TYPE_ID" => "ERROR_404",
              "MODULE_ID" => "main",
              "DESCRIPTION" => $APPLICATION->GetCurPage(),
           ));

        }
    }

    function MyOnBeforePrologHandler94 () {

        //создаём константу, равную идентификатору инфоблока Метатеги
        define("IBLOCK_METATAGS_ID", 6);
		global $APPLICATION;
        $curDir = $APPLICATION->GetCurDir();

        //подключаем модуль инфоблок
        if(CModule::IncludeModule("iblock")) {

            $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_title", "PROPERTY_description");

            $arFilter = array(
                "IBLOCK_ID"=>IBLOCK_METATAGS_ID, 
                "NAME"=>$curDir,
            );

            $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

          //если элементы по фильтру были найдены, то устанавливаем соответствующие свойства страницы
          if($arRes = $res->Fetch()) {
              $APPLICATION->SetPageProperty('title', $arRes['PROPERTY_TITLE_VALUE']);
              $APPLICATION->SetPageProperty('description', $arRes['PROPERTY_DESCRIPTION_VALUE']);
          }
        }
    }
}

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/agents.php"))
    include_once $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/agents.php";