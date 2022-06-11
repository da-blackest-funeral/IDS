<?php

    namespace App\Services\Repositories\Classes;

    use App\Services\Commands\Interfaces\Command;
    use App\Services\Repositories\Interfaces\CommandRepository;

    abstract class AbstractCommandRepository implements CommandRepository
    {
        /**
         * @var array<Command>
         */
        protected array $commands = [];

        /**
         * @param array<Command> $commands
         * @return CommandRepository
         */
        public function setCommands(array $commands): CommandRepository {
            $this->commands = $commands;
            return $this;
        }

        /**
         * @param Command $command
         * @return CommandRepository
         */
        public function addCommand(Command $command): CommandRepository {
            $this->commands[] = $command;
            return $this;
        }

        /**
         * @return CommandRepository
         */
        public function execute(): CommandRepository {
            foreach ($this->commands as $command) {
                $command->execute();
            }

            return $this;
        }
    }
