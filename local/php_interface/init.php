<?
IncludeModuleLangFile(__FILE__);

AddEventHandler("main", "OnBeforeEventAdd", array("MyClass", "OnBeforeFeedbackHandler"));
AddEventHandler("main", "OnBuildGlobalMenu", array("MyClass", "MenuBuilder"));

class MyClass
{
    function OnBeforeFeedbackHandler(&$event, &$lid, &$arFields)
    {
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

	function MenuBuilder (&$aGlobalMenu, &$aModuleMenu)
	{
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
}
?>