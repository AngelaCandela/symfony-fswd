<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Form\CoursesType;
use App\Repository\CoursesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/courses")
 */
class ApiCoursesController extends AbstractController
{
    /**
     * @Route("/", name="api_courses_index", methods={"GET"})
     */
    public function index(CoursesRepository $coursesRepository): Response
    {   
        $coursesArray = [];
        
        $courses = $coursesRepository-> findAll();

        foreach($courses as $course){

            $studentsArray = [];

            $students = $course->getStudents();

            foreach($students as $student){
                $studentObj = [
                    'id' => $student->getId(),
                    'name' => $student->getName(),
                    'age' => $student->getAge(),
                ];
                $studentsArray[] = $studentObj; // array_push($studentsArray, $studentObj)
            }

            $courseObj = [
                'id' => $course->getId(),
                'name' => $course->getName(),
                'language' => $course->getLanguage(),
                'level' => $course->getLevel(),
                'students' => $studentsArray,
            ];

            $coursesArray[] = $courseObj;
        }

        return new JsonResponse($coursesArray);
    }

    /**
     * @Route("/new", name="api_courses_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {   
        $bodyRequest = $request->getContent();
        $courseObj = json_decode($bodyRequest);

        $course = new Courses();
        $course->setName($courseObj->name);
        $course->setLanguage($courseObj->language);
        $course->setLevel($courseObj->level);

        $em->persist($course);
        $em->flush();

        $reply = [
            'id' => $course->getId(),
            'name' => $course->getName(),
            'language' => $course->getLanguage(),
            'level' => $course->getLevel()
        ];

        return new JsonResponse($reply);
    }

    /**
     * @Route("/{id}", name="api_courses_show", methods={"GET"})
     */
    public function show($id, CoursesRepository $coursesRepository): Response
    {   
        $course = $coursesRepository->find($id);

        if($course === null) {
            throw $this->createNotFoundException('This course does not exist');
        }

        $studentsArray = [];

        $students = $course->getStudents();

        foreach($students as $student){
            $studentObj = [
                'id' => $student->getId(),
                'name' => $student->getName(),
                'age' => $student->getAge(),
            ];
            $studentsArray[] = $studentObj; // array_push($studentsArray, $studentObj)
        }


        $courseObj = [
            'id' => $course->getId(),
            'name' => $course->getName(),
            'language' => $course->getLanguage(),
            'level' => $course->getLevel(),
            'students' => $studentsArray,
        ];

        return new JsonResponse($courseObj);
    }

    /**
     * @Route("/{id}/edit", name="api_courses_edit", methods={"PUT"})
     */
    public function edit($id, Request $request, CoursesRepository $coursesRepository, EntityManagerInterface $em): Response
    {
        $bodyRequest = $request->getContent();
        $courseObj = json_decode($bodyRequest);

        $course = $coursesRepository->find($id);
        $course->setName($courseObj->name);
        $course->setLanguage($courseObj->language);
        $course->setLevel($courseObj->level);

        $em->persist($course);
        $em->flush();

        $reply = [
            'id' => $course->getId(),
            'name' => $course->getName(),
            'language' => $course->getLanguage(),
            'level' => $course->getLevel()
        ];

        return new JsonResponse($reply);        
    }

    /**
     * @Route("/{id}", name="api_courses_delete", methods={"DELETE"})
     */
    public function delete($id, Request $request, CoursesRepository $coursesRepository, EntityManagerInterface $em): Response
    {
        $course = $coursesRepository->find($id);

        $em->remove($course);
        $em->flush();

        $reply = [
            'message' => 'Course deleted successfully!'
        ];

        return new JsonResponse($reply);
    }
}
