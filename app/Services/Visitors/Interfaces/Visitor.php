<?php

    namespace App\Services\Visitors\Interfaces;

    use Illuminate\Support\Collection;

    interface Visitor
    {
        public function addVisitable(Visitable $visitable): Visitor;

        public function setVisitable(Collection $visitableCollection): Visitor;

        public function visitDelivery();

        public function visitMeasuring();

        public function visitCountAdditionalVisits();

        public function visitKilometres();

        public function visitAddress();

        public function visitSale();

        public function visitAutoSale();

        public function visitPrepayment();

        public function visitPerson();

        public function visitMinimalSum();

        public function visitSumManually();

        public function visitWageManually();

        public function visitWish();

        public function visitAllOrderComment();

        public function visitInstaller();

        public function visitMinimalOrderSum();

        public function execute();
    }
