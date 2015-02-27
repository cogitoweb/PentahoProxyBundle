<?php
namespace Cogitoweb\PentahoProxyBundle\Controller;

use Cogitoweb\PentahoProxyBundle\DependencyInjection\Pentaho;
use Cogitoweb\PentahoProxyBundle\Entity\Db;
use Cogitoweb\PentahoProxyBundle\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {
	const PENTAHO_API = '/pentaho/api';
	protected $shortcuts = [
		'csv'       => 'table/csv;page-mode=stream',
		'email'     => 'mime-message/text/html',
		'excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;page-mode=flow',
		'excel'     => 'table/excel;page-mode=flow',
		'html'      => 'table/html;page-mode=stream', // 'table/html;page-mode=page'
		'pdf'       => 'pageable/pdf',
		'png'       => 'pageable/X-AWT-Graphics;image-type=png',
		'rtf'       => 'table/rtf;page-mode=flow',
		'text'      => 'pageable/text',
		'xml'       => 'table/xml' // 'pageable/xml'
	];
	
	protected $client;
	protected $clientRequest;
	protected $clientResponse;
	protected $symfonyResponse;

	public function proxyAction($id) {
		$em = $this->getDoctrine()->getManager();
		$report = $this->getDoctrine()->getRepository('CogitowebPentahoProxyBundle:Report')->find($id);
		/* @var $report Report */
		
		if (!$report) {
			return new Response();
		}
		
		$db = $report->getDb();
		/* @var $db Db */
		
		$pentaho = new Pentaho(
			$db->getHost(),
			$db->getUsername(),
			$db->getPassword()
		);
		
		try {
			$pentaho->setOutputFormat($report->getOutputFormat());
			$pentaho->setOutputType($report->getOutputType());
		} catch (InvalidArgumentException $e) {}
		
		$result = $pentaho->query(
			$report->getPath(),
			$report->getParams()
		);
		$result = $pentaho->parseResponse($result);
		
		$response = new Response();
		$response->setContent($result->getBody());
		$response->setStatusCode($result->getStatusCode());
		$response->headers->add($result->getHeaders());
		$response->prepare(Request::createFromGlobals());
		
		return $response;
	}
}