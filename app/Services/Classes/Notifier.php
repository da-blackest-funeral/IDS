<?php

    namespace App\Services\Classes;

    // todo вывод warning'ов делать через этот класс и зарегестрировать его как фасад
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\NoReturn;

    class Notifier
    {
        protected static Collection $messagesAndRules;

        public static function setData() {
            static::$messagesAndRules = jsonData('warnings');
        }

        #[NoReturn] public static function displayWarnings() {
            $selected = collect(selectedGroups());

            static::$messagesAndRules->each(function ($object) use ($selected) {
                if (
                    request()->has('categories') &&
                    $object->category == request()->input('categories')
                ) {
                    $needWarning = true;
                    if (isset($object->profile)) {
                        $needWarning = $object->profile == request()->input('profiles');
                    }

                    if (isset($object->groups) && !empty($object->groups)) {
                        $needWarning = $needWarning && $selected->hasAny($object->groups);
                    }

                    if (isset($object->width)) {
                        $needWarning = $needWarning && (int) request()->input('width') > $object->width;
                    }

                    if (isset($object->height)) {
                        $needWarning = $needWarning && (int) request()->input('height') > $object->height;
                    }

                    if ($needWarning) {
                        warning($object->message);
                        return false;
                    }
                }
            });
        }
    }
