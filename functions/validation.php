<?php

/**
 * Validation helper functions
 */

/**
 * Main validation function that accepts data and rules
 * 
 * @param array $data The data to validate (associative array)
 * @param array $rules The validation rules (associative array)
 * @return array|bool Array of validation errors (empty if no errors) or true if validation passes
 */
function validate($data, $rules) {
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        // Convert single rule to array
        if (!is_array($fieldRules)) {
            $fieldRules = [$fieldRules];
        }

        foreach ($fieldRules as $rule) {
            // Handle rules with parameters (e.g., min:5)
            $parameters = [];
            if (strpos($rule, ':') !== false) {
                list($rule, $parameter) = explode(':', $rule);
                $parameters = explode(',', $parameter);
            }

            $value = $data[$field] ?? null;
            $error = null;

            // Apply validation rule
            switch ($rule) {
                case 'required':
                    $error = validate_required($value, $field);
                    break;
                case 'email':
                    $error = validate_email($value, $field);
                    break;
                case 'min':
                    $error = validate_min($value, $field, $parameters[0]);
                    break;
                case 'max':
                    $error = validate_max($value, $field, $parameters[0]);
                    break;
                case 'numeric':
                    $error = validate_numeric($value, $field);
                    break;
                case 'alpha':
                    $error = validate_alpha($value, $field);
                    break;
                case 'alpha_num':
                    $error = validate_alpha_num($value, $field);
                    break;
                case 'date':
                    $error = validate_date($value, $field);
                    break;
                case 'in':
                    $error = validate_in($value, $field, $parameters);
                    break;
                case 'url':
                    $error = validate_url($value, $field);
                    break;
                case 'phone':
                    $error = validate_phone($value, $field);
                    break;
                case 'password':
                    $error = validate_password($value, $field);
                    break;
                case 'confirmed':
                    $error = validate_confirmed($value, $data[$field . '_confirmation'] ?? null, $field);
                    break;
                case 'unique':
                    $error = validate_unique($value, $field, $parameters);
                    break;
                case 'exists':
                    $error = validate_exists($value, $field, $parameters);
                    break;
                case 'foreign_key':
                    $error = validate_foreign_key($value, $field, $parameters);
                    break;
                case 'date_range':
                    $error = validate_date_range($value, $field, $parameters);
                    break;
                case 'time_range':
                    $error = validate_time_range($value, $field, $parameters);
                    break;
                    
                case 'mimetype':
                    $error = validate_mimetype($value, $field, $parameters);
                    break;
            }

            if ($error) {
                $errors[$field] = $error;
                break; // Stop validating this field after first error
            }
        }
    }

    if (count($errors) === 0) {
        return true;
    }
    return $errors;
}


/**
 * Mime type validation
 *
 * @param mixed $value The file or file metadata to validate
 * @param string $field The name of the field being validated
 * @param array $allowedMimeTypes Array of allowed mime types
 * @return string|null Error message if validation fails, or null if it passes
 */
function validate_mimetype($value, $field, $allowedMimeTypes): true|string|null
{
    if ($value === null || $value === '') {
        return null;
    }

    // Assume $value has 'tmp_name' and 'type' for file metadata
    if (is_array($value) && isset($value['tmp_name']) && isset($value['type'])) {
        $fileMimeType = mime_content_type($value['tmp_name']);

        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            return "Le champ " . format_field_name($field) .
                " doit être un fichier de type valide (" . implode(', ', $allowedMimeTypes) . ").";
        }

        return true;
    } else {
        return "Le champ " . format_field_name($field) . " est invalide ou ne contient pas de fichier.";
    }
}

/**
 * Required field validation
 */
function validate_required($value, $field) {
    if ($value === null || $value === '' || (is_array($value) && empty($value))) {
        return "Le champ " . format_field_name($field) . " est obligatoire.";
    }
    return null;
}

/**
 * Email validation
 */
function validate_email($value, $field) {
    if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "Le champ " . format_field_name($field) . " doit être une adresse email valide.";
    }
    return null;
}

/**
 * Minimum length/value validation
 */
function validate_min($value, $field, $min) {
    if ($value === null || $value === '') {
        return null;
    }

    if (is_numeric($value)) {
        if ($value < $min) {
            return "Le champ " . format_field_name($field) . " doit être supérieur ou égal à " . $min . ".";
        }
    } else {
        if (strlen($value) < $min) {
            return "Le champ " . format_field_name($field) . " doit contenir au moins " . $min . " caractères.";
        }
    }
    return null;
}

/**
 * Maximum length/value validation
 */
function validate_max($value, $field, $max) {
    if ($value === null || $value === '') {
        return null;
    }

    if (is_numeric($value)) {
        if ($value > $max) {
            return "Le champ " . format_field_name($field) . " doit être inférieur ou égal à " . $max . ".";
        }
    } else {
        if (strlen($value) > $max) {
            return "Le champ " . format_field_name($field) . " doit contenir au maximum " . $max . " caractères.";
        }
    }
    return null;
}

/**
 * Numeric validation
 */
function validate_numeric($value, $field) {
    if ($value && !is_numeric($value)) {
        return "Le champ " . format_field_name($field) . " doit être un nombre.";
    }
    return null;
}

/**
 * Alphabetic validation
 */
