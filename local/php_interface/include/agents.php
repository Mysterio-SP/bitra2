<?
    function CheckUserCount() {
        $date = new DateTime();
        // Приводим дату к необходимому формату
        $date = \Bitrix\Main\Type\DateTime::createFromTimestamp($date->getTimestamp());
        // Получаем из системы записанное ранее свойство
        $lastDate = COption::GetOptionString("main", "last_date_agent_checkUserCount");

        // Если свойство не пустое формируем фильтр
        if ($lastDate) {
            $arFilter =array("DATE_REGISTER_1" => $lastDate);
        } else {
            $arFilter = array();
        }

        $arUsers = array();

        // Получаем список зарегистрированых пользователей по фильтру
        $by = "DATE_REGISTER";
        $order = "ASC";
        $rsUser = CUser::GetList(
            $by,
            $order,
            $arFilter
        );

        while ($user = $rsUser->Fetch()) {
            $arUsers[] = $user;
        }

        if (!$lastDate) {
            $lastDate = $arUsers[0]["DATE_REGISTER"];
        }

        // Получаем разницу в секундах между текущей датой и датой последнего запуска функции
        $difference = intval(abs(strtotime($lastDate) - strtotime($date->toString())));
        
        // Преобразуем секунды в дни
        $days = round($difference / (3600 * 24));
        
        // Получаем колличество пользователей
        $countUsers = count($arUsers);
    
        // Получаем всех администраторов
        $by = "ID";
        $order = "ASC";
        $rsAdmin = CUser::GetList(
            $by,
            $order,
            array("GROUPS_ID" => 1)
        );

        while ($admin = $rsAdmin->Fetch()) {
            // Отправляяем письмо администратору
            CEvent::Send(
                "COUNT_REGISTERED_USERS",
                "s1",
                array(
                    "EMAIL_TO" => $admin["EMAIL"],
                    "USERS" => $countUsers,
                    "DAYS" => $days,
                ),
            );
        }

        // Записываем в систему обновленное значение переменной
        COption::SetOptionString("main", "last_date_agent_checkUserCount", $date->toString());
        return "CheckUserCount();";
}