<?php
/**
 * Created by PhpStorm.
 * User: ignatenkovnikita
 * Date: 16/03/2017
 * Time: 20:17
 * Web Site: http://IgnatenkovNikita.ru
 */

namespace Petun\Forms\Actions;


class TelegramAction extends BaseAction
{
    /** @var  string */
    public $api_key;

    /** @var  array */
    public $receivers;

    /** @var \Smarty $_smarty */
    private $_smarty;

    public $subject;

    /*
	 * @var string - Smarty template name. Located in dir - smarty/templates. eg. default.tpl
	 */
    public $template = 'default_telegram.tpl';


    /**
     * @param \Petun\Forms\BaseForm $form
     */
    public function __construct(\Petun\Forms\BaseForm $form)
    {
        parent::__construct($form);

        // init smarty template engine
        $this->_smarty = new \Smarty();
        $baseDir = dirname(__FILE__) . '/../../smarty/';
        $this->_smarty->setTemplateDir($baseDir . 'templates');
        $this->_smarty->setCompileDir($baseDir . 'templates_c');
        $this->_smarty->setCacheDir($baseDir . 'cache');
        $this->_smarty->setConfigDir($baseDir . 'configs');
    }


    function run()
    {

        foreach ($this->receivers as $receiver) {
            $message = $this->_getBody();
            $this->sendMessage($receiver, $message, $this->api_key);
        }

        return true;
    }


    protected function sendMessage($chatID, $messaggio, $token)
    {

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
        $url = $url . "&text=" . urlencode($messaggio);
        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    private function _getBody()
    {
        $this->_smarty->assign('subject', $this->subject);
        $this->_smarty->assign('values', $this->_form->fieldValues());
        return $this->_smarty->fetch($this->template);
    }
}