<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.03.18
 * Time: 14:17
 */

namespace serverMonitor;

use const Amp\Process\BIN_DIR;
use React\EventLoop\Factory;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\TgLog;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;

class serverMonitorController
{
    public function checkServerSpace(int $sLoad){
        chdir('/var/www/html/serverBot/');
        $flag_1 = file_get_contents('./config/status1.env');
        $flag_2 = file_get_contents('./config/status2.env');
        if ($flag_1 != '1' && $sLoad <= 15){
            file_put_contents('./config/status1.env', 1);
            $this->sendMessage(15);
        }

        if ($flag_2 != '1' && $sLoad <= 5){
            file_put_contents('./config/status2.env', '1');
            $this->sendMessage(5);
        }
    }

    public function sendMessage ($message) {
        chdir('/var/www/html/serverBot/');
        $tocken = file_get_contents('./config/tocken.env');
        $loop = Factory::create();
        $handler = new HttpClientRequestHandler($loop);
        $tgLog = new TgLog($tocken, $handler);

        $sendMessage = new SendMessage();
        $sendMessage->chat_id = '-264626362';
        $sendMessage->text = 'Altair memory - '. $message . '%';

        $tgLog->performApiRequest($sendMessage);
        $loop->run();
    }

    public function readServerSpace(){
        $data = exec('df -h --type=ext4 --output=pcent');
        $cleared = str_replace('%', '', trim(preg_replace('/\r\n|\r|\n/u', '', $data)));
        return $cleared;
    }

    public function updateStatusFiles (int $sLoad){
        chdir('/var/www/html/serverBot/');
        if ($sLoad > 15){
            file_put_contents('./config/status1.env', '0');
        }
        if ($sLoad > 5){
            file_put_contents('./config/status2.env', '0');
        }
    }
}