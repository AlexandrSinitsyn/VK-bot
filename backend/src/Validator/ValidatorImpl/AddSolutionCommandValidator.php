<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Cache\CacheAdapter;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Service\HomeworksSolutionService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;
use DateTime;

#[Validator]
class AddSolutionCommandValidator extends AbstractValidator
{
    private HomeworkService $homeworkService;
    private HomeworksSolutionService $solutionService;

    public function __construct()
    {
        $this->homeworkService = new HomeworkService();
        $this->solutionService = new HomeworksSolutionService(new CacheAdapter());
    }

    #[ValidatorCommand]
    public function validateIsStudent(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === true,
            'Only students can add solutions');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        return ValidationResult::process(count($matches) === 3,
            'Invalid number of arguments. Look in `help`');
    }

    #[ValidatorCommand]
    public function validateHomeworkId(int $id): ValidationResult
    {
        return ValidationResult::process($this->homeworkService->getHomeworkById($id) != null,
            "Unknown homework `$id`");
    }

    #[ValidatorCommand]
    public function validateUnique(array $ids): ValidationResult
    {
        return ValidationResult::process($this->solutionService->getSolution($ids['hwid'], $ids['uid']) == null,
            'Homework solution is not unique');
    }

    #[ValidatorCommand]
    public function validateDeadline(int $hwId): ValidationResult
    {
        $time = strtotime((new DateTime())->format('d-m-Y'));

        return ValidationResult::process($this->homeworkService->getHomeworkById($hwId)->deadline->getTimestamp() >= $time,
            "Deadline has already expired");
    }
}
