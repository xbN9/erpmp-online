<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * StoreBook
 *
 * @ORM\Table(name="store_book", indexes={@ORM\Index(name="store_id", columns={"store_id", "book_time"})})
 * @ORM\Entity
 */
class StoreBook
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
     * @ORM\Column(name="store_id", type="integer", nullable=false)
     */
    private $storeId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="book_time", type="boolean", nullable=false)
     */
    private $bookTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="book_cnt", type="integer", nullable=false)
     */
    private $bookCnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="waiter_id", type="integer", nullable=false)
     */
    private $waiterId;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
