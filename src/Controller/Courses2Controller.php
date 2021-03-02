<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Courses;
use App\Entity\Students;
use App\Repository\Courses2Repository;

class Courses2Controller extends AbstractController
{
    /**
     * @Route("/courses2", name="courses2")
     */
    public function index(Courses2Repository $courses2Repository): Response {   
        $courses = $courses2Repository -> findAll();        

        return $this->render('courses2/index.html.twig', [
            'controller_name' => 'Courses2Controller',
            'courses' => $courses,
        ]);
    }

    /**  
    * @Route("/courses2/add/", name="courses2_add")
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

        return $this->render('courses2/index.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    /**
     * @Route("/courses2/{id}", name="courses2_show", requirements={"id"="\d+"})
     */
    public function show($id, Courses2Repository $courses2Repository): Response
    {        
        $course = $courses2Repository->find($id);
        
        return $this->render('courses2/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/courses2/edit/{id}", name="courses2_edit", requirements={"id"="\d+"})
     */
    public function edit($id, Courses2Repository $courses2Repository, EntityManagerInterface $em): Response
    {        
        $course = $courses2Repository->find($id);

        $course -> setLevel(1);
        $course -> setLanguage('Francés');
        $em -> persist($course);
        $em -> flush();
        
        return $this->render('courses2/edit.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/courses2/delete/{id}", name="courses2_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Courses2Repository $courses2Repository, EntityManagerInterface $em): Response
    {        
        $course = $courses2Repository->find($id);

        $em -> remove($course);
        $em -> flush();
        
        return new Response('Course deleted');
    }


    /**
     * @Route("/courses2/filter/{language}", name="courses2_filter")
     */
    public function filter($language, Courses2Repository $courses2Repository): Response {

        $courses = $courses2Repository -> findByLanguage($language);
        // SELECT * FROM Courses WHERE language = 'Chino';

        $course = $courses2Repository -> findOneByLanguage($language);
        // SELECT * FROM Courses WHERE language = 'Chino' LIMIT 1;

        $tenCourses = $courses2Repository -> findBy(['language' => $language, 'level' => 2], ['name' => 'ASC'], 10, 0);        
        // SELECT * FROM Courses WHERE language = 'Chino' AND level = 2 ORDER BY name ASC LIMIT 10 SKIP 0;

        return $this->render('courses2/index.html.twig', [
            'courses' => $courses,
        ]);
    }
}
