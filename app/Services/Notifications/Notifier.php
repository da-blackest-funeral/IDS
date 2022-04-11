<?php

    namespace App\Services\Notifications;

    // todo вывод warning'ов делать через этот класс и зарегестрировать его как фасад
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\NoReturn;

    class Notifier
    {
        /**
         * Specified data for conditional displaying warnings
         *
         * @var Collection
         */
        protected static Collection $messagesAndRules;

        /**
         * Setting data from json file
         * (it happens at every post request automatically)
         *
         * @return void
         */
        public function setData() {
            static::$messagesAndRules = jsonData('warnings');
        }

        /**
         * Checks specified conditions and writes warning messages
         *
         * @return void
         */
        #[NoReturn] public function displayWarnings() {
            $selected = collect(selectedGroups());

            static::$messagesAndRules->each(function ($object) use ($selected) {
                if (
                    request()->has('categories') &&
                    $object->category == request()->input('categories')
                ) {
                    $needWarning = false;
                    if (isset($object->profile)) {
                        $needWarning = $object->profile == request()->input('profiles');
                    }

                    if (isset($object->groups) && !empty($object->groups)) {
                        foreach ($object->groups as $group) {
                            $needWarning = $needWarning && $selected->contains($group);
                        }
                    }

                    if (isset($object->width)) {
                        $needWarning = $needWarning && (int)request()->input('width') > $object->width;
                    }

                    if (isset($object->height)) {
                        $needWarning = $needWarning && (int)request()->input('height') > $object->height;
                    }

                    if ($needWarning) {
                        static::warning($object->message);
                        return false;
                    }
                }
            });
        }

        /**
         * Outputs only unique warning-messages
         *
         * @param string $text
         * @return void
         */
        public function warning(string $text) {
            if (is_null(session('warnings'))) {
                warning($text);
                return;
            }

            foreach (session('warnings') as $warning) {
                if ($warning !== $text) {
                    warning($text);
                }
            }
        }
    }
