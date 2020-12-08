<?php

if (! function_exists('redeem_regex')) {
    function redeem_regex() {
        $regex_code = '';
        tap(config('vouchers.characters'), function ($allowed) use (&$regex_code) {
            $regex_code = "([{$allowed}]{4})-([{$allowed}]{4})";
        });

        return [
            'regex_code' => $regex_code,
            'regex_email' => '[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})'
        ];
    }
}

if (! function_exists('enlist_regex')) {
    function enlist_regex() {
        $regex_code = '';
        tap(config('vouchers.characters'), function ($allowed) use (&$regex_code) {
            $regex_code = "([{$allowed}]{4})-([{$allowed}]{4})";
        });

        return [
            'regex_code' => $regex_code,
            'regex_name' => ".*"
        ];
    }
}

if (! function_exists('mudmod_regex')) {
    function mudmod_regex() {
        $regex_json = '\{.*\}';

        return [
            'regex_json' => $regex_json,
            'regex_name' => ".*"
        ];
    }
}
