<?php

    namespace App\Services\Visitors\Classes;

    use App\Services\Visitors\Interfaces\Visitor;

    abstract class AbstractVisitor implements Visitor
    {
        public function __construct(protected array $visitItems) {
        }

        public function setVisitable(array $visitItems): Visitor {
            $this->visitItems = $visitItems;
            return $this;
        }

        public function execute() {
            foreach ($this->visitItems as $visitItem => $value) {
                $method = $this->convertToMethod($visitItem);
                $this->$method();
            }

            $this->final();
        }

        abstract protected function convertToMethod(string $name): string;

        abstract protected function final();
    }
