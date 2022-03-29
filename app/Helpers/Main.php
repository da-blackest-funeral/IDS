<?php

    function isOrderPage() {
        return \request()->path() == '/';
    }
