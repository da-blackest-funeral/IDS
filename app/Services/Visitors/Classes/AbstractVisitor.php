<?php

    namespace App\Services\Visitors\Classes;

    use App\Services\Visitors\Interfaces\Visitor;

    abstract class AbstractVisitor implements Visitor
    {
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
         * @return void
         */
        public function execute() {
            foreach ($this->visitItems as $visitItem => $value) {
                $method = $this->convertToMethod($visitItem);
                $this->$method();
            }

            return $this->final();
        }

        abstract protected function convertToMethod(string $name): string;

        abstract protected function final();
    }
