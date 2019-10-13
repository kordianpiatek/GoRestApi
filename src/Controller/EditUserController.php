<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="editUser", methods={"GET"})
     */
    public function editUser($id){

//        $defaultData = ['id' => $id];
        $form = $this->createFormBuilder()
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('gender', TextType::class)
            ->add('dob', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('website', UrlType::class)
            ->add('address', TextType::class)
            ->add('status', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();
        return $this->render('edit_user/editUser.html.twig', array('form' => $form->createView(),[
            'id' =>$id
        ]));
    }

    /**
     * @Route("/{id}/edit" , methods={"POST"})
     */
    public function editUserPost($id, Request $request){
        $form = $this->createFormBuilder()
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('gender', TextType::class)
            ->add('dob', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('website', UrlType::class)
            ->add('address', TextType::class)
            ->add('status', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://gorest.co.in/public-api/users?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS');
        $content = $response->getContent();
        $usersArray = json_decode($content, true);
        $users = $usersArray['result'];
        $idNumber = strval($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $formDataInputs = [
                'id' => $idNumber,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'dob' => $data['dob'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'website' => $data['website'],
                'address' =>$data['address'],
                'status' => $data['status']
            ];
            $formData = new FormDataPart($formDataInputs);
            $client->request('PUT', 'https://gorest.co.in/public-api/users/'.$id.'?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS', [
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToIterable(),
            ]);
            return $this->render('new_user/newUserCreated.html.twig',[
                'data' => $data,
                'form' =>$form
            ]);
        }else{
            return "error";
        }
    }
}
