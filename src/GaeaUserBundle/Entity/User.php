<?php

namespace GaeaUserBundle\Entity;

use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Routing\Loader\ProtectedPhpFileLoader;

/**
 * User
 *
 * @ORM\Table(name="gaea_user")
 * @ORM\Entity(repositoryClass="GaeaUserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
