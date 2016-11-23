<?php

namespace Lddt\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lddt\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Draw
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lddt\MainBundle\Entity\DrawRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Draw
{
    // Déclaration des attributs clés étrangères > des associations

    /**
     * Plusieurs dessins dans une catégorie
     * @ORM\ManyToOne(targetEntity="Lddt\MainBundle\Entity\Category")
     * @ORM\JoinColumn(name="id_cat",referencedColumnName="id",onDelete="CASCADE")
     */
    private $category;

    /**
     * Un dessin pour plusieurs commentaires
     * @ORM\OneToMany(targetEntity="Lddt\MainBundle\Entity\Comment", mappedBy="draw")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Lddt\MainBundle\Entity\Color",cascade={"persist"})
     */
    private $colors;

    /**
     * @ORM\ManyToOne(targetEntity="Lddt\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user",referencedColumnName="id",onDelete="CASCADE")
     */
    private $user;

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="draw_path", type="string", length=255)
     */
    private $drawPath;


    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Votre dessin ne peut pas excéder 1Mo",
     *     mimeTypes = {"image/jpeg", "image/png","image/gif"},
     *     mimeTypesMessage = "Choisissez un fichier jpg,png ou gif valide"
     * )
     */
    private $drawFile;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_online", type="boolean")
     */
    private $isOnline;

    /**
     * @var string
     *
     * @ORM\Column(name="author_name", type="string", length=255,nullable=true)
     */
    private $authorName;

    /**
     * @var string
     *
     * @ORM\Column(name="author_ico_path", type="string", length=255,nullable=true)
     */
    private $authorIcoPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    // ce constructeur permet d'hydrater l'entitié avec des valeurs par défaut
    public function __construct($user) {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->isOnline = true;
        $this->user = $user;
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
     * Set title
     *
     * @param string $title
     * @return Draw
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set drawPath
     *
     * @param string $drawPath
     * @return Draw
     */
    public function setDrawPath($drawPath)
    {
        $this->drawPath = $drawPath;

        return $this;
    }

    /**
     * Get drawPath
     *
     * @return string 
     */
    public function getDrawPath()
    {
        return $this->drawPath;
    }

    /**
     * Set isOnline
     *
     * @param boolean $isOnline
     * @return Draw
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Get isOnline
     *
     * @return boolean 
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return Draw
     */
    public function setAuthorName($authorName = null)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorIcoPath
     *
     * @param string $authorIcoPath
     * @return Draw
     */
    public function setAuthorIcoPath($authorIcoPath = null)
    {
        $this->authorIcoPath = $authorIcoPath;

        return $this;
    }

    /**
     * Get authorIcoPath
     *
     * @return string 
     */
    public function getAuthorIcoPath()
    {
        return $this->authorIcoPath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Draw
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Draw
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \Lddt\MainBundle\Entity\Category $category
     * @return Draw
     */
    public function setCategory(\Lddt\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Lddt\MainBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add comments
     *
     * @param \Lddt\MainBundle\Entity\Comment $comments
     * @return Draw
     */
    public function addComment(\Lddt\MainBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Lddt\MainBundle\Entity\Comment $comments
     */
    public function removeComment(\Lddt\MainBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add colors
     *
     * @param \Lddt\MainBundle\Entity\Color $colors
     * @return Draw
     */
    public function addColor(\Lddt\MainBundle\Entity\Color $colors)
    {
        $this->colors[] = $colors;

        return $this;
    }

    /**
     * Remove colors
     *
     * @param \Lddt\MainBundle\Entity\Color $colors
     */
    public function removeColor(\Lddt\MainBundle\Entity\Color $colors)
    {
        $this->colors->removeElement($colors);
    }

    /**
     * Get colors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColors()
    {
        return $this->colors;
    }

    // Gestion de l'upload et des chemins vers les images uploadées


    public function getDrawFile() {
        return $this->drawFile;
    }

    public function setDrawFile(UploadedFile $drawFile) {
        $this->drawFile = $drawFile;
        return $this;
    }

    // Retourner le chemin absolu d'un fichier (utile depuis les controllers)
    public function getAbsolutePath() {
        return null === $this->drawPath ? null :
            $this->getUploadRootDir().'/'.$this->drawPath;
    }
    // Retourne le chemin relatif d'un fichier (utile dans les vues twig)
    public function getWebPath() {
        return null === $this->drawPath ? null : $this->getUploadDir().'/'.$this->drawPath;
    }


    protected function getUploadRootDir() {
        return __DIR__."/../../../../web/".$this->getUploadDir();
    }

    // Chemin réel de stockage des fichiers dessins qui seront uploadés
    protected function getUploadDir() {
        return "uploads/draws";
    }

    // Gestion des cycles de vie, un dessin ne peut être persisté dans la DB si le fichier jpg, png ou gif n'est pas uploadé.

    // AVANT la création(create) ou avant la mise à jour (update)
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if(null !== $this->drawFile) {
           // Avant la sauvegarde dans la db, on crypte le nom du fichier du dessin uploadé
            // ex: fred.jpg devient 23143132.jpg
$this->drawPath = sha1(uniqid(mt_rand(),true)).'.'.$this->drawFile->guessExtension();
        } else {
            return;
        }
    }

    /**
     * Après la sauvegarde ou la mise à jour d'un dessin dans la DB, on déplace le fichier dans son répertoire d'upload designé dans getUploadDir()
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if(null === $this->drawFile) {
            return;
        }
        $this->drawFile->move($this->getUploadRootDir(),$this->drawPath);
        // vide le fichier en mémoire temporaire, une fois que le fichier est déplacé dans le repertoire d'upload
        unset($this->drawFile);
    }

    /**
     * Si je supprime un dessin dans la db, je supprime aussi le fichier dans le répertoire d'upload
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if($file = $this->getAbsolutePath() ) {
            unlink($file);
        }
    }






    /**
     * Set user
     *
     * @param \Lddt\UserBundle\Entity\User $user
     * @return Draw
     */
    public function setUser(\Lddt\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Lddt\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
