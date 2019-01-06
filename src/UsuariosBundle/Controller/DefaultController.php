<?php

namespace UsuariosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UsuariosBundle\Entity\User;
use UsuariosBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;      //Esto para el uso de la propiedad @Security("has_role('ROLE_SUPER_ADMIN')") en la confAction()

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('@Usuarios/Default/index.html.twig');
    }
    
    /**
     * @Route("/admin/", name="admin_zone")
     */
    public function adminAction()
    {
        return $this->render('@Usuarios/Default/index.html.twig');
    }
    
    /**
    * @Security("has_role('ROLE_SUPER_ADMIN')")
    * @Route("/admin/conf", name="config_zone")
    */
    public function confAction()        //Hay que recordar importar la librería use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    {
            return $this->render('@Usuarios/Default/conf.html.twig');
    }
    
    /**
     * @Route("/usuarios", name="usuarios")
     */
    public function usuariosAction()
    {
//        return $this->render('@Usuarios/Default/usuarios.html.twig');  //ASÍ ESTABA INICIALMENTE ESTA ACTION SOLO CON ESTA LÍNEA
        
        
        // whatever *your* User object is
      $user = new User();
      $plainPassword = '1234';
      $encoder = $this->container->get('security.password_encoder');
      $encoded = $encoder->encodePassword($user, $plainPassword);
      $user->setPassword($encoded);
      $user->setUserName("paco");
      $roles = ["ROLE_ADMIN","ROLE_SUPER_ADMIN"];
      $user->setRoles($roles);
      $user->setEmail("paco@mail.com");
      
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();
      return $this->render('@Usuarios/Default/admin.html.twig');
        
        
    }
    
/**
     * @Route("/register", name="user_registration")
     */
    //ASÍ ESTABA ESTA LÍNEA ORIGINALMENTE COPIADA DESDE LA DOCUMENTACIÓN DE SYMFONY RESPECTO AL REGISTRO DE USUARIOS
    //public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            //$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());       //ASÍ ESTABA ESTA LÍNEA ORIGINALMENTE COPIADA DESDE LA DOCUMENTACIÓN DE SYMFONY RESPECTO AL REGISTRO DE USUARIOS
            $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);      //AQUÍ EL CONTENIDO DE ESTA VARIABLE $password SE GUARDA EN LA BASE DE DATOS

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            
            //ASÍ ESTABA ORIGINALMENTE
            //return $this->redirectToRoute('replace_with_some_route');
            
            //para esto hay que agregar la biblioteca del objeto Response
            return new Response("Usuario registrado");
        }

        return $this->render(
            //ASÍ ESTABA ORIGINALMENTE
            //'registration/register.html.twig',
                //'UsuariosBundle:Default:register.html.twig',      //ASÍ ES EN VERSIONES 2.x DE SYMFONY
                '@Usuarios/Default/register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    
    //ASÍ ESTABA INICIALMENTE
//    /**
//     * @Route("/usuarios/login", name="login")
//     */
    
    
    /**
     * @Route("/admin/login/", name="login")
     */       
    public function loginAction(Request $request)
    {
        
        $authenticationUtils = $this->get('security.authentication_utils');
        
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('@Usuarios/Default/login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
    ));
        
    }
}
