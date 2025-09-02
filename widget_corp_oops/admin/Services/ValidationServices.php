<?php

namespace Widget_Corp_Oops_Admin\Services;

class ValidationServices
{
    public function validateRequiredFields(array $requiredFields): array
    {
        $errors = array();
        foreach ($requiredFields as $field) {
            if (! isset($_POST[ $field ]) || trim($_POST[ $field ]) === '') {
                $errors[] = $field;
            }
        }
        return $errors;
    }

    public function validateMaxLengths(array $fieldLengths): array
    {
        $errors = array();
        foreach ($fieldLengths as $field => $maxLength) {
            if (isset($_POST[ $field ]) && strlen(trim($_POST[ $field ])) > $maxLength) {
                $errors[] = $field;
            }
        }
        return $errors;
    }
}
