<?php

namespace App\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\DbModel;

class StartCommand extends Command
{
    protected $name = "start";
    protected $description = "Start Command to get you started";
    public function handle($arguments)
    {
        $this->replyWithMessage(['text' => 'Привет '.U_NAME.'!'.PHP_EOL.'Этот бот будет считать количество гласных или согласных букв. В последнем или 10 последних сообщения.'.PHP_EOL.'Приступим!']);
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $commands = $this->getTelegram()->getCommands();
        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }
        $this->replyWithMessage(['text' => $response]);        
        
        $a = new DbModel();
        $a -> addNewSession(U_ID);

        // $this->triggerCommand('subscribe');

    }
 }