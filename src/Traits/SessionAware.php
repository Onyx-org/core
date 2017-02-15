<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Symfony\Component\HttpFoundation\Session\Session;

trait SessionAware
{
    private
        $session;

    public function setSession(Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    private function addSuccessFlash(string $message): void
    {
        return $this->addFlash($message, 'success');
    }

    private function addInfoFlash(string $message): void
    {
        return $this->addFlash($message, 'info');
    }

    private function addWarningFlash(string $message): void
    {
        return $this->addFlash($message, 'warning');
    }

    private function addErrorFlash(string $message): void
    {
        return $this->addFlash($message, 'error');
    }

    private function addResultFlash($result, string $successMessage, string $errorMessage): void
    {
        if($result)
        {
            return $this->addSuccessFlash($successMessage);
        }

        return $this->addErrorFlash($errorMessage);
    }

    private function addFlash(string $message, string $type = 'info'): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
