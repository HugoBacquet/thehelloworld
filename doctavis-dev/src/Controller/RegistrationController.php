<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Constant\Roles;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    /**
     * @var EntityManagerInterface $manager
     */
    private $manager;

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $manager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->manager = $manager;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $roles = $user->getRoles();
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $roles[] = Roles::ROLE_PRACTITIONER;
            $user->setRoles($roles);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $confirmationToken = bin2hex(random_bytes(64));
            $user->setConfirmationToken($confirmationToken);
            $entityManager->persist($user);
            $entityManager->flush();

//            // generate a signed url and email it to the user
            $paramsLink = $this->emailVerifier->getEmailConfirmationParams('app_verify_email', $user);

            $confirmationMessage = (new \Swift_Message('Please Confirm your Email'))
                ->setFrom($_ENV['MAIL_ADDRESS'])
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'registration/confirmation_email.html.twig',
                        array_merge([
                            'confirmationToken' => $confirmationToken,
                            'username' => $user->getEmail()
                        ], $paramsLink)
                    ),
                    'text/html'
                );

//             Mail confirmation Zoe
            $confirmationEmail = (new \Swift_Message("Un nouveau practicien s'est inscrit"))
                ->setFrom($_ENV['MAIL_ADDRESS'])
                ->setTo($_ENV['MAIL_ADDRESS'])
                ->setBody(
                    $this->renderView(
                        'emails/new_practitioner.html.twig',
                        [
                            'username' => $user->getUsername()
                        ]
                    ),
                    'text/html'
                );

            $mailer->send($confirmationMessage);
            $mailer->send($confirmationEmail);

            return $this->render('registration/confirmation_message.html.twig');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $confirmationToken = $request->query->get('confirmationToken');
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $user = $this->manager->getRepository(User::class)->findOneByConfirmationToken($confirmationToken);
            if (null == $user) {
                throw new \Exception('Invalid Link');
            }
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('practitioner_new');
    }
}
