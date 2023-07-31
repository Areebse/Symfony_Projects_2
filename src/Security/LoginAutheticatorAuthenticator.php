<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAutheticatorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
        );
///////////////////////
//        $passport =  new Passport(
//            new UserBadge($email),
//            new PasswordCredentials($request->request->get('password', '')),
//            [
//                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
//        );
//        dump($passport);
//        exit();
//        $passport returns
//        LoginAutheticatorAuthenticator.php on line:
//Symfony\Component\Security\Http\Authenticator\Passport\Passport {#290 ▼
//        #user: null
//        -badges: array:3 [▼
//    "Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge" => Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge {#289 ▼
//            -userIdentifier: "good@gmail.com"
//            -userLoader: null
//            -attributes: null
//    }
//    "Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials" => Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials {#288 ▼
//            -password: "good123"
//            -resolved: false
//    }
//    "Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge" => Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge {#287 ▼
//            -resolved: false
//            -csrfTokenId: "authenticate"
//            -csrfToken: "6762b8b0a8492e58fd4bd4dbc89.-uje9OEKrJsr8yosgVK3gYWpNWPalvg3oF-kQSYwMss.mIntmIVzwu9Jp3JAuWCH0LKeYDz31ZJ45GnxAmRJH_yL3oufslPe-Wa4WQ"
//    }
//  ]
//  -attributes: []
//}
//////////////////////
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

////////
//        $targetPath2 = $this->getTargetPath($request->getSession(), $firewallName);
//        dump($targetPath2);
//        exit();
//  $targetPath returns null;
///////

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($this->urlGenerator->generate('app_todo',['user'=>$request->request->get('email')]));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);


        ///////
//        $test = $this->urlGenerator->generate(self::LOGIN_ROUTE);
//        dump($test);
//        exit();
//        It gives "/login"
        //////
    }
}
