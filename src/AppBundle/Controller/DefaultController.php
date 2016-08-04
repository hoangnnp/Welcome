<?php

namespace AppBundle\Controller;
use AppBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //truyen du lieu cho view, view can hien thi first name, image, luu trong session
       $res_array=array();
        $res_array['isLogin']=false;
        $session=$request->getSession();
        if($session->has('firstname')){
            $res_array['isLogin']=true;
            $res_array['firstname']=$session->get('firstname');
            $res_array['lastname']=$session->get('lastname');
            $res_array['image']=$session->get('image');
        }
        return $this->render('default/index.html.twig',$res_array);
    }

    /**
     * @Route("/login", name="loginpage")
     */
    public function loginAction(Request $request)
    {
        $form=$this->createForm(LoginType::class);
        $form->handleRequest($request);
        if($form-> isSubmitted()){
            $login=$form->getData();
            //xu ly du lieu qua doctrine nếu c
            $repository = $this->getDoctrine()
            ->getRepository('AppBundle:User');
            $user=$repository->findOneBy(
                array('username'=>$login['username'],'password'=>$login['password'])
            );
            if($user!=null)
            {
                $session=$request->getSession();
                //khi ngươi dùng login thành công, sẽ lưu thông tin cơ bản vào session
                $session->set('firstname',$user->getFirstname());//lưu một giá trị có key là firstname và value là tên của user, lấy qua đối tượng $user
                $session->set('lastname',$user->getLastname());//lưu một giá trị có key là lastname và value là lastname của user, lấy qua đối tượng $user
                $session->set('image',$user->getImage());//lưu một giá trị có key là image và value là hình của user, lấy qua đối tượng $user
                $session->set('login',true);//lưu một giá trị có key là login và value là true
                $session->set('role',$user->getRole());
                $session->set('id',$user->getID());
               if($user->getRole()=="Normal")
                     return $this->redirectToRoute('homepage');
               else
                     return $this->redirectToRoute('adminpage');
            }
        }
        return $this->render('default/login.html.twig', [
            'form1'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/logout", name="logoutpage")
     */
    public function logoutAction(Request $request)
    {
        $session=$request->getSession();
        $session->clear();
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/profile", name="profilepage")
     */
    public function profileAction(Request $request)
    {
        $session = $request->getSession();//lấy đối tượng session ra, để thao tác trên sesion
        $uid = $session->get('id'); //lấy giá trị của session id ra
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:User');
        $user = $repository->findOneBy(array('id' => $uid));
        $res_array = array();
        $res_array['user'] = $user;
        return $this->render('default/profile.html.twig', $res_array);
    }
}

