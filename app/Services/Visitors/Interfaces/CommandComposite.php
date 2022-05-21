<?php

    namespace App\Services\Visitors\Interfaces;

    use App\Services\Commands\Interfaces\Command;
    use App\Services\Visitors\Classes\AbstractCommandComposite;

    interface CommandComposite
    {
        /**
         * @param array<Command> $commands
         * @return CommandComposite
         */
        public function setCommands(array $commands): CommandComposite;

        /**
         * @return CommandComposite
         */
        public function execute(): CommandComposite;

        /**
         * @return mixed
         */
        public function result();

        /**
         * @param Command $command
         * @return CommandComposite
         */
        public function addCommand(Command $command): CommandComposite;

        /**
         * @return CommandComposite
         */
        public function commands(): CommandComposite;
    }
