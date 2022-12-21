<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Service\UserService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class SendMessageCommandValidator extends AbstractValidator
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    #[ValidatorCommand]
    public function validateStudentId(int $id): ValidationResult
    {
        return ValidationResult::process($this->userService->getUserById($id) != null,
            "Unknown user");
    }
}
