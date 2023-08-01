<?php

namespace App\Controller;
use App\Entity\Todo;
use App\Form\NewTodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
//    #[Route('/todo', name: 'app_todo')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $todoList = $entityManager->getRepository(Todo::class)->findAll();
/////////
//        $user = $this->getUser()->getEmail();
//        We can get user details using this
/////////
        return $this->render('todo/index.html.twig', [
            'todo_list' => $todoList,
        ]);
    }

    #[Route('/createTodoView', name: 'create_todo_view')]
    public function viewCreateTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $form = $this->createForm(NewTodoType::class,$todo);
        $form->handleRequest($request);
//        dump($form);
//        exit();
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $userId = $this->getUser();
            $todo->setName(
                $form->get('name')->getData()
            );
            $todo->setUserId($userId);

            $entityManager->persist($todo);
            $entityManager->flush();
            // do anything else you need here, like send an email
//        dump($user);
            $url = $this->generateUrl('app_todo');
            return $this->redirect($url);
        }
       return $this->render('/todo/createTodo.html.twig',['todoForm'=>$form]);

    }

    public function add(){

    }
    #[Route('/deleteTodo', name: 'delete_todo')]
    public function delete(Request $request, EntityManagerInterface $entityManager){
      dump($request);
      exit();
    }

    public function update(){

    }
}
