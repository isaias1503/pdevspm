<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\Projects;
use AppBundle\Form\Type\ProjectsType;
use AppBundle\Entity\Users;
use AppBundle\Entity\ProjectsTypes;
use AppBundle\Entity\ProjectsStatus;

/**
 * Projects controller.
 *
 * @Route("/projects")
 */
class ProjectsController extends Controller
{
    /**
     * Lists all Projects entities.
     *
     * @Route("/showList", name="projects_index")
     * @Method("GET")
     */
    public function mainIndexProjectsAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Projects')->findAll();
        
        return $this->render('projects/index.html.twig', ['entity' => $entities]);
    }
    /**
     * Lists all Projects entities.
     *
     * @Route("/list", name="projects_list")
     * @Method("GET")
     */
    public function indexListAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Projects')->findAll();
        
        return $this->render('projects/show.html.twig', ['entity' => $entities]);
    }
    /**
     * Creates a new Projects entity.
     *
        * @Route("/create", name="projects_create")
     */
    public function createAction()
    {
        $entity = new Projects();
        //$user = new Users();
        
        $repository = $this->getDoctrine()
        ->getRepository('AppBundle:Users');
        
        $user = $repository->findAll();
        
        $form = $this->createForm(new ProjectsType(), $entity, ['method' => 'POST', 'action' => $this->generateUrl('projects_create')]);
                
        return $this->render('projects/new.html.twig', ['form' => $form->createView(), 'users' => $user]);    
                
    }
    /**
     * Process the Form of Projects
     *
     * @Route("/create/process", name="projects_process")
     * @Method("POST") 
     */
    public function processCreateAction(Request $request) {
        
        if ($request->getMethod() == 'POST') {
            
            $em = $this->getDoctrine()->getManager();           
                //Set the objects.
            $projects = new Projects();
                //Get the data from Field ProjectType
               $idProjectType = $request->request->get('projects')
                       ['projectsTypes'];            
                //Get the data from Field ProjectStatus
               $idProjectsStatus = $request->request->get('projects')
                       ['projectsStatus'];          
                //++++++++++++++Getting the users id from Checkbox++++++++++++++
               $optionsSelected = $request->request->get('projects')['team'];           
               $this->convertArrayIdUsers($request, $optionsSelected);            
               $options2 = implode("," , $optionsSelected); 
               $projects->setTeam($options2);
                //++++++++++++++Getting the users id from Checkbox++++++++++++++            
                $idProjectsTypeItem = $this->getDoctrine()
                        ->getRepository('AppBundle:ProjectsTypes') 
                        ->find($idProjectType);            
                $idProjectStatusItem = $this->getDoctrine()
                        ->getRepository('AppBundle:ProjectsStatus') 
                        ->find($idProjectsStatus);           
                $projects->setName($request->request->get('projects')['name']);                        
                $projects->setDescription($request->request->get('projects')
                        ['description']);
                $projects->setCreatedAt(new \DateTime($request->request->
                        get('createdAt')));
                $projects->setOrderTasksBy('TEXTO_PLANO');
                $projects->setProjectsStatus($idProjectStatusItem);          
                $projects->setProjectsTypes($idProjectsTypeItem);            
                $em->persist($projects);            
                $em->flush();            
                $url = $this->generateUrl('projects_process');
                
        }//End IF
        
                $repository = $this->getDoctrine()
                            ->getRepository('AppBundle:Projects');
                $projects = $repository->findAll();    
                return $this->render('projects/show.html.twig', 
                ['entity' => $projects]);
            
    }//End Function
    
    /**
     * Convert the Array collection to String values separates with comma. 
     *
     */
    public function convertArrayIdUsers(Request $request, Array $optionsSelected) {
        
            foreach ($optionsSelected as $value) {
                
              if(is_object($value)){                  
                $coma = ',';                
                $optionsSelected .= $coma.$value;                  
              }//End IF
            }//End Foreach 
    }//End Function    
    /**
     * Displays a form to create a new Projects entity.
     *
     * @Route("/new", name="projects_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new Projects();
        
        $form   = $this->createCreateForm($entity);
        
        return $this->render('projects/new.html.twig' , ['form' => $form]);
    }//End Function newAction()
    /**
     * Finds and displays a Projects entity.
     *
     * @Route("/{id}", name="projects_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()
                    ->getRepository('AppBundle:Projects');
        
        $projects = $repository->find($id);
                       
        //$entity = $em->getRepository('AppBundle:Projects')->find($id);

        if (!$projects) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }                   
                
        return $this->render('projects/show.html.twig', ['entity' => $projects]);
                
    }

    /**
     * Displays a form to edit an existing Projects entity.
     *
     * @Route("/{id}/edit", name="projects_edit")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager(); 
        $projects = $em->getRepository('AppBundle:Projects')->find($id);
        /*Its created the form view of Users with path containing the view*/
        $form = $this->createForm(new ProjectsType(), $projects);
        
        $info = $form->getData();
        
        return $this->render('projects/edit.html.twig', ['form' => $form->createView(),'id_project'=>$projects->getId()]); 
    }
    
    /**
     * This function update the new information.  
     * @Route("/{id}/update/project", name="add_edit_project")        
     */
    public function processEditProjectAction($id, Request $request){        
        
        $em = $this->getDoctrine()->getManager(); 
        $projects = $em->getRepository('AppBundle:Projects')->find($id);
        
        $repository = $this->getDoctrine()
        ->getRepository('AppBundle:Projects');
        
        if($projects) {
            
            //Get the data from Field ProjectType
            $idProjectType = $request->request->get('projects')['projectsTypes'];
            
            //Get the data from Field ProjectStatus
            $idProjectsStatus = $request->request->get('projects')['projectsStatus'];
            
            $optionsSelected = $request->request->get('projects')['team'];
            
            $this->transformEdit($optionsSelected);
                      
            $idProjectsTypeItem = $this->getDoctrine()
                    ->getRepository('AppBundle:ProjectsTypes') 
                    ->find($idProjectType);
            $idProjectStatusItem = $this->getDoctrine()
                    ->getRepository('AppBundle:ProjectsStatus') 
                    ->find($idProjectsStatus);
            $projects->setName($request->request->get('projects')['name']);
            $projects->setDescription($request->request->get('projects')['description']);
            //$projects->setTeam('textoPlano');
                     
            $projects->setCreatedAt(new \DateTime($request->request->get('createdAt')));
                                   
            $projects->setOrderTasksBy('TEXTO_PLANO');
            $projects->setProjectsStatus($idProjectStatusItem);          
            $projects->setProjectsTypes($idProjectsTypeItem);
            
            $em->persist($projects);
            
            $em->flush();
                       
        }
 
        $projects = $repository->findAll();
        
        return $this->redirect($this->generateUrl('projects_index'));          
        
    }//End Function
    
    /**
     * Transforms a string to an ArrayCollection
     *
     * @param string $value
     *
     * @return ArrayCollection
     */
    public function transformEdit(Request $request, string $value)
    {
        $users = new ArrayCollection();
        $projects = new Projects();
 
        if ( null === $value ) return $users;
 
        $data = preg_split( ',', $value, -1, PREG_SPLIT_NO_EMPTY );
        
        foreach ( $data as $ids ) {
            if ( null === ( $User = $this->om->getRepository('AppBundle:Users')->find($ids) ) ) {
                $User = new Users();
                $projects->setName($request->request->get('projects')['team']);
                $this->om->persist($User);
            }
            $users->add($User);
        }
        $this->om->flush();
 
        return $users;
    }//End Function. 
    
    /**
     * Displays all list of users exist in the entity Users
     *
     * @Route("/all/users", name="projects_users_list")
     */
    public function renderListUsers(){
        
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('AppBundle:Users')->findAll();
        
        return $this->render('projects/listUsers.html.twig', ['users' => $users]);        
    }
    

    /**
    * Creates a form to edit a Projects entity.
    *
    * @param Projects $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Projects $entity)
    {
        $form = $this->createForm(new ProjectsType(), $entity, array(
            'action' => $this->generateUrl('projects_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Projects entity.
     *
     * @Route("/{id}", name="projects_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Projects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Projects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('projects_edit', array('id' => $id)));
        }

        return $this->render('projects/new.html.twig', ['entity' => $entity->createView(), 
            'edit_form'   => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),]);
    }
    /**
     * Deletes a Projects entity.
     *
     * @Route("/{id}/delete", name="projects_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $project = $em->getRepository('AppBundle:Projects')->find($id);

        if (!$project) {
                throw $this->createNotFoundException('Dont find Project, any matches for this ID');
            }//End IF
            
            $em->remove($project);
            $em->flush();   
            
        return $this->redirect($this->generateUrl('projects_index'));
    }

    /**
     * Creates a form to delete a Projects entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projects_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }//End Function createDeleteForm
    
}
