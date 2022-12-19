<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;
use DateTime;
use Exception;

#[Validator]
class AddHomeworkCommandValidator extends AbstractValidator
{
    #[ValidatorCommand]
    public function validateIsStudent(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === false,
            'Only teachers can add homework');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        return ValidationResult::process(count($matches) === 3,
            "Invalid number of arguments. Look in `help`");
    }

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
        $homeworkSolution = new HomeworkService();

        return ValidationResult::process($homeworkSolution->getHomeworkById($number) == null,
            'Homework is not unique');
    }
}