<?php /** @noinspection PhpUnused */

namespace Bot\Validator\ValidatorImpl;

use Bot\Attributes\ValidatorCommand;
use Bot\Cache\CacheAdapter;
use Bot\Entity\User;
use Bot\Service\HomeworkService;
use Bot\Service\HomeworksSolutionService;
use Bot\Validator\ValidationResult;
use Bot\Attributes\Validator;

#[Validator]
class GetSolutionCommandValidator extends AbstractValidator
{
    private HomeworkService $homeworkService;
    private HomeworksSolutionService $solutionService;

    public function __construct()
    {
        $this->homeworkService = new HomeworkService();
        $this->solutionService = new HomeworksSolutionService(new CacheAdapter());
    }

    #[ValidatorCommand]
    public function validateIsTeacher(User $user): ValidationResult
    {
        return ValidationResult::process($user->student === false,
            'Only teachers can check homeworks');
    }

    #[ValidatorCommand]
    public function validateArguments(array $matches): ValidationResult
    {
        return ValidationResult::process(count($matches) === 3,
            'Invalid number of arguments. Look in `help`');
    }

    #[ValidatorCommand]
    public function validateHomeworkExists(int $id): ValidationResult
    {
        return ValidationResult::process($this->homeworkService->getHomeworkById($id) != null,
            "Unknown homework `$id`");
    }

    #[ValidatorCommand]
    public function validateSolutionExists(array $ids): ValidationResult
    {
        $hwid = $ids['hwid'];
        $uid = $ids['uid'];

        return ValidationResult::process($this->solutionService->getSolution($hwid, $uid) != null,
            "Solution for `hw=$hwid` from `u=$uid` is not found");
    }

    #[ValidatorCommand]
    public function validateNotChecked(array $ids): ValidationResult
    {
        return ValidationResult::process($this->solutionService->getSolution($ids['hwid'], $ids['uid']) != null,
            'Solution has been already checked');
    }
}
