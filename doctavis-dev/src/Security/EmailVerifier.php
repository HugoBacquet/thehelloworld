<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, \Swift_Mailer $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    /**
     * @param string $verifyEmailRouteName
     * @param UserInterface $user
     * @return mixed
     */
    public function getEmailConfirmationParams(string $verifyEmailRouteName, UserInterface $user)
    {;
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        return $context;
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $urlArray = parse_url($request->getUri());
        parse_str($urlArray["query"], $query);
        unset($query["confirmationToken"]);
        $urlArray["query"] = http_build_query($query);
        $url = $urlArray["scheme"].'://'.$urlArray["host"].$urlArray["path"].'?'.$urlArray["query"];
        $this->verifyEmailHelper->validateEmailConfirmation($url, $user->getId(), $user->getEmail());

        $user->setIsVerified(true);
        $user->setConfirmationToken(null);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
