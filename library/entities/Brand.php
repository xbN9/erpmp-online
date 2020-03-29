<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Brand
 *
 * @ORM\Table(name="brand", indexes={@ORM\Index(name="sort", columns={"sort"})})
 * @ORM\Entity
 */
class Brand
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=500, nullable=false)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="aliasname", type="string", length=500, nullable=false)
     */
    private $aliasname;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=false)
     */
    private $isDelete;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime", nullable=false)
     */
    private $createtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatetime", type="datetime", nullable=true)
     */
    private $updatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletetime", type="datetime", nullable=true)
     */
    private $deletetime;


}
