<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProduitController extends Controller
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index()
    {
        return $this->render('produit/admin_creationProduit.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    public function creationProduit(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($produit->getImage()))
            {
                $fileName = $this->generateUniqueFileName().'.jpeg';
                $file = new UploadedFile($produit->getImage(), $fileName);
            
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                $produit->setImage($fileName);

            }
            else {
                $produit->setImage('6bca0f32d445f16e2a3e7730035a1e12.jpeg');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
        return $this->redirectToRoute('creationProduit');

        
        }

        return $this->render('produit/admin_creationProduit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
    *  @Route("/produit/{id}", name="produit", requirements={"id"="\d+"})
    */

    public function showProduit($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit= $entityManager->getRepository(Produit::class)->find($id);

        if(!$produit)
        {
            throw $this->createNotFoundException('Aucun produit ne correspond');
        }

              

        return $this->render ('produit/produit.html.twig', ['produit'=>$produit]);


    }

    // récupérer des éléments(id, titre, contenu, created) dans le stockage des class, ici Article
    public function showAllProduits()
     {
        $entityManager = $this->getDoctrine()->getRepository(Produit::class);
        // on récupère tous les éléments via findAll dans un tableau nommé 'articles'
        $produits = $entityManager->findAll();

        
 
        //renvoi les éléments récupérés pour les afficher dans la page articles.html.twig
        return $this->render ('produit/produits.html.twig', ['produits'=>$produits]);
     }

     /**
    *  @Route("/admin_produitsListe", name="produitsListe")
    */
     public function produitsListe()
     {
        $entityManager = $this->getDoctrine()->getRepository(Produit::class);
        // on récupère tous les éléments via findAll dans un tableau nommé 'articles'
        $produits = $entityManager->findAll();

        return $this->render ('produit/admin_produitsListe.html.twig', ['produits'=>$produits]);

     }

    
     
     public function modifProduit(Request $request, Produit $produit)
     {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!empty($produit->getImage()))
            {
                $fileName = $this->generateUniqueFileName().'.jpeg';
                $file = new UploadedFile($produit->getImage(), $fileName);
            
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                $produit->setImage($fileName);

            }
            else {
                // $img= $produit->getImage();
                // $produit->setImage($img);
                $produit->setImage('6bca0f32d445f16e2a3e7730035a1e12.jpeg');
            }
            

            $em->flush();
            
        }

        return $this->render('produit/admin_modifProduit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
     }

     public function panier()
     {
         return $this->render('panier/panier.html.twig');
     }
 
     public function ajouter($id)
     {
     return $this->redirect($this->generateUrl('panier'));
     }
 
     public function supprimer($id)
     {
     return $this->redirect($this->generateUrl('ajouter'));
     }

}