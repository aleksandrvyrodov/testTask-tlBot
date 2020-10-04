<?php

    include('vendor/autoload.php');

    use Telegram\Bot\Api;
    use App\Potato;
    use App\DbModel;

    $telegram = new Api('1346586971:AAHJ763tN-LyVEEnfw3Zqiz6v4imDG18M-E');
    $result = $telegram -> getWebhookUpdates();
    
    define("U_NAME", $result["message"]["from"]["first_name"]);
    define("U_ID", $result["message"]["chat"]["id"]);

    $text = $result["message"]["text"];
    $type = $result["message"]["entities"][0]["type"];
    $chat_id = U_ID;
    $name = U_NAME;

    $potato = new Potato($chat_id);
 
    $telegram->addCommands([
        App\Commands\StartCommand::class,
        App\Commands\HelpCommand::class
    ]);

    $update = $telegram->commandsHandler(true);

    if($type == "bot_command"){
        switch ($text) {
            case '/start':
                $reply_markup = $telegram->replyKeyboardHide();
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=>"Напишите что-нибудь)))", 'reply_markup' => $reply_markup]);            
            default:
                return true;
        }
    }

    $a_or_b = 'Z';
    $gch = $potato->getUserCh();
    
    switch ($text) {        
        case 'Гласные':
            $a_or_b = "A";

        case 'Согласные':
            $a_or_b = ($a_or_b == 'Z') ? 'B' : $a_or_b;            
            $md = $potato->checkMode($a_or_b);

            if($md == "Z-0"){
                $potato->message($text);
                $keyboard = [["Гласные"],["Согласные"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Отлично теперь можно вести подсчеты!', 'reply_markup' => $reply_markup]);
            }elseif($md == "AB"){
                $potato->changeMode($a_or_b);
                $keyboard = [["В последнем"],["В 10 последних"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Супер! Можем посчитать в 10 последних сообщениях или только в одном. Выбирай! ', 'reply_markup' => $reply_markup]);
            }elseif($md == "Z"){
                $keyboard = [["В последнем"],["В 10 последних"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Нет нет нет. Мы же уже решили считать '.strtolower($pttrn[$r_u['mode_name']]).'. Идем до конца! ', 'reply_markup' => $reply_markup]);
            }
            break;

        case 'В последнем':
            $a_or_b = $gch['mode_name'].'-1';

        case 'В 10 последних':
            $a_or_b = ($a_or_b == 'Z') ? $gch['mode_name'].'-10' : $a_or_b;
            $md = $potato->checkMode($a_or_b);     
            $pttrn = array(
                'B' => ["согласных", 🅱️], 
                'A' => ["гласных", 🅰️],
                10 => "последние 10 сообщений", 
                1 => "последнее сообщение" 
            );

            if($md == "Z-0"){
                $potato->message($text);
                $keyboard = [["Гласные"],["Согласные"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Отлично теперь можно вести подсчеты!', 'reply_markup' => $reply_markup]);
            }elseif($md == "Z"){
                $potato->message($text);
            }else{
                $r = $potato->happyEnd($a_or_b);
                $q = explode("-", $a_or_b);
                $r_s = $potato->smile((string)$r);

                $keyboard = [["Гласные"],["Согласные"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> "Статистика за {$pttrn[$q[1]]}.".PHP_EOL."Количество {$pttrn[$q[0]][0]} букв: $r.".PHP_EOL."{$pttrn[$q[0]][1]} - $r_s", 'reply_markup' => $reply_markup]);
            }
            break;

        default:
            $md = $potato->checkMode($a_or_b);
            $pttrn = array(
                'B' => "Согласные", 
                'A' => "Гласные" 
            );

            if($md == "Z-0"){
                $potato->message($text);
                $keyboard = [["Гласные"],["Согласные"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Отлично теперь можно вести подсчеты!', 'reply_markup' => $reply_markup]);
            }elseif($md == "Z"){
                $potato->message($text);
            }elseif($md == "X"){
                $keyboard = [["В последнем"],["В 10 последних"]];
                $reply_markup = $telegram->replyKeyboardMarkup([ 'keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true ]);
                $telegram->sendMessage([ 'chat_id' => $chat_id, 'text'=> 'Нет нет нет. Мы же уже решили считать '.strtolower($pttrn[$r_u['mode_name']]).'. Идем до конца! ', 'reply_markup' => $reply_markup]);
            }
            break; 
    }