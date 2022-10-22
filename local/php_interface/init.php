<?
IncludeModuleLangFile(__FILE__);

AddEventHandler("main", "OnBeforeEventAdd", array("MyClass", "OnBeforeFeedbackHandler"));
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
}
?>