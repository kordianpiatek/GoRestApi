<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function searchBar(){
        $form = $this->createFormBuilder(null)
            ->add('query',TextType::class)
            ->add('search',SubmitType::class)
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
           'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/search", name="searchUser", methods={"GET"})
     */
    public function searchUser(){
        return $this->render('search/searchUser.html.twig');
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function searchUserPost(Request $request){
        $form = $this->createFormBuilder(null)
            ->add('query',TextType::class)
            ->add('search',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['query'];
            $client = HttpClient::create();
            $response = $client->request('GET', 'https://gorest.co.in/public-api/users?name='.$name.'_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
            $content = $response->getContent();
            $searchedUserArray = json_decode($content,true);
            $searchedUser = $searchedUserArray['result'];
            return $this->render('search/searchedUser.html.twig',[
                'searchedUser' => $searchedUser,
                'form' =>$form
            ]);
        }else{
            return "error";
        }
    }
}
