<?php

// codes 
// 0 => error
// 1 => all ok

function validate_all($required, $data)
{
    $result = array();
    $result['code'] = 1;
    $result['error'] = "All OK!";
    if (count(array_intersect_key($required, $data)) != count($required)) {
        $result['code'] = 0;
        $result['error'] = "Fields Mismatch!";
        return $result;
    }
    foreach ($required as $field => $rules) {
        $value = trim($data[$field]);
        $code = 0;
        $error = "";
        switch ($rules['type']) {
            case 'numeric':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!is_numeric($value)) {
                    $error = $field . " is not numeric.";
                } else if (isset($rules['min'])) {
                    if ($value < $rules['min']) {
                        $error = $field . " min error.";
                    } else {
                        $code   = 1;
                        $error  = "All OK!";
                    }
                } else if (isset($rules['max'])) {
                    if ($value > $rules['max']) {
                        $error = $field . " max error.";
                    } else {
                        $code   = 1;
                        $error  = "All OK!";
                    }
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'alpha':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match('/^[a-zA-Z]+$/', $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'alpha_space':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match('/^[a-zA-Z ]+$/', $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'alphanumeric':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'alphanumeric_space':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match('/^[a-zA-Z0-9 ]+$/', $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'email':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match($rules['pattern'], $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'pattern':
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else if (!preg_match($rules['pattern'], $value)) {
                    $error = $field . " is invalid.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
            case 'any':
                # code...
                if ($rules['required'] && empty($value)) {
                    $error = $field . " is empty.";
                } else {
                    $code   = 1;
                    $error  = "All OK!";
                }
                break;
        }
        $result['fields'][$field] = array('code' => $code, 'error' => $error, 'value' => $value, 'field' => $field);
    }
    foreach ($result['fields'] as $row) {
        if ($row["code"] == 0) {
            $result['code'] = 0;
            $result['error'] = $row['error'];
            break;
        }
    }
    return $result;
}
