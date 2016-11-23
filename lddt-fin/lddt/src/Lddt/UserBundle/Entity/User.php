<?php

namespace Lddt\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Lddt\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="avatarPath", type="string", length=255,nullable=true)
     */
    private $avatarPath;

    /**
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage = "Votre avatar ne peut pas excéder 1Mo",
     *     mimeTypes = {"image/jpeg", "image/png","image/gif"},
     *     mimeTypesMessage = "Choisissez un fichier jpg,png ou gif valide"
     * )
     */
    private $avatarFile;


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
     * Set avatarPath
     *
     * @param string $avatarPath
     * @return User
     */
    public function setAvatarPath($avatarPath = null)
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }

    /**
     * Get avatarPath
     *
     * @return string 
     */
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }

    // Gestion de l'upload et des chemins vers les images uploadées
    public function getAvatarFile() {
        return $this->avatarFile;
    }

    public function setAvatarFile(UploadedFile $avatarFile) {
        $this->avatarFile = $avatarFile;
        return $this;
    }

    // Retourner le chemin absolu d'un fichier (utile depuis les controllers)
    public function getAbsolutePath() {
        return null === $this->avatarPath ? null :
            $this->getUploadRootDir().'/'.$this->avatarPath;
    }
    // Retourne le chemin relatif d'un fichier (utile dans les vues twig)
    public function getWebPath() {
        return null === $this->avatarPath ? null : $this->getUploadDir().'/'.$this->avatarPath;
    }


    protected function getUploadRootDir() {
        return __DIR__."/../../../../web/".$this->getUploadDir();
    }

    // Chemin réel de stockage des fichiers dessins qui seront uploadés
    protected function getUploadDir() {
        return "uploads/avatars";
    }

    // Gestion des cycles de vie, un dessin ne peut être persisté dans la DB si le fichier jpg, png ou gif n'est pas uploadé.

    // AVANT la création(create) ou avant la mise à jour (update)
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if(null !== $this->avatarFile) {
            // Avant la sauvegarde dans la db, on crypte le nom du fichier du dessin uploadé
            // ex: fred.jpg devient 23143132.jpg
            $this->avatarPath = sha1(uniqid(mt_rand(),true)).'.'.$this->avatarFile->guessExtension();
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
        if(null === $this->avatarFile) {
            return;
        }
        $this->avatarFile->move($this->getUploadRootDir(),$this->avatarPath);
        // vide le fichier en mémoire temporaire, une fois que le fichier est déplacé dans le repertoire d'upload
        unset($this->avatarFile);
    }

    /**
     * Si je supprime un dessin dans la db, je supprime aussi le fichier dans le répertoire d'upload
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if($file = $this->getAbsolutePath() != null ) {
            unlink($file);
        }
    }



}
