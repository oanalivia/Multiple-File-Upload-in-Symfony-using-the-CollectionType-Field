<?php

namespace Playground\CookiejarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="cookiejar_document")
 * @ORM\Entity(repositoryClass="Playground\CookiejarBundle\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

	/**
     * Many Documents have one (the same) Folder
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="documents")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
	private $folder;

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
     * @return Document
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
     * Set extension
     *
     * @param string $extension
     *
     * @return Document
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set folder
     *
     * @param \Playground\CookiejarBundle\Entity\Folder $folder
     *
     * @return Document
     */
    public function setFolder(\Playground\CookiejarBundle\Entity\Folder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \Playground\CookiejarBundle\Entity\Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }

	// https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony/creer-des-formulaires-avec-symfony
	// Part 4.1. "Créer des formulaires avec Symfony"

	private $file;

	// Temporary store the file name
	private $tempFilename;

	public function getFile()
	{
		return $this->file;
	}

	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;

		// Replacing a file ? Check if we already have a file for this entity
		if (null !== $this->extension)
		{
			// Save file extension so we can remove it later
			$this->tempFilename = $this->extension;

			// Reset values
			$this->extension = null;
			$this->name = null;
		}
	}

	/**
	* @ORM\PrePersist()
	* @ORM\PreUpdate()
	*/
	public function preUpload()
	{
		// If no file is set, do nothing
		if (null === $this->file)
		{
			return;
		}

		// The file name is the entity's ID
		// We also need to store the file extension
		$this->extension = $this->file->guessExtension();

		// And we keep the original name
		$this->name = $this->file->getClientOriginalName();
	}

	/**
	* @ORM\PostPersist()
	* @ORM\PostUpdate()
	*/
	public function upload()
	{
		// If no file is set, do nothing
		if (null === $this->file)
		{
			return;
		}

		// A file is present, remove it
		if (null !== $this->tempFilename)
		{
			$oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
			if (file_exists($oldFile))
			{
				unlink($oldFile);
			}
		}

		// Move the file to the upload folder
		$this->file->move(
			$this->getUploadRootDir(),
			$this->id.'.'.$this->extension
		);
	}

	/**
	* @ORM\PreRemove()
	*/
	public function preRemoveUpload()
	{
		// Save the name of the file we would want to remove
		$this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
	}

	/**
	* @ORM\PostRemove()
	*/
	public function removeUpload()
	{
		// PostRemove => We no longer have the entity's ID => Use the name we saved
		if (file_exists($this->tempFilename))
		{
			// Remove file
			unlink($this->tempFilename);
		}
	}

	public function getUploadDir()
	{
		// Upload directory
		return 'uploads/documents/';
		// This means /web/uploads/documents/
	}

	protected function getUploadRootDir()
	{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		// Image location (PHP)
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}

	public function getUrl()
	{
		return $this->id.'.'.$this->extension;
	}
}
