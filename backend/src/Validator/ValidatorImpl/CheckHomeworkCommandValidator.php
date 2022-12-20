<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Service\UserService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class CheckHomeworkCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateIsTeacher(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === false,
            'Only teachers can check homeworks');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        return ValidationResult::process(count($matches) === 4,
            'Invalid number of arguments. Look in `help`');
    }

    #[ValidatorCommand]
    public function validateHomeworkId(int $id): ValidationResult
    {
        $homeworkService = new HomeworkService();

        return ValidationResult::process($homeworkService->getHomeworkById($id) != null,
            "Unknown homework `$id`");
    }

    #[ValidatorCommand]
    public function validateStudentId(int $id): ValidationResult
    {
        $userService = new UserService();

        return ValidationResult::process($userService->getUserById($id) != null,
            "Unknown student `$id`");
    }

    #[ValidatorCommand]
    public function validateMark(int $mark): ValidationResult
    {
        return ValidationResult::process(2 <= $mark && $mark <= 5,
            "Unexpected mark `$mark`. Move to range [2, 5]");
    }
}
