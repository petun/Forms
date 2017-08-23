<?php

namespace Petun\Forms\Actions;

use Petun\Forms\BaseForm;


/**
 * This is only sample example for AmoCrmAction.
 * This class need package :"dotzero/amocrm": "^0.3.7"
 *
 * Class AmoCrmAction
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
class AmoCrmAction extends BaseAction
{

    public $hash;

    public $login;

    public $domain;

    private $client;

    public function __construct(BaseForm $form)
    {
        parent::__construct($form);
        $this->hash = 'hash';
        $this->login = 'example@user.com';
        $this->domain = 'domain';

        $this->client = new \AmoCRM\Client($this->domain, $this->login, $this->hash);
    }

    function run()
    {
        // Получение экземпляра модели для работы с аккаунтом
        $account = $this->client->account;

        // Вывод информации об аккаунте
        $apiCurrent = $account->apiCurrent();

        // Получение экземпляра модели для работы с контактами
        $lead = $this->client->lead;

        // Заполнение полей модели
        $name = $this->_form->fieldValue('name') ? 'Новая сделка (' . $this->_form->fieldValue('name') . ')' : 'Новая сделка с сайта ' . time();
        $lead['name'] = $name;

        //email
//        $email = $this->_form->fieldValue('email');
//        if ($email) {
//            $lead->addCustomField(454734, $email, 'WORK');
//        }
//
//        //telephone
//        $telephone = $this->_form->fieldValue('telephone');
//        if ($telephone) {
//            $lead->addCustomField(454732, $telephone, 'WORK');
//        }
//
//        //sitename
//        $site = $this->_form->fieldValue('sitename');
//        if ($site) {
//            $lead->addCustomField(589182, $site);
//        }


        //description
        $description = '';
        foreach ($this->_form->fieldValues() as $name => $value) {
            $description .= sprintf('%s: %s' . "\n", $name, $value);
        }
        $lead->addCustomField(589270, $description);


        $lead['responsible_user_id'] = 239914;
        $tags = array('форма с сайта');
        if ($this->_form->fieldValue('formName')) {
            $tags = array($this->_form->fieldValue('formName'));
        }
        $lead['tags'] = $tags;

        // Добавление нового контакта и получение его ID
        $leadId = $lead->apiAdd();

//        if ($contactId) {
//            $task = $this->client->task;
//            $task['text'] = 'Обработать новый контакт';
//            $task['responsible_user_id'] = 239914;
//            $task['complete_till'] = '+7 DAYS';
//            $task['element_id'] = $contactId;
//            $task['element_type'] = 1;
//
//            $taskId = $task->apiAdd();
//        }
    }
}