<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<? if (count($arResult["AUTHORS"]) > 0): ?>
    <ul>
		<? 
			//перебираем всех авторов
			foreach ($arResult["AUTHORS"] as $key => $arAuthor): 
				if($arAuthor["NEWS"]):
			?>
            <li>
                [<?=$key;?>] <?=$arAuthor["LOGIN"];?>
				<? if (count($arAuthor["NEWS"]) > 0): ?>
                    <ul>
						<? //у каждого автора перебираем новости
							foreach ($arAuthor["NEWS"] as $arNews): ?>
                            <li>
                                - <?=$arNews["NAME"];?>
                            </li>
                        <? endforeach ?>
                    </ul>
                <? endif ?>
            </li>
			<?endif?>
        <? endforeach ?>
    </ul>
<? endif ?>
