<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends Controller
{
    public function panier(Request $request)
    {
        $session = $this->$request->getSession();
        if (!$session->has('panier')) $session->set('panier', array());
        
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository(Produit::class)->findArray(array_keys($session->get('panier')));
        
        return $this->render('panier/panier.html.twig', array('produits' => $produits,'panier' => $session->get('panier')));
    }

    public function ajouter($id, Request $request)
     {
        $session = $this->$request->getSession();
        
        if (!$session->has('panier')) 
        {
            $session->set('panier',array());
        }
        else
        {
            $panier = $session->get('panier');
        }
        
        if (array_key_exists($id, $panier)) {
            if ($this->$request->query->get('qte') != null) 
            {
                $panier[$id] = $this->$request->query->get('qte');
            }
            else
            {
                $this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
            }
        } 
        else 
        {
            if ($this->$request->query->get('qte') != null)
                $panier[$id] = $this->$request->query->get('qte');
            else
                $panier[$id] = 1;
            
            $this->get('session')->getFlashBag()->add('success','Article ajouté avec succès');
        }
            
        $session->set('panier',$panier);
                
        return $this->redirect($this->generateUrl('panier'));
     }
 
     public function supprimer($id)
     {
     return $this->redirect($this->generateUrl('ajouter'));
     }
}
