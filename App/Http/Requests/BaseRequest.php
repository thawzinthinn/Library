<?php

namespace App\Http\Requests;

abstract class BaseRequest
{
    protected array $data;
    protected array $errors = [];

    public function __construct()
    {
        $this->data = array_map('trim', $_POST);
        $this->prepareForValidation();
    }

    protected function prepareForValidation(): void
    {
    }

    // each request defines rules
    abstract public function rules(): array;

    public function validate(): bool
    {
        foreach ($this->rules() as $field => $rules) {

            $value = $this->data[$field] ?? null;

            // REQUIRED
            if (($rules['required'] ?? false) && empty($value)) {
                $this->errors[$field] = "$field is required";
                continue;
            }

            // MIN LENGTH
            if (isset($rules['min']) && strlen($value) < $rules['min']) {
                $this->errors[$field] = "$field must be at least {$rules['min']} characters";
            }

            /**
             * EMAIL VALIDATION (STRICT CUSTOM RULE)
             */
            if (($rules['email'] ?? false)) {

                // custom gmail-only strict format:
                // letters + numbers only before @gmail.com
                $pattern = '/^[a-zA-Z0-9]+@gmail\.com$/';

                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = "Invalid email format";
                }

                // extra strict rule
                elseif (!preg_match($pattern, $value)) {
                    $this->errors[$field] = "Email must be like: letters+numbers@gmail.com";
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}