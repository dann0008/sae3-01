<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateUserAttributeController extends AbstractController
{
    /**
     * @Route("/update-user-attribute", name="update_user_attribute")
     */
    public function update(Request $request, ManagerRegistry $doctrine)
    {


        // Récupérez les données de la requête
        $data = json_decode($request->getContent(), true);
        $attribute = $data['attribute'];
        $value = $data['value'];
        $user = $data['user'];
        // Récupérez l'utilisateur actuellement connecté
//        $user = $data['user'];

        // Mettez à jour l'attribut de l'utilisateur
//        $user->set($attribute, $value);
        if($value==true) {
            $user->setTer($value);
        }else{
            $user->removeCandidaturesTer($attribute);
        }
        // Enregistrez les modifications de l'utilisateur en base de données
        $Manager = $doctrine->getManager();
        $Manager->persist($user);
        $Manager->flush();

        // Renvoyez une réponse JSON indiquant que la mise à jour a réussi
        return new JsonResponse(['success' => true]);
    }
}