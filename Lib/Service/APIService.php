<?php

namespace PQT_TELEGRAM_BOT\Lib\Service;

class APIService
{

    private static $isInit = false;
    static $apiUrl = "";
    static function init()
    {
        if (self::$isInit) return;
        self::$isInit = true;

        self::$apiUrl = get_rest_url(null, "pqt_telegram_bot/v1/sendMessage");

        add_action('rest_api_init', function () {
            register_rest_route('pqt_telegram_bot/v1', '/sendMessage', array(
                'methods' => 'POST',
                'callback' => function ($param) {
                    $option = OptionService::create();
                    $apiKeyReal = $option->apiKey;
                    $apiKey = $param->get_header('apiKey');
                    $message = $param->get_param('message');

                    if ($apiKey !== $apiKeyReal) {
                        wp_send_json_error([
                            "message" => "Wrong API KEY"
                        ]);
                        exit;
                    }

                    if (empty($message)) {
                        wp_send_json_error([
                            "message" => "Empty Message"
                        ]);
                        exit;
                    }
                    $bot = new BotService($option->botToken);
                    $data = $bot->sendMessage($message, $option->chatID);

                    if (empty($data) || $data['ok'] !== true) {
                        wp_send_json_error([
                            "message" => "Can not Send Message!",
                        ]);
                        exit;
                    }

                    wp_send_json_success([
                        "message" => "Done!"
                    ]);
                    exit;
                },
            ));
        });
    }
}
