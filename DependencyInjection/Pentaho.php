<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cogitoweb\PentahoProxyBundle\DependencyInjection;

use InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Provides methods to query a Pentaho report with PHP.
 *
 * @author Daniele Artico
 */
class Pentaho {

	const OUTPUT_FORMAT_CSV = 'table/csv;page-mode=stream';
	const OUTPUT_FORMAT_EMAIL = 'mime-message/text/html';
	const OUTPUT_FORMAT_EXCEL = 'table/excel;page-mode=flow';
	const OUTPUT_FORMAT_EXCEL2007 = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;page-mode=flow';
	const OUTPUT_FORMAT_HTML_PAGE = 'table/html;page-mode=page';
	const OUTPUT_FORMAT_HTML_STREAM = 'table/html;page-mode=stream';
	const OUTPUT_FORMAT_PDF = 'pageable/pdf';
	const OUTPUT_FORMAT_PNG = 'pageable/X-AWT-Graphics;image-type=png';
	const OUTPUT_FORMAT_RTF = 'table/rtf;page-mode=flow';
	const OUTPUT_FORMAT_TEXT = 'pageable/text';
	const OUTPUT_FORMAT_XML_PAGEABLE = 'pageable/xml';
	const OUTPUT_FORMAT_XML_TABLE = 'table/xml';
	const OUTPUT_TYPE_DOWNLOAD = 'download';
	const OUTPUT_TYPE_VIEW = 'view';
	const EMPTY_PARAM_MARKER = '$';
	const PENTAHO_API_PATH = '/pentaho/api';

