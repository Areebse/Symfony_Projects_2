<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\NewTodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TodoController extends AbstractController
{
//    #[Route('/todo', name: 'app_todo')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $todoList = $entityManager->getRepository(Todo::class)->findBy(array('userId' => $userId));
/////////
//        $user = $this->getUser()->getEmail();
//        We can get user details using this
/////////
        return $this->render('todo/index.html.twig', [
            'todo_list' => $todoList,
        ]);
    }

//    #[Route('/createTodo', name: 'create_todo')]
    public function createTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $form = $this->createForm(NewTodoType::class, $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $userId = $this->getUser();
            $todo->setName(
                $form->get('name')->getData()
            );
            $todo->setUserId($userId);

            $entityManager->persist($todo);
            $entityManager->flush();
            $url = $this->generateUrl('app_todo');
            return $this->redirect($url);
        }
        return $this->render('/todo/createTodo.html.twig', ['todoForm' => $form]);

    }

//    #[Route('/deleteTodo', name: 'delete_todo')]
    public function deleteTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todoId = $request->get("todoId");
        $todo = $entityManager->getRepository(Todo::class)->findOneBy(array('id' => $todoId));

        $entityManager->remove($todo);
        $entityManager->flush();
        $url = $this->generateUrl('app_todo');
        return $this->redirect($url);
    }

//    #[Route('/updateTodo', name: 'update_todo')]
    public function updateTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todoId = $request->get("todoId");
        $todo = $entityManager->getRepository(Todo::class)->findOneBy(array('id' => $todoId));
        if (!$todo) {
            throw $this->createNotFoundException(
                'No todo found for id ' . $todoId
            );
        }
        $todo->setStatus(true);
        $entityManager->flush();
        $url = $this->generateUrl('app_todo');
        return $this->redirect($url);
    }
}
