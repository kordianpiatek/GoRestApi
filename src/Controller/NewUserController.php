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

class NewUserController extends AbstractController
{
    //Adding new User
    /**
     * @Route("/newUser",name="newUser", methods={"GET"})
     */
    public function newUserGet()
    {
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
        return $this->render('new_user/newUser.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/newUser", methods={"POST"})
     */
    public function newUserPost(Request $request){
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
        $idNumber = sizeof($users) + 1;
        $stringIdNumber = strval($idNumber);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $formDataInputs = [
                'id' => $stringIdNumber,
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
            $client->request('POST', 'https://gorest.co.in/public-api/users?_format=json&access-token=CG2w5p_gBJEBBus-Ig8plQdk9Gkv1DKjAZkS', [
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
