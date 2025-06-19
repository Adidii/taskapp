<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(TaskRepository $repo): JsonResponse
    {
        $tasks = $repo->findAll();
        $data = [];

        foreach ($tasks as $task) {
            $data[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);

        $em->persist($task);
        $em->flush();

        return $this->json(['message' => 'Tâche créée'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, TaskRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $task = $repo->find($id);
        if (!$task) return $this->json(['message' => 'Tâche non trouvée'], 404);

        $data = json_decode($request->getContent(), true);
        $task->setStatus($data['status']);
        $em->flush();

        return $this->json(['message' => 'Statut mis à jour']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, TaskRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $task = $repo->find($id);
        if (!$task) return $this->json(['message' => 'Tâche non trouvée'], 404);

        $em->remove($task);
        $em->flush();

        return $this->json(['message' => 'Tâche supprimée']);
    }
}
