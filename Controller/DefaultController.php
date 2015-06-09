<?php
namespace Cogitoweb\PentahoProxyBundle\Controller;

use Exception;
use Cogitoweb\PentahoProxyBundle\DependencyInjection\Pentaho;
use Cogitoweb\PentahoProxyBundle\Entity\Db;
use Cogitoweb\PentahoProxyBundle\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator;

class DefaultController extends Controller {
	public function proxyAction($id) {
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			throw $this->createNotFoundException('The id ' . $id . ' does not exist');
		}
		
		$validator = $this->get('validator');
		/* @var $validator Validator */
		
//		$db->setPassword('');
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($db);
//		$em->flush();
		
		$className = $report->getClass();
		$class = new $className($report->getParams());
		print('ID: ' . $class->getProgettoId());
		return new Response();
		
//		$validator->validate();
		
		$db = $report->getDb();
		/* @var $db Db */
		
//		$db->setPassword('');
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($db);
//		$em->flush();
		
		$pentaho = new Pentaho(
			$db->getHost(),
			$db->getUsername(),
			$db->getPassword()
		);
		
		parse_str($report->getParams(), $reportParams);
		parse_str($this->getRequest()->getQueryString(), $queryString);
		
		$pentaho->setPath($report->getPath());
		$pentaho->setQuery(array_merge($reportParams, $queryString));
		
		if ($pentaho->hasEmptyParams()) {
			return $this->redirect($this->generateUrl(
				'cogitoweb_pentaho_require_parameters',
				array_merge(
					['id' => $id],
					$pentaho->getQuery()
				)
			));
		}
		
		try {
			$pentaho->setOutputFormat($report->getOutputFormat());
			$pentaho->setOutputType($report->getOutputType());
		} catch (InvalidArgumentException $e) {}
		
		$result = $pentaho->query();
		$parsedResult = $pentaho->parseResponse($result);
		
		$response = new Response();
		$response->setContent($parsedResult->getBody());
		$response->setStatusCode($parsedResult->getStatusCode());
		$response->headers->add($parsedResult->getHeaders());
		$response->prepare(Request::createFromGlobals());
		
		return $response;
	}
	
	public function requireParametersAction($id) {
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			throw $this->createNotFoundException('The id ' . $id . ' does not exist');
		}
		
		$class = $report->getForm();
		$params = $this->getRequest()->query->all();
		$form = $this->createForm(
			new $class(),
			$params
		);
		
		$form->handleRequest($this->getRequest());
		
		if ($form->isValid()) {
			return new Response();//$this->redirect($this->generateUrl('task_success'));
		}
		
        return $this->render('CogitowebPentahoProxyBundle:Default:Form.html.twig', array(
            'form' => $form->createView(),
        ));
	}
	
	public function useParametersAction($id, $params) {
		var_dump($params);
		$em = $this->getDoctrine()->getManager();
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			throw $this->createNotFoundException('The id does not exist');
		}
		
		$db = $report->getDb();
		/* @var $db Db */
		
		$pentaho = new Pentaho(
			$db->getHost(),
			$db->getUsername(),
			$db->getPassword()
		);
		
		if ($pentaho->hasEmptyParams($report->getParams())) {
			return $this->redirect(
				$this->generateUrl(
					'cogitoweb_pentaho_require_parameters',
					[
						'id' => $id,
						'params' => $pentaho->getEmptyParams('/', $report->getParams())
					]
				)
			);
		}
		
		try {
			$pentaho->setOutputFormat($report->getOutputFormat());
			$pentaho->setOutputType($report->getOutputType());
		} catch (InvalidArgumentException $e) {}
		
/*		$result = $pentaho->query(
			$report->getPath(),
			$report->getParams()
		);
		$result = $pentaho->parseResponse($result);
		
*/		$response = new Response();
/*		$response->setContent($result->getBody());
		$response->setStatusCode($result->getStatusCode());
		$response->headers->add($result->getHeaders());
		$response->prepare(Request::createFromGlobals());
		
*/		return $response;
	}
}