	protected $formats = [
		'csv' => 'table/csv;page-mode=stream',
		'email' => 'mime-message/text/html',
		'excel' => 'table/excel;page-mode=flow',
		'excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;page-mode=flow',
		'html' => 'table/html;page-mode=stream', // 'table/html;page-mode=page'
		'pdf' => 'pageable/pdf',
		'png' => 'pageable/X-AWT-Graphics;image-type=png',
		'rtf' => 'table/rtf;page-mode=flow',
		'text' => 'pageable/text',
		'xml' => 'table/xml' // 'pageable/xml'
	];
	protected $outputFormat;
	protected $outputType;
	
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
		return $this->request->getScheme();
	}

	public function getHost() {
		return $this->request->getHost();
	}

	public function getPort() {
		return $this->request->getPort();
	}

	public function getUsername() {
		$pattern = '/^Basic (.+)$/';
		$subject = $this->request->getHeader('Authorization');

		if (preg_match($pattern, $subject, $matches) == true) {
			list($username, $password) = explode(':', base64_decode($matches[1]));
			return $username;
		}

		return null;
	}

	public function getPassword() {
		$pattern = '/^Basic (.+)$/';
		$subject = $this->request->getHeader('Authorization');

		if (preg_match($pattern, $subject, $matches) == true) {
			list($username, $password) = explode(':', base64_decode($matches[1]));
			return $password;
		}

		return null;
	}

	public function getPath() {
		return $this->request->getPath();
	}

	public function getQuery() {
		return $this->request->getQuery()->toArray();
	}

	public function getOutputFormat() {
		return $this->outputFormat;
	}

	public function getOutputType() {
		return $this->outputType;
	}

	public function hasEmptyParams() {
		foreach ($this->getQuery() as $value) {
			if ($value == self::EMPTY_PARAM_MARKER) {
				return true;
			}
		}

		return false;
	}

	private function setScheme($scheme) {
		$this->request->setScheme($scheme);
	}

	public function setHost($host) {
		$this->request->setHost($host);
	}

	public function setPort($port) {
		$this->request->setPort($port);
	}

	public function setUsername($username) {
		$this->request->setHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $this->getPassword()));
	}

	public function setPassword($password) {
		$this->request->setHeader('Authorization', 'Basic ' . base64_encode($this->getUsername() . ':' . $password));
	}

	public function setPath($path) {
		$this->request->setPath(self::PENTAHO_API_PATH . '/repos/' . $path . '/generatedContent');
	}

	public function setQuery($query) {
		$this->request->setQuery($query);
	}

	/**
	 * Sets the report's output format that Pentaho will render
	 * List of available formats as associative array, provided by getFormats():
	 * 'csv'       => 'table/csv;page-mode=stream'
	 * 'email'     => 'mime-message/text/html'
	 * 'excel'     => 'table/excel;page-mode=flow'
	 * 'excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;page-mode=flow'
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
		switch ($outputFormat) {
			case 'csv':
				$this->outputFormat = self::OUTPUT_FORMAT_CSV;
				break;
			case 'email':
				$this->outputFormat = self::OUTPUT_FORMAT_EMAIL;
				break;
			case 'excel':
				$this->outputFormat = self::OUTPUT_FORMAT_EXCEL;
				break;
			case 'excel2007':
				$this->outputFormat = self::OUTPUT_FORMAT_EXCEL2007;
				break;
			case 'html':
				$this->outputFormat = self::OUTPUT_FORMAT_HTML_STREAM;
				break;
			case 'pdf':
				$this->outputFormat = self::OUTPUT_FORMAT_PDF;
				break;
			case 'png':
				$this->outputFormat = self::OUTPUT_FORMAT_PNG;
				break;
			case 'rtf':
				$this->outputFormat = self::OUTPUT_FORMAT_RTF;
				break;
			case 'text':
				$this->outputFormat = self::OUTPUT_FORMAT_TEXT;
				break;
			case 'xml':
				$this->outputFormat = self::OUTPUT_FORMAT_XML_TABLE;
				break;
			case self::OUTPUT_FORMAT_CSV:
				$this->outputFormat = self::OUTPUT_FORMAT_CSV;
				break;
			case self::OUTPUT_FORMAT_EMAIL:
				$this->outputFormat = self::OUTPUT_FORMAT_EMAIL;
				break;
			case self::OUTPUT_FORMAT_EXCEL:
				$this->outputFormat = self::OUTPUT_FORMAT_EXCEL;
				break;
			case self::OUTPUT_FORMAT_EXCEL2007:
				$this->outputFormat = self::OUTPUT_FORMAT_EXCEL2007;
				break;
			case self::OUTPUT_FORMAT_HTML_PAGE:
				$this->outputFormat = self::OUTPUT_FORMAT_HTML_PAGE;
				break;
			case self::OUTPUT_FORMAT_HTML_STREAM:
				$this->outputFormat = self::OUTPUT_FORMAT_HTML_STREAM;
				break;
			case self::OUTPUT_FORMAT_PDF:
				$this->outputFormat = self::OUTPUT_FORMAT_PDF;
				break;
			case self::OUTPUT_FORMAT_PNG:
				$this->outputFormat = self::OUTPUT_FORMAT_PNG;
				break;
			case self::OUTPUT_FORMAT_RTF:
				$this->outputFormat = self::OUTPUT_FORMAT_RTF;
				break;
			case self::OUTPUT_FORMAT_TEXT:
				$this->outputFormat = self::OUTPUT_FORMAT_TEXT;
				break;
			case self::OUTPUT_FORMAT_XML_PAGEABLE:
				$this->outputFormat = self::OUTPUT_FORMAT_XML_PAGEABLE;
				break;
			case self::OUTPUT_FORMAT_XML_TABLE:
				$this->outputFormat = self::OUTPUT_FORMAT_XML_TABLE;
				break;
			default:
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
			case self::OUTPUT_TYPE_DOWNLOAD:
				$this->outputType = self::OUTPUT_TYPE_DOWNLOAD;
				break;
			case self::OUTPUT_TYPE_VIEW:
				$this->outputType = self::OUTPUT_TYPE_VIEW;
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
	public function query() {
		$this->request->getQuery()->add('output-target', $this->getOutputFormat());

		return $this->client->send($this->request);
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
						'Content-Disposition', str_replace(
								ResponseHeaderBag::DISPOSITION_INLINE, ResponseHeaderBag::DISPOSITION_ATTACHMENT, $response->getHeader('Content-Disposition')
						)
				);
				break;
			case 'view':
				$response->setHeader(
						'Content-Disposition', str_replace(
								ResponseHeaderBag::DISPOSITION_ATTACHMENT, ResponseHeaderBag::DISPOSITION_INLINE, $response->getHeader('Content-Disposition')
						)
				);
				break;
			default:
		}

		return $response;
	}

}
