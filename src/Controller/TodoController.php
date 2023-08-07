<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\EditTodoType;
use App\Form\NewTodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends AbstractController
{
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $todoForm = $this->createForm(NewTodoType::class, $todo);
        $todoForm->handleRequest($request);
        if ($todoForm->isSubmitted() && $todoForm->isValid()) {
            $userId = $this->getUser();
            $todo->setName(
                $todoForm->get('name')->getData()
            );
            $todo->setUserId($userId);

            $entityManager->persist($todo);
            $entityManager->flush();

            unset($todo);
            unset($todoForm);
            $todo = new Todo();
            $todoForm = $this->createForm(NewTodoType::class, $todo);

        }
        $userId = $this->getUser()->getId();
        $todoList = $entityManager->getRepository(Todo::class)->findBy(array('userId' => $userId));
        return $this->render('todo/index.html.twig', [
            'todo_list' => $todoList,
            'todoForm' => $todoForm
        ]);

    }
    public function deleteTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todoId = $request->get("todoId");
        $todo = $entityManager->getRepository(Todo::class)->findOneBy(array('id' => $todoId));

        $entityManager->remove($todo);
        $entityManager->flush();
        $url = $this->generateUrl('app_todo');
        return $this->redirect($url);
    }
    public function viewEditTodo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $todo->setName($request->get("todoName"));
        $todo->setStatus($request->get("todoStatus"));

        $form = $this->createForm(EditTodoType::class, $todo,[
            'action'=>$this->generateUrl('update_todo')
        ]);
        $form->handleRequest($request);
        return $this->render('todo/editTodo.html.twig', [
            'todo' => $todo,
            'todoId'=>$request->get('todoId'),
            'editTodoForm' => $form
        ]);
    }
    public function updateTodo(Request $request, EntityManagerInterface $entityManager):Response{

        $todo = new Todo();
        $form = $this->createForm(EditTodoType::class, $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $todoId = $request->get("todoId");

            $checkTodo= $entityManager->getRepository(Todo::class)->findOneBy(array('id' => $todoId));
            if (!$checkTodo) {
                throw $this->createNotFoundException(
                    'No todo found for id ' . $checkTodo
                );
            }

            $checkTodo->setStatus($form->getData()->getStatus());
            $checkTodo->setName($form->getData()->getName() );
            $entityManager->flush();
        }
        $url = $this->generateUrl('app_todo');
        return $this->redirect($url);
}
}
