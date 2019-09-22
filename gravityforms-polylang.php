<?php

/**
 * Gravity Forms + Polylang
 *
 * Add form titles, descriptions, field labels, etc, to Polylang string translations
 * Based: https://github.com/pdme/gravity-forms-polylang
 */

if (!class_exists('GF_PLL')) :
    class GF_PLL
    {
        private $whitelist;
        private $blacklist;
        private $registered_strings;
        private $form;

        public function __construct()
        {
            $this->whitelist = array(
                'title',
                'description',
                'text',
                'content',
                'message',
                'defaultValue',
                'errorMessage',
                'placeholder',
                'label',
                'checkboxLabel',
                'customLabel',
                'value',
                'subject',
                'validationMessage'
            );
            $this->blacklist = array();
            $this->registered_strings = array();
        }

        private function is_translatable($key, $value)
        {
            return
            $key &&
            in_array($key, $this->whitelist) &&
            is_string($value) &&
            !in_array($value, $this->registered_strings);
        }

        private function iterate_form(&$value, $key, $callback = null)
        {
            if (!$callback && is_callable($key)) {
                $callback = $key;
            }

            if (is_array($value) || is_object($value)) {
                foreach ($value as $new_key => &$new_value) {
                    if (!(in_array($new_key, $this->blacklist) && !is_numeric($new_key))) {
                        $this->iterate_form($new_value, $new_key, $callback);
                    }
                }
            } else {
                if ($this->is_translatable($key, $value)) {
                    $callback($value, $key);
                }
            }
        }

        public function register_strings()
        {
            if (!class_exists('GFAPI') || !function_exists('pll_register_string')) return;

            $forms = GFAPI::get_forms();
            foreach ($forms as $form) {
                $this->form = $form;
                $this->registered_strings = array();
                $this->iterate_form($form, function ($value, $key) {
                    $name = ''; // todo: suitable naming
                    $group = "Form #{$this->form['id']}: {$this->form['title']}";
                    pll_register_string($name, $value, $group);
                    $this->registered_strings[] = $value;
                });
            }
        }

        public function translate_strings($form)
        {
            if (function_exists('pll__')) {
                $this->iterate_form($form, function (&$value, $key) {
                    $value = pll__($value);
                });
            }
            return $form;
        }
    }
endif;

if (!class_exists('GF_PLL_Initialize')) :
    class GF_PLL_Initialize
    {
        public static function register_strings()
        {
            $gf_pll = new GF_PLL();
            $gf_pll->register_strings();
        }

        public static function translate_strings($form)
        {
            $gf_pll = new GF_PLL();
            return $gf_pll->translate_strings($form);
        }
    }

    add_action('init', array('GF_PLL_Initialize', 'register_strings'), 100);
    add_filter('gform_pre_render', array('GF_PLL_Initialize', 'translate_strings'));
    add_filter('gform_pre_process', array('GF_PLL_Initialize', 'translate_strings'));
endif;
