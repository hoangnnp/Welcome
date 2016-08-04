<?php

namespace AppBundle\Controller;

use AppBundle\Form\EditType;
use AppBundle\Entity\User;
use AppBundle\Form\LoginType;
use AppBundle\Form\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class AdminController extends Controller
{
    public function CheckLogin(Session $se)
    {
        if(!$se->has('login'))
        {
            return $this->render('error/errorlogin.html.twig');
        }
        if($se->get('role')=='Normal')
        {
            $ar=array();
            $ar['firstname'] = $se->get('firstname');
            $ar['lastname']=$se->get('lastname');
            return $this->render('error/errorlimit.html.twig',$ar);
        }
    }
    /**
     * @Route("/admin", name="adminpage")
     */
    public function adminAction(Request $request)
    {
        //kiêm tra xem trong session có thông tin chưa, có nghĩa là đã đăng nhập thành công, chưa là chưa đăng nhập
        $res_array = array();//tạo array rỗng để chứa các dữ liệu cần gửi cho view
        $se = $request->getSession();//Lấy kho chứa các session


        if(!$se->has('login'))//kiểm tra xem trong session có lưu một giá trị có key là login chưa
        {
            return $this->render('error/errorlogin.html.twig');
        }
        if($se->get('role')=='Normal')
        {
            $ar=array();
            $ar['firstname']=$se->get('firstname');
            $ar['lastname']=$se->get('lastname');
            return $this->render('error/errorlimit.html.twig',$ar);
        }
        //lấy tất cả user va bo vo res_array

        $form=$this->createForm(CreateType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $create = $form->getData();
            $user = new User();
            $user->setUsername($create['username']);
            $user->setPassword($create['password']);
            $user->setEmail($create['email']);
            $user->setAddress($create['address']);
            $user->setBirthday($create['birthday']);
            $user->setFirstname($create['firstname']);
            $user->setLastname($create['lastname']);
            $user->setHomephone($create['homephone']);
            $user->setImage($create['image']);
            $user->setRole($create['role']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        $res_array['form']=$form->createView();

        $res_array['image']=$se->get('image');
        $res_array['firstname']=$se->get('firstname');
        $res_array['lastname']=$se->get('lastname');

        $repository=$this->getDoctrine()->getRepository('AppBundle:User');
        $users=$repository->findAll();
        $res_array['users']=$users;


        return $this->render('default/admin.html.twig',$res_array);
    }
    /**
     * @Route("/admin/create",name="createpage")
     */
    public function createAction(Request $request)
    {
        $se = $request->getSession();//Lấy kho chứa các session
        //cái hàm has này nó chỉ kiểm tra là có key login ko thôi chứ chưa lấy giá trị của key đó
        if(!$se->has('login'))//kiểm tra xem trong session có lưu một giá trị có key là login chưa
        {
            return $this->render('error/errorlogin.html.twig');
        }
        if($se->get('role')=='Normal')
        {
            $ar=array();
           $ar['firstname'] = $se->get('firstname');
           $ar['lastname']=$se->get('lastname');
            return $this->render('error/errorlimit.html.twig',$ar);
        }
        $form=$this->createForm(CreateType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $create = $form->getData();
            $user = new User();
            $user->setUsername($create['username']);
            $user->setPassword($create['password']);
            $user->setEmail($create['email']);
            $user->setAddress($create['address']);
            $user->setBirthday($create['birthday']);
            $user->setFirstname($create['firstname']);
            $user->setLastname($create['lastname']);
            $user->setHomephone($create['homephone']);
            $user->setImage($create['image']);
            $user->setRole($create['role']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        $res_array=array('form'=>$form->createView());
        $res_array['image']=$se->get('image');
        $res_array['firstname']=$se->get('firstname');
        $res_array['lastname']=$se->get('lastname');
        return $this->render('default/create.html.twig',$res_array);
    }

    /**
     * @Route("/admin/delete/{id}",name="deletepage")
     */
    public function deleteAction($id)
    {
        var_dump($id);
        $em=$this->getDoctrine()->getManager();
        $user = $em->getPartialReference('AppBundle\Entity\User', array('id' => $id));
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("adminpage");
    }
    /**
     * @Route("/admin/edit/{id}",name="editpage")
     */
    public function editAction($id, Request $request)
    {
        $se = $request->getSession();
        if(!$se->has('login'))
        {
            return $this->render('error/errorlogin.html.twig');
        }
        if($se->get('role')=='Normal')
        {
            if($se->get('id')!=$id) {
                $ar = array();
                $ar['firstname'] = $se->get('firstname');
                $ar['lastname'] = $se->get('lastname');
                return $this->render('error/errorlimit.html.twig', $ar);
            }
        }
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user=$repository->findOneBy(
            array('id'=>$id)
        );
        $form=$this->createForm(EditType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //form handle
            $userData = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($userData);
            $em->flush();
            if($se->get('role')=='Normal')
            {
                return $this->redirectToRoute('profilepage');
            }
            return $this->redirectToRoute('adminpage');
        }
        $res_array=array('form'=>$form->createView(),'errors'=>$form->getErrors());
        $res_array['image']=$se->get('image');
        $res_array['firstname']=$se->get('firstname');
        $res_array['lastname']=$se->get('lastname');
        return $this->render('default/edit.html.twig',$res_array);
    }


}