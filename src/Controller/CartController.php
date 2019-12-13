<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;

class CartController extends AbstractController
{
    /**
     * @Route(path="/checkout/{coupon}", name="checkout",methods={"GET"},defaults={"coupon":""})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function checkout(Request $request)
    {
        try {
            $cart = new Cart();
            $session = new Session();
            $books = $session->get('cart');
            $coupon = $request->query->all() && $request->query->all()['form']['coupon']
                ?
                $request->query->all()['form']['coupon']
                :
                null;

            $form = $this->createFormBuilder()
                ->setMethod('GET')
                ->add('coupon',TextType::class,[
                    'attr' => [
                        'class'=> 'form-control'
                    ]
                ])
                ->add('save',SubmitType::class,[
                    'label'=> 'Add coupon',
                    'attr'=>[
                        'class'=> 'btn btn-primary mt-3'
                    ]
                ])
                ->getForm();

            $form->handleRequest($request);

            return $this->render('cart/checkout.html.twig',[
                'books' => $books,
                'discount' => number_format($cart->totalDiscount($books,$coupon),2),
                'sub_total' => number_format($cart->getItemSubTotal($books),2) ,

                'total'=> number_format($cart->getItemTotal($books),2) ,
                'form' => $form->createView()
            ]);
        }catch (Exception $exception){
            return $this->render('error.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * @Route(path="/remove-cart", name="remove-cart",methods={"GET"})
     */
    public function clearCart()
    {
        try {
            $session = new Session();
            $session->remove('cart');
            return $this->redirect('/');
        }catch (\Exception $exception){
            return $this->render('error.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
