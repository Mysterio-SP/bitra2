<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	if($arItem['PROPERTIES']['EN_NAME']['VALUE'] != '') {
	?>
	<p class="news-item">
		<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif?>
		<?if($arItem["PROPERTIES"]["EN_NAME"]["VALUE"]):?>
			<b><?echo $arItem["PROPERTIES"]["EN_NAME"]["VALUE"]?></b><br />
		<?endif;?>
		<?if($arItem["PROPERTIES"]["EN_PREV"]["VALUE"]["TEXT"]):?>
			<?echo $arItem["PROPERTIES"]["EN_PREV"]["VALUE"]["TEXT"];?>
		<?endif;?>
	</p>
	<? } ?>
<?endforeach;?>

</div>
