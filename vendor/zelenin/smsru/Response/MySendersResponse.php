<?php

namespace Zelenin\SmsRu\Response;

class MySendersResponse extends AbstractResponse
{
    /**
     * @var array
     */
    public $phones = [];

    /**
     * @var array
     */
    protected $availableDescriptions = [
        '100' => 'Запрос выполнен. На второй и последующих строчках вы найдете ваших одобренных отправителей, которые можно использовать в параметре &from= метода sms/send.',
        '200' => 'Неправильный api_id.',
        '210' => 'Используется GET, где необходимо использовать POST.',
        '211' => 'Метод не найден.',
        '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
        '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
        '301' => 'Неправильный пароль, либо пользователь не найден.',
        '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).'
    ];
}
