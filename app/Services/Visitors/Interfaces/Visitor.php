<?php

    namespace App\Services\Visitors\Interfaces;

    use Illuminate\Support\Collection;

    interface Visitor
    {
        public function __construct(array $visitItems);

        public function setVisitable(array $visitItems): Visitor;

        public function execute(): Visitor;

        public function final();
    }
