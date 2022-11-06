<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---
</br>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<? 
//код по заданию [ex2-81]
$url = $APPLICATION->GetCurPage() . "?F=Y";
echo GetMessage("FILTER_TITLE") . "<a href='".$url."'>".$url."</a>" . "</br>";
//конец части кода по заданию [ex2-81]

//код по заданию [ex2-107]
echo GetMessage("TIMESTAMP");
echo time();
//конец части кода по заданию [ex2-107]

if (count($arResult["NEWS"]) > 0) { ?>
    <ul>
	      
        <? foreach ($arResult["NEWS"] as $key => $arNews) { 
			      if(isset($arNews["NAME"])) {?>

			          <li>
				            <b>
				 	              <?=$arNews["NAME"];?>
				            </b>
				            - <?=$arNews["ACTIVE_FROM"];?>
				            (<?=implode(", ", $arNews["SECTIONS"]);?>)
			          </li>
			      <?}
			      if (count($arNews["PRODUCTS"]) > 0) { ?>
                <?
                //добавляем функционал эрмитажа по заданию [ex2-58]
                $this->AddEditAction("add_element".$key, $arResult["ADD_LINK"], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_ADD"));
                ?>
                <ul id="<?=$this->GetEditAreaId("add_element".$key);?>">
                    <? foreach ($arNews["PRODUCTS"] as $arProduct) { ?>
                        <?  //добавляем функционал эрмитажа по заданию [ex2-58]
                            $this->AddEditAction($arNews["ID"]."_".$arProduct['ID'], $arProduct['EDIT_LINK'], CIBlock::GetArrayByID($arProduct["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($arNews["ID"]."_".$arProduct['ID'], $arProduct['DELETE_LINK'], CIBlock::GetArrayByID($arProduct["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <li id="<?=$this->GetEditAreaId($arNews["ID"]."_".$arProduct['ID']);?>">
                            <?=$arProduct["NAME"];?> -
                            <?=$arProduct["PROPERTY_PRICE_VALUE"];?> -
                            <?=$arProduct["PROPERTY_MATERIAL_VALUE"];?> -
                            <?=$arProduct["PROPERTY_ARTNUMBER_VALUE"];?>
                            (<?=$arProduct["DETAIL_PAGE_URL"];?>)          <?//добавляем ссылку на детальную страницу по заданию [ex2-81]?>
                        </li>
                    <?}?>
                </ul>
			      <?}?>
	      <?}?>
	  </ul>
<br>
---
    <p>
        <b>
            <?=GetMessage("NAVIGATION");?>
        </b>
    </p>
    <?php echo $arResult["NAV_STRING"];?>
<?}?>