function validate_alpha($value, $field) {
    if ($value && !preg_match('/^[\pL\s]+$/u', $value)) {
        return "Le champ " . format_field_name($field) . " ne doit contenir que des lettres.";
    }
    return null;
}

/**
 * Alphanumeric validation
 */
function validate_alpha_num($value, $field) {
    if ($value && !preg_match('/^[\pL\pN\s]+$/u', $value)) {
        return "Le champ " . format_field_name($field) . " ne doit contenir que des lettres et des chiffres.";
    }
    return null;
}

/**
 * Date validation
 */
function validate_date($value, $field) {
    if ($value && !strtotime($value)) {
        return "Le champ " . format_field_name($field) . " doit être une date valide.";
    }
    return null;
}

/**
 * In array validation
 */
function validate_in($value, $field, $allowed) {
    if ($value && !in_array($value, $allowed)) {
        return "Le champ " . format_field_name($field) . " doit être l'une des valeurs suivantes : " . implode(', ', $allowed) . ".";
    }
    return null;
}

/**
 * URL validation
 */
function validate_url($value, $field) {
    if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
        return "Le champ " . format_field_name($field) . " doit être une URL valide.";
    }
    return null;
}

/**
 * Phone number validation
 */
function validate_phone($value, $field) {
    if ($value && !preg_match('/^[0-9]{10}$/', $value)) {
        return "Le champ " . format_field_name($field) . " doit être un numéro de téléphone valide.";
    }
    return null;
}

/**
 * Password validation
 */
function validate_password($value, $field) {
    if ($value && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
        return "Le champ " . format_field_name($field) . " doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }
    return null;
}

/**
 * Confirmation validation
 */
function validate_confirmed($value, $confirmation, $field) {
    if ($value !== $confirmation) {
        return "Le champ " . format_field_name($field) . " ne correspond pas à sa confirmation.";
    }
    return null;
}

/**
 * Unique validation
 */
function validate_unique($value, $field, $parameters) {
    if (!$value) {
        return null;
    }

    $table = $parameters[0];
    $column = $parameters[1] ?? $field;
    $except = $parameters[2] ?? null;
    $exceptColumn = $parameters[3] ?? 'id';

    $db = load_db();
    $query = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    $params = [$value];

    if ($except) {
        $query .= " AND $exceptColumn != ?";
        $params[] = $except;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        return "Cette valeur pour le champ " . format_field_name($field) . " est déjà utilisée.";
    }
    return null;
}

/**
 * Exists validation - Check if value exists in database table
 */
function validate_exists($value, $field, $parameters) {
    if (!$value) {
        return null;
    }

    $table = $parameters[0];
    $column = $parameters[1] ?? $field;

    $db = load_db();
    $query = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$value]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] === 0) {
        return "La valeur sélectionnée pour le champ " . format_field_name($field) . " n'existe pas.";
    }
    return null;
}

/**
 * Foreign key validation - Check if value exists in referenced table
 */
function validate_foreign_key($value, $field, $parameters) {
    if (!$value) {
        return null;
    }

    $table = $parameters[0];
    $column = $parameters[1] ?? 'id';
    $referencedTable = $parameters[2];
    $referencedColumn = $parameters[3] ?? 'id';

    $db = load_db();
    $query = "SELECT COUNT(*) as count FROM $referencedTable WHERE $referencedColumn = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$value]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] === 0) {
        return "La référence sélectionnée pour le champ " . format_field_name($field) . " n'existe pas.";
    }
    return null;
}

/**
 * Date range validation - Check if date is within allowed range
 */
function validate_date_range($value, $field, $parameters) {
    if (!$value) {
        return null;
    }

    $minDate = $parameters[0] ?? null;
    $maxDate = $parameters[1] ?? null;
    $date = strtotime($value);

    if ($minDate && strtotime($minDate) > $date) {
        return "La date pour le champ " . format_field_name($field) . " doit être après " . date('d/m/Y', strtotime($minDate)) . ".";
    }

    if ($maxDate && strtotime($maxDate) < $date) {
        return "La date pour le champ " . format_field_name($field) . " doit être avant " . date('d/m/Y', strtotime($maxDate)) . ".";
    }

    return null;
}

/**
 * Time range validation - Check if time is within allowed range
 */
function validate_time_range($value, $field, $parameters) {
    if (!$value) {
        return null;
    }

    $minTime = $parameters[0] ?? null;
    $maxTime = $parameters[1] ?? null;
    $time = strtotime($value);

    if ($minTime && strtotime($minTime) > $time) {
        return "L'heure pour le champ " . format_field_name($field) . " doit être après " . date('H:i', strtotime($minTime)) . ".";
    }

    if ($maxTime && strtotime($maxTime) < $time) {
        return "L'heure pour le champ " . format_field_name($field) . " doit être avant " . date('H:i', strtotime($maxTime)) . ".";
    }

    return null;
}

/**
 * Format field name for error messages
 */
function format_field_name($field) {
    // Convert snake_case or camelCase to spaces
    $field = str_replace(['_', '-'], ' ', $field);
    // Convert camelCase to spaces
    $field = preg_replace('/(?<!^)[A-Z]/', ' $0', $field);
    // Capitalize first letter
    return ucfirst($field);
} 