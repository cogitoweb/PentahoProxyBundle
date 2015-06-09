<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cogitoweb\PentahoProxyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of OpenerpAttivitaType
 *
 * @author Daniele Artico
 */
class OpenERPAttivitaType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add(
				'progetto_id',
				'integer',
				[
					'label' => 'ID progetto',
					'required' => true
				]
			)
			->add(
				'annomese',
				'text',
				[
					'label' => 'Mese/anno',
					'required' => true
				]
			)
			->add(
				'dataDa',
				'date',
				[
					'label' => 'Dal'
				]	
			)
			->add(
				'dataA',
				'date',
				[
					'label' => 'Al'
				]	
			)
			->add('Submit', 'submit');
	}
	
	public function getName() {
		return 'OpenERPAttivitaType';
	}
}