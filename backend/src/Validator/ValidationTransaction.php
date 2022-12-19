<?php

namespace Bot\Validator;

class ValidationTransaction
{
    private ValidationResult $result;

    public function __construct()
    {
        $this->result = ValidationResult::ok();
    }

    public function pipe($run): ValidationTransaction
    {
        error_log(var_export($this->result, true) . var_export($run, true) . PHP_EOL);
        if (!$this->result->isOk()) {
            return $this;
        }

        $this->result = $run();

        return $this;
    }

    public function commit(): ValidationResult
    {
        return $this->result;
    }
}
