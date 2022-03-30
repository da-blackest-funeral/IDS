<?php

    function isOrderPage() {
        return \request()->path() == '/' || substr_count( request()->path(), 'orders', );
    }
