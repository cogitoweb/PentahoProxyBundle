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
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Description of OpenerpAttivitaType
 *
 * @author Daniele Artico
 */
class OpenERPAttivitaType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('progetto_id', 'integer', [
				'label' => 'ID progetto',
				'required' => true,
				'constraints' => new NotBlank()
			])
			->add('annomese', 'text', [
				'label' => 'Mese/anno',
				'required' => true,
				'constraints' => new NotBlank()
			])
			->add('dataDa', 'date', [
				'label' => 'Dal',
				'constraints' => new Date()
			])
			->add('dataA', 'date', [
				'label' => 'Al',
				'constraints' => new Date()
			])
			->add('Submit', 'submit');
	}
	
	public function getName()
	{
		return 'OpenERPAttivitaType';
	}
}