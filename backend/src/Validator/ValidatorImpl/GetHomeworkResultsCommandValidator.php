<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class GetHomeworkResultsCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateIsStudent(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === true,
            'Only students can add solutions');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        return ValidationResult::process(count($matches) === 2,
            'Invalid number of arguments. Look in `help`');
    }

    #[ValidatorCommand]
    public function validateHomeworkExists(int $id): ValidationResult
    {
        $homeworkService = new HomeworkService();

        return ValidationResult::process($homeworkService->getHomeworkById($id) != null,
            'Homework is not found');
    }
}
