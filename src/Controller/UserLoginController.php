<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user){
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // TODO
        $token = null;
        
        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token
        ], Response::HTTP_ACCEPTED);
    }


    #[Route('/user/register', name: 'user_register', methods:['POST'])]
    public function register(ValidatorInterface $validator, Request $req)
    {
        $content = json_decode($req->getContent(), true);
        if(is_null($content) || !$content){
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setUserName($content['username']);
        $user->setEmail($content['email']);
        $user->setPassword($content['password']);

        //$validator = new ValidatorInterface();
        $err = $validator->validate($user);
        //return $this->json([count($err)]);
        
        if(count($err) > 0){
            $errString = (string) $err;
            return $this->json([$errString], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['not that bad']);
    }
}
