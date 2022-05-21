<?php

    namespace App\Services\Visitors\Classes;

    use App\Services\Commands\Interfaces\Command;
    use App\Services\Visitors\Interfaces\CommandComposite;

    abstract class AbstractCommandComposite implements CommandComposite
    {
        /**
         * @var array<Command>
         */
        protected array $commands = [];

        /**
         * @param array<Command> $commands
         * @return CommandComposite
         */
        public function setCommands(array $commands): CommandComposite {
            $this->commands = $commands;
            return $this;
        }

        /**
         * @param Command $command
         * @return CommandComposite
         */
        public function addCommand(Command $command): CommandComposite {
            $this->commands[] = $command;
            return $this;
        }

        /**
         * @return CommandComposite
         */
        public function execute(): CommandComposite {
            foreach ($this->commands as $command) {
                dump($command::class);
                $command->execute();
            }

            return $this;
        }
    }
