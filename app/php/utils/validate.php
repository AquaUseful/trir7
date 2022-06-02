<?php
namespace utils\validate {

    $DESCRIPTOR_DIR = '/var/www/html/assets/forms/';
    $DESCRIPTOR_EXTENSION = '.json';

    function get_descriptor(string $form): array
    {
        global $DESCRIPTOR_DIR, $DESCRIPTOR_EXTENSION;
        $text = file_get_contents($DESCRIPTOR_DIR . $form . $DESCRIPTOR_EXTENSION);
        return json_decode($text, true);
    }

    function get_forminfo(string $form): array
    {
        $desc = get_descriptor($form);
        $result = [
            'fields' => [],
            'buttons' => [],
            'links' => []
        ];

        foreach (['fields', 'buttons', 'links'] as $type) {
            foreach ($desc[$type] as $id => $cont) {
                $result[$type][] = $id;
            }
        }

        return $result;
    }

    function validate_field($fieldValidator, string $fieldValue, array $fields): array
    {
        $result = [
            "valid" => true,
            "displayError" => ""
        ];
        if ($fieldValidator === null) {
            return $result;
        }
        foreach ($fieldValidator as $v) {
            $valid = true;
            switch ($v['type']) {
                case 'regex':
                    if (!preg_match($v['regex'], $fieldValue)) {
                        $valid = false;
                    }
                    break;

                case 'match':
                    if ($fieldValue !== $fields[$v['matchField']]) {
                        $valid = false;
                    }
                    break;
            }
            if (!$valid) {
                $result['valid'] = false;
                $result['displayError'] = $v['displayError'];
                break;
            }
        }
        return $result;
    }

    function validate_fields(array $validators, array $fields): array
    {
        $result = [
            'valid' => true,
            'fields' => []
        ];
        foreach ($fields as $name => $value) {
            $v = $validators[$name];
            $fieldResult = validate_field($v, $value, $fields);
            $result['fields'][$name] = $fieldResult;
            if (!$fieldResult['valid']) {
                $result['valid'] = false;
            }
        }
        return $result;
    }

    function validate_form(array $form): array
    {
        $descriptor = get_descriptor($form['name']);
        assert($descriptor !== null);
        return validate_fields($descriptor['validators'], $form['fields']);
    }

}
