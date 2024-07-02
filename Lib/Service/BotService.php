<?php

namespace PQT_TELEGRAM_BOT\Lib\Service;

class BotService
{

    private $botToken;

    function __construct($botToken)
    {
        $this->botToken = $botToken;
    }

    /**
     * @return int
     */
    function getChatID()
    {
        $arrData = $this->getUpdates();
        return $arrData['result'][0]['message']['chat']['id'] ?? 0;
    }

    function sendMessage($text, $chatID)
    {
        $text = urlencode($text);
        $botToken = $this->botToken;
        return $this->request("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatID&text=$text");
    }

    private function getUpdates()
    {
        return $this->request("https://api.telegram.org/bot$this->botToken/getUpdates");
    }

    /**
     * @return array|false
     */
    private function request($url)
    {
        $res = wp_remote_get($url);
        if (is_wp_error($res)) {
            return false;
        }
        return json_decode(wp_remote_retrieve_body($res), true);
    }
}
