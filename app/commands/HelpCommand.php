<?php

namespace App\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected $name = "help";
    protected $description = "Command Help";
    public function handle($arguments)
    {        
        $this->replyWithMessage(['text' => 'Hello! this command Help']);
    }
 }