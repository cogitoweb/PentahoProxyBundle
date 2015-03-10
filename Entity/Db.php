<?php

namespace Cogitoweb\PentahoProxyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PentahoProxyBundleDb
 *
 * @ORM\Table(name="pentaho_proxy_bundle_db")
 * @ORM\Entity
 */
class Db
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
     * @var string
     *
     * @ORM\Column(name="release", type="string", length=255, nullable=true)
     */
    private $release;

    /**
     * @var string
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port = 8080;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="initialization_vector", type="string", length=16)
     */
    private $initializationVector;
	
	/**
	 * @ORM\OneToMany(targetEntity="Report", mappedBy="id")
	 */
	protected $reports;
	
	private $secret = 'abcd1234';

	public function __construct() {
		$this->reports = new ArrayCollection();
	}
	
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
     * Set release
     *
     * @param string $release
     * @return PentahoProxyBundleDb
     */
    public function setRelease($release)
    {
        $this->release = $release;

        return $this;
    }

    /**
     * Get release
     *
     * @return string 
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return PentahoProxyBundleDb
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string 
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return PentahoProxyBundleDb
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return PentahoProxyBundleDb
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return PentahoProxyBundleDb
     */
    public function setPassword($password)
    {
		$initializationVectorSize = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
		$initializationVector = mcrypt_create_iv($initializationVectorSize);
		$this->setInitializationVector($initializationVector);
		
		$this->password = openssl_encrypt(
			$password,
			'AES-256-CBC',
			$this->secret,
			null,
			$this->getInitializationVector()
		);
		
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
		return openssl_decrypt(
			$this->password,
			'AES-256-CBC',
			$this->secret,
			null,
			$this->getInitializationVector()
		);
    }

    /**
     * Set initializationVector
     *
     * @param string $initializationVector
     * @return PentahoProxyBundleDb
     */
    private function setInitializationVector($initializationVector)
    {
		$this->initializationVector = base64_encode($initializationVector);
		
        return $this;
    }

    /**
     * Get initializationVector
     *
     * @return resource 
     */
    public function getInitializationVector()
    {
        return base64_decode($this->initializationVector);
    }

    /**
     * Add reports
     *
     * @param \Cogitoweb\PentahoProxyBundle\Entity\Report $reports
     * @return Db
     */
    public function addReport(\Cogitoweb\PentahoProxyBundle\Entity\Report $reports)
    {
        $this->reports[] = $reports;

        return $this;
    }

    /**
     * Remove reports
     *
     * @param \Cogitoweb\PentahoProxyBundle\Entity\Report $reports
     */
    public function removeReport(\Cogitoweb\PentahoProxyBundle\Entity\Report $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReports()
    {
        return $this->reports;
    }
}
