<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Courses;
use App\Entity\Students;
use App\Repository\CoursesRepository;

class CoursesController extends AbstractController
{
    /**
     * @Route("/courses", name="courses")
     */
    public function index(CoursesRepository $coursesRepository): Response {   
        $courses = $coursesRepository -> findAll();        

        return $this->render('courses/index.html.twig', [
            'controller_name' => 'CoursesController',
            'courses' => $courses,
        ]);
    }

    /**  
    * @Route("/courses/add/", name="courses_add")
    */
   
    public function add(EntityManagerInterface $em): Response {

        $course = new Courses();
        $course->setName('Curso de Inglés');
        $course->setLanguage('Inglés');
        $course->setLevel(4);

        $course2 = new Courses();
        $course2->setName('Curso de Chino');
        $course2->setLanguage('Chino');
        $course2->setLevel(2);

        $student = new Students();
        $student->setName('Angela');
        $student->setAge(17);

        $course->addStudent($student);

        $em->persist($course);
        $em->persist($course2);
        $em->persist($student);

        $em->flush();

        return $this->render('courses/index.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/courses/{id}", name="courses_show")
     */
    public function show($id, CoursesRepository $coursesRepository): Response
    {        
        $course = $coursesRepository->find($id);
        
        return $this->render('courses/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/courses/filter/{language}", name="courses_filter")
     */
    public function filter($language, CoursesRepository $coursesRepository): Response {   
        $courses = $coursesRepository -> findByLanguage($language);        

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }
}
