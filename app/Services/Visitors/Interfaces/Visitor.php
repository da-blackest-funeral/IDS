<?php

    namespace App\Services\Visitors\Interfaces;

    interface Visitor
    {
        public function setVisitable(array $visitItems): Visitor;

        public function execute(): Visitor;

        public function final();
    }
