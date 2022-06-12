<?php

    namespace App\Services\Commands\Classes;

    class DeliveryKilometresCommand extends DeliveryCommand
    {
        public function execute() {
            if (!$this->order->need_delivery) {
                return $this;
            }

            $this->calculateOrderPrice();
            $this->calculateSalary();
            $this->order->kilometres = $this->kilometres;

            return $this;
        }

        private function calculateOrderPrice() {
            $this->order->price +=
                $this->deliveryPrice *
                $this->kilometresDifference() *
                $this->visits();
        }

        private function kilometresDifference() {
            return $this->kilometres - $this->order->kilometres;
        }

        private function visits() {
            return ($this->order->additional_visits + 1) * (1 + $this->order->measuring);
        }

        private function calculateSalary() {
            $kilometresSalaryDiff = (
                $this->deliveryWage *
                ($this->kilometres - $this->order->kilometres)
            );

            $deliveryVisits = ($this->order->additional_visits + 1);

            $totalVisits = $deliveryVisits + $this->order->measuring * $deliveryVisits;

            $this->salary->sum += $kilometresSalaryDiff * $totalVisits;
        }

        public function save() {
            $this->order->update();
            $this->salary->update();
        }
    }
