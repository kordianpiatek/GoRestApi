<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    // Show all users
    /**
     * @Route("/", name="users", methods={"GET"})
     */
    public function allUsers()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://gorest.co.in/public-api/users?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
        $content = $response->getContent();
        $usersArray = json_decode($content, true);
        $users = $usersArray['result'];
        return $this->render('user/users.html.twig',[
            'users' => $users,
        ]);
    }

    // Find user by id
    /**
     * @Route("/{id}/find", name="findUser", methods={"GET"})
     */
    public function findUser($id){
        $client = HttpClient::create();
        $response = $client->request('GET','https://gorest.co.in/public-api/users/'.$id.'?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
        $content = $response->getContent();
        $foundUserArray = json_decode($content,true);
        $foundUser = $foundUserArray['result'];
        return $this->render('user/findUser.html.twig',[
           'foundUser' => $foundUser
        ]);
    }
}
