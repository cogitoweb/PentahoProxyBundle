<?php
namespace Cogitoweb\PentahoProxyBundle\Controller;

use Exception;
use Cogitoweb\PentahoProxyBundle\DependencyInjection\Pentaho;
use Cogitoweb\PentahoProxyBundle\Entity\Db;
use Cogitoweb\PentahoProxyBundle\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {
	public function proxyAction($id) {
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			throw $this->createNotFoundException('The id ' . $id . ' does not exist');
		}
		
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
			throw new Exception('Empty parameter(s) found in ' . $report->getParams());
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
}