<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value by key
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $category
     * @param string|null $description
     * @return void
     */
    function set_setting(string $key, $value, string $type = 'string', string $category = 'general', string $description = null): void
    {
        \App\Models\Setting::set($key, $value, $type, $category, $description);
    }
}
