<?php

namespace Widget_Corp_Oops_Admin\Services;

class ValidationServices
{
    public function validateRequiredFields(array $requiredFields, array $data = []): array
    {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                $errors[] = $field;
            }
        }
        return $errors;
    }

    public function validateMaxLengths(array $fieldLengths, array $data = []): array
    {
        $errors = [];
        foreach ($fieldLengths as $field => $maxLength) {
            if (isset($data[$field]) && strlen(trim($data[$field])) > $maxLength) {
                $errors[] = $field;
            }
        }
        return $errors;
    }
}
