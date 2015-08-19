<?php
namespace Cogitoweb\PentahoProxyBundle\Controller;

use Cogitoweb\PentahoProxyBundle\DependencyInjection\Pentaho;
use Cogitoweb\PentahoProxyBundle\Entity\Db;
use Cogitoweb\PentahoProxyBundle\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {
	public function proxyAction($id) {
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			throw $this->createNotFoundException('The id ' . $id . ' does not exist');
		}
		
//		$db->setPassword('');
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($db);
//		$em->flush();
		
		$db = $report->getDb();
		/* @var $db Db */
		
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
		
		$formName = $report->getForm();
		$params = $this->getRequest()->query->all();
		
		// Strip $ parameters
		$params = array_filter($params, function ($value) {
			return strpos($value, Pentaho::EMPTY_PARAM_MARKER) === false;
		});
		
		$form = $this->createForm(
			$formName,
			$params
		);
		
		$form->handleRequest($this->getRequest());
		
		if ($form->isValid()) {
			// Preparo i parametri
			$parameters = array_map(
				function ($parameter) {
					if ($parameter instanceof \DateTime) {
						return $parameter->format('Y-m-d');
					}
					
					if (is_object($parameter)) {
						return $parameter->getId();
					}
					
					if (is_array($parameter)) {
						return implode(',', $parameter);
					}
					
					return $parameter;
				},
				$form->getData()
			);
			$parameters['id'] = $id;
			
			return new RedirectResponse($this->generateUrl('cogitoweb_pentaho_proxy', $parameters));
		}
		
        return $this->render($report->getTemplate(), array(
            'form' => $form->createView(),
        ));
	}
}