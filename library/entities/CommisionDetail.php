<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * CommisionDetail
 *
 * @ORM\Table(name="commision_detail")
 * @ORM\Entity
 */
class CommisionDetail
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
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=30, nullable=false)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="money", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $money;


}
