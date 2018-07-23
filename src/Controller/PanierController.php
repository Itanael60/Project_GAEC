<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends Controller
{
    public function panier(Request $request)
    {
        $session = $request->getSession();

        // $session->remove('panier');
        if (!$session->has('panier')) $session->set('panier', array());
        
        
        $produits = (empty($session->get('panier'))) ? array() : $session->get('panier');
        return $this->render('panier/panier.html.twig', array('produits' => $produits,'panier' => $session->get('panier')));
    }

    public function ajouter($id, Request $request)
     {
        $session = $request->getSession();
        if (empty($session->get('panier')))
            $panier = array();
        else
            $panier = $session->get('panier');
        
        $panier = (is_array($panier))?$panier:array();
        
        if (array_key_exists($id, $panier)) {
            $panier[$id]["qte"]++;
            $session->set('panier', json_encode($panier));
            $this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
        } 
        else 
        {
            // Recuperation du produit
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository(Produit::class)->findOneBy(array("id" => $id));
            $panier[$id] = array("id" => $id, "qte" => 1, "produit" => $produit->getProduit(), "detail" => $produit->getDetail(), "prixHT" => $produit->getPrixHT(),"image" => $produit-> getImage());
            
            $session->set('panier', json_encode($panier));
            $this->get('session')->getFlashBag()->add('success','Article ajouté avec succès');
        }
        // var_dump($panier[$id]);
        // die();
        $session->set('panier',$panier);
                
        return $this->redirect($this->generateUrl('panier'));
     }
 
    public function supprimer(Request $request, $id)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        
        //    var_dump($panier[$id]);
        //    die();

        if (array_key_exists($id, $panier)) {
            unset($panier[$id]);
            $this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
            // var_dump($panier);
            // die();
            $panier=$session->set('panier', $panier);
        } 
            
        return $this->redirect($this->generateUrl('panier'));
            
    }

    public function supprimerPanier(Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        
        $session->remove('panier');
        return $this->redirect($this->generateUrl('panier'));
            
    }


    public function plusProduit(Request $request, $id)
    {
        
        $session = $request->getSession();
        $panier = $session->get('panier');
        $panier[$id]["qte"]++;
        $panier=$session->set('panier', $panier);
        // var_dump($panier[$id]);
        // die();    
                
        return $this->redirect($this->generateUrl('panier'));
    }
    public function moinsProduit(Request $request, $id)
    {
        
        $session = $request->getSession();
        $panier = $session->get('panier');
        $panier[$id]["qte"]--;
        $panier=$session->set('panier', $panier);
        // var_dump($panier[$id]);
        // die();    
                
        return $this->redirect($this->generateUrl('panier'));
    }

    public function ajouterQte($id, Request $request)
     {
        dump($request);
        die();
        $form->handleRequest($request);

        $session = $request->getSession();
        if (empty($session->get('panier')))
            $panier = array();
        else
            $panier = $session->get('panier');
        
        $panier = (is_array($panier))?$panier:array();
        
        if (array_key_exists($id, $panier)) {
            $panier[$id]["qte"] += $qte;
            $session->set('panier', json_encode($panier));
            
        } 
        else 
        {
            // Recuperation du produit
            $em = $this->getDoctrine()->getManager();
            $produit = $em->getRepository(Produit::class)->findOneBy(array("id" => $id));
            $panier[$id] = array("id" => $id, "qte" => 1, "produit" => $produit->getProduit(), "detail" => $produit->getDetail(), "prixHT" => $produit->getPrixHT(),"image" => $produit-> getImage());
            
            $session->set('panier', json_encode($panier));
        }
        // var_dump($panier[$id]);
        // die();
        $session->set('panier',$panier);
                
        return $this->redirect($this->generateUrl('panier'));
     }

}
