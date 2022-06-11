<?php

    namespace App\Services\Repositories\Interfaces;

    use App\Services\Commands\Interfaces\Command;

    interface CommandRepository
    {
        /**
         * @param array<Command> $commands
         * @return CommandRepository
         */
        public function setCommands(array $commands): CommandRepository;

        /**
         * @return CommandRepository
         */
        public function execute(): CommandRepository;

        /**
         * @return mixed
         */
        public function result();

        /**
         * @param Command $command
         * @return CommandRepository
         */
        public function addCommand(Command $command): CommandRepository;

        /**
         * @return CommandRepository
         */
        public function commands(): CommandRepository;
    }
