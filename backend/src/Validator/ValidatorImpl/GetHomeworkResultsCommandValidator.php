<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Service\HomeworkService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class GetHomeworkResultsCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateHomeworkExists(int $id): ValidationResult
    {
        $homeworkService = new HomeworkService();

        return ValidationResult::process($homeworkService->getHomeworkById($id) != null,
            'Homework is not found');
    }
}
