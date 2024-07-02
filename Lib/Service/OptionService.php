<?php

namespace PQT_TELEGRAM_BOT\Lib\Service;


class OptionService
{

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * Chat ID of Bot
     * @var string
     */
    public $chatID;

    /**
     * Token of Bot
     * @var string
     */
    public $botToken;

    /**
     * Api Key use for your API
     * @var string
     */
    public $apiKey = "6PQT_TELEGRAM_BOT_KEY_DEFAULT2sddsd1234hhsd8846ff";


    public static function init()
    {
    }

    public static function create()
    {
        return !empty(self::$instance) ? self::$instance : new self();
    }

    public function __construct()
    {
        $arrOption = json_decode(trim(get_option(PQT_TELEGRAM_BOT_NAME . '_option')), true) ?? [];
        foreach ($arrOption as $key => $option) {
            $this->$key = $option;
        }
        self::$instance = $this;
    }

    public function update()
    {

        $arrOption = [
            'chatID' => sanitize_text_field($this->chatID),
            'botToken' => sanitize_text_field($this->botToken),
            'apiKey' => sanitize_text_field($this->apiKey),
        ];
        return update_option(PQT_TELEGRAM_BOT_NAME . '_option', json_encode($arrOption));
    }

    public function updateByData($arrData = [])
    {
        foreach ($arrData as $key => $data) {
            $this->$key = ($data);
        }
        return $this->update();
    }
}
