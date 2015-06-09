<?php

namespace Cogitoweb\PentahoProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PentahoProxyBundleReport
 *
 * @ORM\Table(name="pentaho_proxy_bundle_report")
 * @ORM\Entity
 */
class Report
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="db_id", type="integer")
     */
    private $dbId;

    /**
     * @var string
     *
     * @ORM\Column(name="output_format", type="string", length=255)
     */
    private $outputFormat;

    /**
     * @var string
     *
     * @ORM\Column(name="output_type", type="string", length=255)
     */
    private $outputType;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="string", length=255, nullable=true)
     */
    private $params;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="form", type="string", length=255, nullable=true)
     */
    private $form;

	/**
     * @ORM\ManyToOne(targetEntity="Db", inversedBy="reports")
     */
    protected $db;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dbId
     *
     * @param integer $dbId
     * @return PentahoProxyBundleReport
     */
    public function setDbId($dbId)
    {
        $this->dbId = $dbId;

        return $this;
    }

    /**
     * Get dbId
     *
     * @return integer 
     */
    public function getDbId()
    {
        return $this->dbId;
    }

    /**
     * Set outputFormat
     *
     * @param string $outputFormat
     * @return PentahoProxyBundleReport
     */
    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;

        return $this;
    }

    /**
     * Get outputFormat
     *
     * @return string 
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * Set outputType
     *
     * @param string $outputType
     * @return PentahoProxyBundleReport
     */
    public function setOutputType($outputType)
    {
        $this->outputType = $outputType;

        return $this;
    }

    /**
     * Get outputType
     *
     * @return string 
     */
    public function getOutputType()
    {
        return $this->outputType;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return PentahoProxyBundleReport
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return PentahoProxyBundleReport
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return PentahoProxyBundleReport
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set form
     *
     * @param string $form
     * @return PentahoProxyBundleReport
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return string 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set db
     *
     * @param \Cogitoweb\PentahoProxyBundle\Entity\Db $db
     * @return Report
     */
    public function setDb(\Cogitoweb\PentahoProxyBundle\Entity\Db $db = null)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get db
     *
     * @return \Cogitoweb\PentahoProxyBundle\Entity\Db 
     */
    public function getDb()
    {
        return $this->db;
    }
}
