<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---
</br>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<? 
if (count($arResult["NEWS"]) > 0) { ?>

	<ul>

	  <? foreach ($arResult["NEWS"] as $key => $arNews) { 
			 if(isset($arNews["NAME"])) {
?>

			<li>
				<b>
				 	<?=$arNews["NAME"];?>
				</b>

				 - <?=$arNews["ACTIVE_FROM"];?>
				   (<?=implode(", ", $arNews["SECTIONS"]);?>)
			</li>
			 <? 
			}
			if (count($arNews["PRODUCTS"]) > 0) { ?>

			<ul>
				<? foreach ($arNews["PRODUCTS"] as $arProduct) { ?>
				<li>
        				<?=$arProduct["NAME"];?> -
                        <?=$arProduct["PROPERTY_PRICE_VALUE"];?> -
                        <?=$arProduct["PROPERTY_MATERIAL_VALUE"];?> -
                        <?=$arProduct["PROPERTY_ARTNUMBER_VALUE"];?>
				</li>
				<? } ?>
			</ul>
			<? } ?>
	<? } ?>

	</ul>
<br>

<? } ?>