<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Course;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CourseVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function __construct(protected AccessDecisionManagerInterface $decisionManager)
    {
    }

    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $subject instanceof Course;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $course = $subject;

        if (!$user instanceof User || !$course instanceof Course) {
            return false;
        }

        return match ($attribute) {
            self::EDIT, self::DELETE => $user === $course->getAuthor() || $this->decisionManager->decide($token, ['ROLE_ADMIN']),
            default => false,
        };
    }
}
