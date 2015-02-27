<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Provides methods to query a Pentaho report with PHP.
 *
 * @author Daniele Artico
 */
namespace Cogitoweb\PentahoProxyBundle\DependencyInjection;

use InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Pentaho {
	const PENTAHO_API_PATH = '/pentaho/api';
	protected $formats = [
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
	
	private $scheme;
	private $host;
	private $port;
	private $username;
	private $password;
	private $outputFormat;
	private $outputType;
	
	private $client;
	private $request;
	
	public function __construct($host = 'localhost', $username = 'admin', $password = 'password', $port = 8080) {
		$this->client = new Client();
		$this->request = $this->client->createRequest('GET');
		
		$this->setScheme('http');
		$this->setHost($host);
		$this->setPort($port);
		$this->setUsername($username);
		$this->setPassword($password);
	}
	
	public function getFormats() {
		return $this->formats;
	}

	public function getScheme() {
		return $this->scheme;
	}

	public function getHost() {
		return $this->host;
	}
	
	public function getPort() {
		return $this->port;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}

	public function getOutputFormat() {
		return $this->outputFormat;
	}
	
	public function getOutputType() {
		return $this->outputType;
	}
	
	private function setScheme($scheme) {
		$this->scheme = $scheme;
		$this->request->setScheme($scheme);
	}
	
	public function setHost($host) {
		$this->host = $host;
		$this->request->setHost($host);
	}
	
	public function setPort($port) {
		$this->port = $port;
		$this->request->setPort($port);
	}
	
	public function setUsername($username) {
		$this->username = $username;
		$this->request->setHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $this->password));
	}
	
	public function setPassword($password) {
		$this->password = $password;
		$this->request->setHeader('Authorization', 'Basic ' . base64_encode($this->username . ':' . $password));
	}
	
	/**
	 * Sets the report's output format that Pentaho will render
	 * List of available formats as associative array, provided by getFormats():
	 * 'csv'       => 'table/csv;page-mode=stream'
	 * 'email'     => 'mime-message/text/html'
	 * 'excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;page-mode=flow'
	 * 'excel'     => 'table/excel;page-mode=flow'
	 * 'html'      => 'table/html;page-mode=stream'
	 * 'pdf'       => 'pageable/pdf'
	 * 'png'       => 'pageable/X-AWT-Graphics;image-type=png'
	 * 'rtf'       => 'table/rtf;page-mode=flow'
	 * 'text'      => 'pageable/text'
	 * 'xml'       => 'table/xml'
	 * @param string $outputFormat		Must be either a key or a value of the given list
	 * @throws InvalidArgumentException
	 */
	public function setOutputFormat($outputFormat) {
		if (in_array($outputFormat, array_values($this->formats))) {
			$this->outputFormat = $outputFormat;
		} else if (in_array($outputFormat, array_keys($this->formats))) {
			$this->outputFormat = $this->formats[$outputFormat];
		} else {
			throw new InvalidArgumentException();
		}
	}
	
	/**
	 * Sets the behavior of parseResponse() to return the query response as a downloadable file or to show it straightforward
	 * @param string $outputType Must be "download" or "view", respectively
	 * @throws InvalidArgumentException
	 */
	public function setOutputType($outputType) {
		switch ($outputType) {
			case 'download':
				$this->outputType = $outputType;
				break;
			case 'view':
				$this->outputType = $outputType;
				break;
			default:
				throw new InvalidArgumentException();
		}
	}
	
	/**
	 * 
	 * @param string $path		Report's path in Pentaho format (i.e. :folder:subfolder:report.prpt)
	 * @param string $params	Parameters to append as HTTP query string (i.e. costumer=John Doe&age=32)
	 * @return \GuzzleHttp\Message\Response
	 */
	public function query($path, $params) {
		$request = clone $this->request;
		
		$request->setPath(self::PENTAHO_API_PATH . '/repos/' . $path . '/report');
		
		$request->setQuery($params);
		$request->getQuery()->add('output-target', $this->getOutputFormat());
		
		return $this->client->send($request);
	}
	
	/**
	 * - Removes 'Transfer-Encoding' header for compatibility issue;
	 * - Removes Pentaho's cookie;
	 * - Adapts 'Content-Disposition' header to setOutputType() option;
	 * @param Response $response
	 * @return Response
	 */
	public function parseResponse(Response $response) {
		$response->removeHeader('Transfer-Encoding');
		$response->removeHeader('Set-Cookie');
		
		switch ($this->getOutputType()) {
			case 'download':
				$response->setHeader(
					'Content-Disposition',
					str_replace(
						ResponseHeaderBag::DISPOSITION_INLINE,
						ResponseHeaderBag::DISPOSITION_ATTACHMENT,
						$response->getHeader('Content-Disposition')
					)
				);
				break;
			case 'view':
				$response->setHeader(
					'Content-Disposition',
					str_replace(
						ResponseHeaderBag::DISPOSITION_ATTACHMENT,
						ResponseHeaderBag::DISPOSITION_INLINE,
						$response->getHeader('Content-Disposition')
					)
				);
				break;
			default:
				
		}
		
		return $response;
	}
}