<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskPageController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function index(TaskRepository $repo): Response
    {
        return $this->render('task_page/index.html.twig', [
            'tasks' => $repo->findAll(),
        ]);
    }

    #[Route('/tasks/add', name: 'task_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $task->setTitle($request->request->get('title'));
        $task->setDescription($request->request->get('description'));
        $task->setStatus($request->request->get('status'));

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }
}
