<?php

    namespace App\Services\Visitors\Classes;

    use App\Models\Order;
    use App\Services\Commands\Interfaces\Command;
    use App\Services\Visitors\Interfaces\Visitor;

    abstract class AbstractVisitor implements Visitor
    {
        /**
         * @var array<Command>
         */
        protected array $commands = [];

        /**
         * @param array<string, string> $visitItems
         */
        public function __construct(protected array $visitItems) {
        }

        /**
         * @param array $visitItems
         * @return Visitor
         */
        public function setVisitable(array $visitItems): Visitor {
            $this->visitItems = $visitItems;
            return $this;
        }

        /**
         * @return Visitor
         */
        public function execute(): Visitor {
            foreach ($this->visitItems as $visitItem => $value) {
                $method = $this->convertToMethod($visitItem);
                $this->$method();
            }

            foreach ($this->commands as $command) {
                $command->execute();
            }

            return $this;
        }

        abstract protected function convertToMethod(string $name): string;
    }
