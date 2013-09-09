<?php

namespace Knp\KnoodleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 

class DefaultController extends Controller
{
	private $pdo;
	
	private function getPdo()
	{
		if(null === $this->pdo)
			$this->pdo = new \PDO($this->container->getParameter('pdo_dsn'));
		
		return $this->pdo;
	}
	
	public function indexAction()
    {
		return new Response("Houston you can tell the world we have arrived");
	}
	
	public function surveyListAction()
	{
		$surveys = $this
					->getPdo()
					->query('SELECT * FROM survey ORDER BY created_at DESC')
					->fetchAll();
		
		return $this->render('KnpKnoodleBundle:Default:surveyList.html.twig', array('surveys' => $surveys));
		/*return new Response('<pre>'.print_r($surveys,true).'<pre>');*/
	}
	
	public function surveyShowAction($id)
	{
		$secured_id	=	intval($id);
		
		$survey = $this
					->getPdo()
					->query(sprintf('SELECT * FROM survey WHERE id=%d',$secured_id))
					->fetch();
				
		$questions = $this
					->getPdo()
					->query(sprintf('SELECT * FROM question WHERE survey_id=%d',$secured_id))
					->fetchAll();
					
		return $this->render('KnpKnoodleBundle:Default:surveyShow.html.twig',array('survey' => $survey,'questions' => $questions,'secured_id' => $secured_id));
	}
}