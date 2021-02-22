<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaludoController extends AbstractController {

    /**  
    * @Route("/hello", name="hello")
    */
   
    public function hello(): Response {
        $name = "Angela";

        return new Response('<html><body>Hello, '.$name.'</body></html>');
    }

    /**  
    * @Route("/bye", name="bye")
    */
   
    public function goodbye(): Response {
        $name = "Angela";

        return new Response('<html><body>Bye, '.$name.'</body></html>');
    }

    /**  
    * @Route("/employees/edit/{id}", name="employees_edit", requirements={"id"="\d+"})
    */
   
    public function edit($id): Response {

        return new Response("<html><body>Editing \"employee\": $id </body></html>");
    }

    /**  
    * @Route("/employees/list", name="employees_list")
    */
   
    public function orderList(Request $request): Response {

        // if(isset($_GET['orderby'])) {
        //     $orderBy = $_GET['orderby'];
        // } else {
        //     $orderBy = 'name';
        // }

        $orderBy = $request->query->get("orderby", "name"); 
        $page = $request->query->get("page", 1);
        
        ///employees/list?orderby=name&page=2         
        
        return new Response("<html><body>List ordered by: $orderBy, page: $page</body></html>");
    }

    /**  
    * @Route("/employees/list2", name="employees_list2")
    */
    
    public function orderList2(Request $request): Response {

        // if(isset($_GET['orderby'])) {
        //     $orderBy = $_GET['orderby'];
        // } else {
        //     $orderBy = 'name';
        // }

        $orderBy = $request->query->get("orderby", "name"); 
        $page = $request->query->get("page", 1);
        
        ///employees/list2?orderby=name&page=2
        
        $people = [
            ['name' => 'Carlos', 'email' => 'carlos@correo.com', 'age' => 20, 'city' => 'Benalmádena'],
            ['name' => 'Carmen', 'email' => 'carmen@correo.com', 'age' => 15, 'city' => 'Fuengirola'],
            ['name' => 'Carmelo', 'email' => 'carmelo@correo.com', 'age' => 17, 'city' => 'Torremolinos'],
            ['name' => 'Carolina', 'email' => 'carolina@correo.com', 'age' => 18, 'city' => 'Málaga'],
        ];
        
        return new JsonResponse($people);
    }
}