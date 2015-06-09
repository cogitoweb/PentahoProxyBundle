<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cogitoweb\PentahoProxyBundle\Entity;

use DateTime;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Description of OpenERPAttivita
 *
 * @author Daniele Artico
 */
class OpenERPAttivita {
	protected $progettoId;
	protected $annomese;
	protected $dataDa;
	protected $dataA;
	
	public function __construct($params) {
		print($params);
		foreach (explode('&', $params) as $param) {
			list($key, $value) = explode('=', $param);
			
			switch($key) {
				case 'progettoId':
					$this->setProgettoId($value);
					break;
				case 'annomese':
					$this->setAnnomese($value);
					break;
				case 'dataDa':
					$this->setDataDa(new DateTime($value));
					break;
				case 'dataA':
					$this->setdataA(new DateTime($value));
					break;
				default:
					
			}
		}
	}

		public function getProgettoId() {
		return $this->progettoId;
	}
	
	public function setProgettoId($progettoId) {
		$this->progettoId = $progettoId;
	}
	
	public function getAnnomese() {
		return $this->annomese;
	}
	
	public function setAnnomese($annomese) {
		$this->annomese = $annomese;
	}
	
	public function getDataDa() {
		return $this->dataDa;
	}
	
	public function setDataDa(DateTime $dataDa) {
		$this->dataDa = $dataDa;
	}
	
	public function getDataA() {
		return $this->dataA;
	}
	
	public function setDataA(DateTime $dataA) {
		$this->dataA = $dataA;
	}
	
	public static function loadValidatorMetadata(ClassMetadata $metadata) {
		$metadata->addPropertyConstraint('progettoId', new NotBlank());
		$metadata->addPropertyConstraint('annomese', new NotBlank());
		$metadata->addPropertyConstraint('dataDa', new Date());
		$metadata->addPropertyConstraint('dataA', new Date());
	}
}
