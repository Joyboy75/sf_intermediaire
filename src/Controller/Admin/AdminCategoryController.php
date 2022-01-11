<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController {

    // Pour les trois entités (Product, Brand et Category): faire le CRUD complet dans des AdminController

    // Modèle des routes @Route("admin/create/product/", name="admin_create_product")


    /**
     * @Route("admin/categories", name="admin_category_list")
     */
    public function categoryList(CategoryRepository $categoryRepository){

        $categories = $categoryRepository->findAll();
    
            return $this->render("admin/categories.html.twig", ['categories' => $categories]);
        }
    
         /**
         * @Route("admin/category/{id}", name="admin_category_show")
         */
        public function categoryShow($id,CategoryRepository $categoryRepository){
    
            $category = $categoryRepository->find($id);
        
                return $this->render("admin/category.html.twig", ['category' => $category]);
            }

            /**
     * @Route("admin/create/category/", name="admin_create_category")
     */
    public function categoryCreate(Request $request, EntityManagerInterface $entityManagerInterface){
        $category = new Category();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $entityManagerInterface->persist($category);
            $entityManagerInterface->flush();
            $this->addFlash(
                'notice',
                'Une category a été créé'
            );

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/categoryform.html.twig', [ 'categoryForm' => $categoryForm->createView()]);
    
    }

     /**
      * @Route("admin/update/category/{id}", name="admin_category_update")
      */
      public function categoryUpdate(
        $id,
         CategoryRepository $categoryRepository,
         Request $request, // class permettant d'utiliser le formulaire de récupérer les information 
         EntityManagerInterface $entityManagerInterface // class permettantd'enregistrer ds la bdd
         ){
             $category = $categoryRepository->find($id);

             // Création du formulaire
          $categoryForm = $this->createForm(CategoryType::class, $category);

          // Utilisation de handleRequest pour demander au formulaire de traiter les informations
      // rentrées dans le formulaire
      // Utilisation de request pour récupérer les informations rentrées dans le formualire
          $categoryForm->handleRequest($request);


          if($categoryForm->isSubmitted() && $categoryForm->isValid())
          {   
              // persist prépare l'enregistrement ds la bdd analyse le changement à faire
              $entityManagerInterface->persist($category);
              $id = $categoryRepository->find($id);

              // flush enregistre dans la bdd
              $entityManagerInterface->flush();

              $this->addFlash(
                'notice',
                'La category a bien été modifié !'
            );

              return $this->redirectToRoute('admin_category_list');

          }

          return $this->render('admin/categoryform.html.twig', ['categoryForm'=> $categoryForm->createView()]);
    }

    /**
     * @Route("admin/delete/category/{id}", name="admin_category_delete")
     */
    public function categoryDelete(
        $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManagerInterface
        ){

            $category = $categoryRepository->find($id);

            //remove supprime le category et flush enregistre ds la bdd
            $entityManagerInterface->remove($category);
            $entityManagerInterface->flush();

            $this->addFlash(
                'notice',
                'Votre category a bien été supprimé'
            );

            return $this->redirectToRoute('admin_category_list');

    }
}