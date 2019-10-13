<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    //Delete user
    /**
     * @Route("/{id}/delete", name="deleteUser", methods={"GET"})
     */
    public function deleteUser($id){
        $client = HttpClient::create();
        $response = $client->request('DELETE','https://gorest.co.in/public-api/users/'.$id.'?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
        $response2 = $client->request('GET','https://gorest.co.in/public-api/users/'.$id.'?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
        $content = $response2->getContent();
        $deletedUserArray = json_decode($content,true);
        $deletedUser = $deletedUserArray['result'];
        if(!empty($deletedUser['first_name'])){
            return $this->render('delete/deletedUser.html.twig',[
                'deletedUser' => $deletedUser
            ]);
        }else{
            return $this->render('delete/deletedUserEmpty.html.twig',[
                'deletedUser' => $deletedUser
            ]);
        }
    }
}
