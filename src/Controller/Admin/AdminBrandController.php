<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBrandController extends AbstractController {

    // Pour les trois entités (Product, Brand et Category): faire le CRUD complet dans des AdminController

    // Modèle des routes @Route("admin/create/product/", name="admin_create_product")


    /**
     * @Route("admin/brands", name="admin_brand_list")
     */
    public function categoryList(BrandRepository $brandRepository){

        $brands = $brandRepository->findAll();
    
            return $this->render("admin/brands.html.twig", ['brands' => $brands]);
        }
    
         /**
         * @Route("admin/brand/{id}", name="admin_brand_show")
         */
        public function categoryShow($id,BrandRepository $brandRepository){
    
            $brand = $brandRepository->find($id);
        
                return $this->render("admin/brand.html.twig", ['brand' => $brand]);
            }

            /**
     * @Route("admin/create/brand/", name="admin_create_brand")
     */
    public function brandCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $brand = new Brand();

        $brandForm = $this->createForm(BrandType::class, $brand);

        $brandForm->handleRequest($request);

        if($brandForm->isSubmitted() && $brandForm->isValid()){
            $entityManagerInterface->persist($brand);
            $entityManagerInterface->flush();
            $this->addFlash(
                'notice',
                'Une brand a été créé'
            );
            
            return $this->redirectToRoute('admin_brand_list');
        }

        return $this->render('admin/brandform.html.twig', [ 'brandForm' => $brandForm->createView()]);
    
    }

     /**
      * @Route("admin/update/brand/{id}", name="admin_brand_update")
      */
      public function brandUpdate(
        $id,
         BrandRepository $brandRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $brand = $brandRepository->find($id);

             // Création du formulaire
          $brandForm = $this->createForm(BrandType::class, $brand);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $brandForm->handleRequest($request);


          if($brandForm->isSubmitted() && $brandForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($brand);
              $id = $brandRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'La brand a bien été modifié !'
            );

              return $this->redirectToRoute('admin_brand_list');

          }

          return $this->render('admin/brandform.html.twig', ['brandForm'=> $brandForm->createView()]);
    }

    /**
     * @Route("admin/delete/brand/{id}", name="admin_brand_delete")
     */
    public function brandDelete(
        $id,
        BrandRepository $brandRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $brand = $brandRepository->find($id);

            //remove supprime le brand et flush enregistre ds la bdd
            $entityManagerInterface->remove($brand);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre brand a bien été supprimé'
            );

            return $this->redirectToRoute('admin_brand_list');

    }
}