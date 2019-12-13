<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
     * @Route("/",methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $book = new Book();
            $books = $this->getDoctrine()->getRepository(Book::class);

            if(count($request->query->all()) && $request->query->all()['category']){
                $books = $books->findBy(['category'=> $request->query->all()['category']]);
            }
            else{
                $books = $books->findAll();
            }
            return $this->render('books/index.html.twig',[
                'books' => $books,
                'bookCount'=> $book->getBookCount(new Session())
            ]);
        }catch (\Exception $exception){
            return $this->render('error.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * @Route("/book-create",name="book-create",methods={"GET","POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        try {
            $book = new Book();
            $book->setCreatedAt(new \DateTime("now"));
            $form = $this->createFormBuilder($book)
                ->add('category', ChoiceType::class,[
                    'choices'=>['Children'=>Book::CHILDREN_CATEGORY,'Fiction'=>Book::FICTION_CATEGORY],
                    'attr' => ['class'=> 'form-control']
                ])
                ->add('name',TextType::class,[
                    'attr' => ['class'=> 'form-control']
                ])
                ->add('author',TextType::class,[
                    'attr' => ['class'=> 'form-control']
                ])
                ->add('unit_price',TextType::class,[
                    'attr' => ['class'=> 'form-control']
                ])
                ->add('description',TextareaType::class,[
                    'attr' => ['class'=> 'form-control']
                ])
                ->add('save',SubmitType::class,[
                    'label'=> 'Save',
                    'attr'=>['class'=> 'btn btn-primary mt-3']
                ])
                ->getForm();
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();
                $entityManage = $this->getDoctrine()->getManager();
                $entityManage->persist($data);
                $entityManage->flush();
                return $this->redirect('/');
            }

            return $this->render('books/create.html.twig',[
                'book' => $book,
                'form' => $form->createView()
            ]);
        }catch (\Exception $exception){
            return $this->render('error.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }


    }

    /**
     * @Route(path="/book/{id}", name="book",methods={"GET","POST"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        try {
            $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
            $form = $this->createFormBuilder()
                ->add('qty',TextType::class,[
                    'attr' => [
                        'class'=> 'form-control'
                    ]
                ])
                ->add('save',SubmitType::class,[
                    'label'=> 'Add to cart',
                    'attr'=>[
                        'class'=> 'btn btn-primary mt-3'
                    ]
                ])
                ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();
                $session = new Session();
                $items = [];
                if($session->get('cart')){
                    $items = $session->get('cart');
                }
                array_push($items,
                    [
                        'id'=> $id,
                        'name'=> $book->getName(),
                        'author'=> $book->getAuthor(),
                        'unit_price' => $book->getUnitPrice(),
                        'category' => $book->getCategory(),
                        'price' => $book->getUnitPrice() * $data['qty'],
                        'qty' => $data['qty']
                    ]
                );
                $session->set('cart',$items );
                return $this->redirect('/');
            }
            return $this->render('books/show.html.twig',[
                'book' => $book,
                'form' => $form->createView()
            ]);
        }catch (\Exception $exception){
            return $this->render('error.html.twig', [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
