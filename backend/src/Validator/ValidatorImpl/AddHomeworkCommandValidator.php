<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Service\HomeworkService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;
use DateTime;
use Exception;

#[Validator]
class AddHomeworkCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateDate(string $date): ValidationResult
    {
        try {
            /** @noinspection PhpUnusedLocalVariableInspection */
            $ignored = new DateTime($date);

            $today = (new DateTime())->format('d-m-Y');

            return ValidationResult::process(strtotime($date) > strtotime($today),
                'Deadline has already expired');
        } catch (Exception $e) {
            return ValidationResult::failure('Invalid date', $e);
        }
    }

    #[ValidatorCommand]
    public function validateUnique(int $number): ValidationResult
    {
        $homeworkService = new HomeworkService();

        return ValidationResult::process($homeworkService->getHomeworkById($number) == null,
            'Homework is not unique');
    }
}
