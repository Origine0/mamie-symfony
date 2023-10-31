<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CafeRepository;
use App\Repository\TypeCafeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Cafe;
use App\Form\AjoutCafeType;

class CafeController extends AbstractController
{
    #[Route('/ajout_cafe', name: 'app_ajoutcafe')]
    public function ajoutCafe(Request $request, EntityManagerInterface $em): Response
    {
        $cafe = new Cafe();
        $form = $this->createForm(AjoutCafeType::class, $cafe);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em->persist($cafe);
                $em->flush();
                $this->addFlash('notice','Café ajouté');
                return $this->redirectToRoute('app_ajoutcafe');
            }
        }
        return $this->render('cafe/ajoutCafe.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/liste_typecafe', name: 'app_listetypecafe')]
    public function listeContacts(TypeCafeRepository $typecafeRepository): Response
    {
        $typecafes = $typecafeRepository->findAll();
        return $this->render('cafe/liste_cafe.html.twig', [
            'typecafes' => $typecafes
        ]);
    }
}
