<?php

namespace Playground\CookiejarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Folder
 *
 * @ORM\Table(name="cookiejar_folder")
 * @ORM\Entity(repositoryClass="Playground\CookiejarBundle\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

	/**
     * Many Documents have one (the same) Folder
     * @ORM\OneToMany(targetEntity="Document", mappedBy="folder", cascade={"persist"}, orphanRemoval=true)
     */
	private $documents;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Folder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add document
     *
     * @param \Playground\CookiejarBundle\Entity\Document $document
     *
     * @return Folder
     */
    public function addDocument(\Playground\CookiejarBundle\Entity\Document $document)
    {
		// Bidirectional Ownership
		$document->setFolder($this);

        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Playground\CookiejarBundle\Entity\Document $document
     */
    public function removeDocument(\Playground\CookiejarBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

	/**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
