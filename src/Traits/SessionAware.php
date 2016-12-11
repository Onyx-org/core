<?php

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

    private function addSuccessFlash($message): void
    {
        return $this->addFlash($message, 'success');
    }

    private function addInfoFlash($message): void
    {
        return $this->addFlash($message, 'info');
    }

    private function addWarningFlash($message): void
    {
        return $this->addFlash($message, 'warning');
    }

    private function addErrorFlash($message): void
    {
        return $this->addFlash($message, 'error');
    }

    private function addResultFlash($result, $successMessage, $errorMessage): void
    {
        if($result)
        {
            return $this->addSuccessFlash($successMessage);
        }

        return $this->addErrorFlash($errorMessage);
    }

    private function addFlash($message, $type = 'info'): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
