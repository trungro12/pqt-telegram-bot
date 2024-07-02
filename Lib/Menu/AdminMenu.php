<?php

namespace PQT_TELEGRAM_BOT\Lib\Menu;

use PQT_TELEGRAM_BOT\Lib\Service\APIService;
use PQT_TELEGRAM_BOT\Lib\Service\BotService;
use PQT_TELEGRAM_BOT\Lib\Service\OptionService;

class AdminMenu
{
    public static function adminInit()
    {
        if (!is_admin()) return;
        self::initMenu();
    }


    private static function initMenu()
    {
        add_action("admin_menu", function () {
            add_options_page(PQT_TELEGRAM_BOT_NAME, PQT_TELEGRAM_BOT_NAME, 'administrator', 'pqt-telegram-bot-admin', function () {
                $option = OptionService::create();

                if (isset($_POST['submit']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], "update")) {
                    // check again, for sure
                    if (!current_user_can("administrator")) {
                        exit(__("You do not have permission to access this request!"));
                    }
                    // don't worry, data will sanitize when save to DB
                    if (empty($_POST['apiKey'])) {
                        $_POST['apiKey'] = hash('sha256', PQT_TELEGRAM_BOT_NAME);
                    }
                    $option->updateByData($_POST);
                    if (empty($option->chatID) && $option->botToken) {
                        $bot = new BotService($option->botToken);
                        $chatID = $bot->getChatID();
                        if ($chatID) {
                            $option->chatID = $chatID;
                            $option->update();
                        }
                    }
                }
?>
                <div class="wrap">
                    <h1><?php _e(PQT_TELEGRAM_BOT_NAME . " Settings") ?></h1>
                    <p>To Create a bot, you can following Step Below or <a href="https://core.telegram.org/bots/tutorial" rel="nofollow noreferrer noopener">See Docs</a></p>
                    <ol>
                        <li>Send message <code>/newbot</code> to <a rel="nofollow noreferrer noopener" target="_blank" href="https://t.me/botfather">@BotFather</a></li>
                        <li>Enter <b>Name</b> for Bot: The name of your bot is displayed in contact details and elsewhere.</li>
                        <li>Enter <b>username</b> for Bot</li>
                        <li>BotFather will return <b>Token</b> and Link Chat of Your Bot</li>
                        <li>Go to <b>Your Bot</b> and Send any Message (it will create chat ID)</li>
                        <li style="color: red;"><b>Final</b>, Insert your bot Token to option Below and Click Submit</li>
                    </ol>

                    <h3>
                        Your API:
                    </h3>
                    <p>
                        <code>
                            curl '<?php echo APIService::$apiUrl; ?>' \<br>
                            -H 'apiKey: <?php echo $option->apiKey; ?>' \<br>
                            --data-raw 'message=<?php echo urlencode("Hello World"); ?>'
                        </code>
                    </p>


                    <form action="" method="post">
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="chatID">Chat ID</label></th>
                                    <td>
                                        <input placeholder="12345" name="chatID" type="text" id="chatID" value="<?php echo sanitize_text_field($option->chatID); ?>" class="regular-text code">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="botToken">Bot Token</label></th>
                                    <td>
                                        <input required placeholder="12345:AAbced" name="botToken" type="text" id="botToken" value="<?php echo sanitize_text_field($option->botToken); ?>" class="regular-text code">
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="apiKey">API Key (Optional)</label></th>
                                    <td>
                                        <input placeholder="123123" name="apiKey" type="text" id="apiKey" value="<?php echo sanitize_text_field($option->apiKey); ?>" class="regular-text code">
                                        <p class="description">if it blank, default API KEY will created</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="botMessage">Message</label></th>
                                    <td>
                                        <input placeholder="Hello World" type="text" id="botMessage" value="" class="regular-text code">
                                        <button type="button" class="button button-info" id="botSendMessage">Send Message (Test)</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php wp_nonce_field("update") ?>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <script>
                    (function($) {
                        $("#botSendMessage").click(function() {
                            const message = $("#botMessage").val();
                            if (!message || message === "") {
                                alert("Empty Message");
                                return;
                            }

                            $.ajax({
                                url: `<?php echo APIService::$apiUrl; ?>`,
                                method: 'POST',
                                headers: {
                                    apiKey: `<?php echo $option->apiKey; ?>`
                                },
                                data: {
                                    message: message
                                },
                                success: function(res) {
                                    if (res.success) {
                                        alert("Done!");
                                    } else {
                                        alert(res.data.message);
                                    }

                                },
                            });

                        });
                    })(jQuery);
                </script>
<?php
            });
        });
    }
}
