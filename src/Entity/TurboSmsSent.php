<?php

namespace Myowncode\TurboSmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class TurboSmsSent
{
    public const STATUS_SENT = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, options={"unsigned"=true})
     *
     * @Assert\NotBlank
     * @Assert\Type("numeric")
     * @Assert\Valid()
     *
     * @var string
     */
    private $phone = '';

    /**
     * @ORM\Column(type="string", length=250)
     *
     * @var string
     */
    private $message_id = '';

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $status = '';

    /**
     * @ORM\Column(type="string", length=250)
     *
     * @var string
     */
    private $status_message = '';

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $message = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updated_at;

    public function __construct()
    {
        $this->setStatus(self::STATUS_SENT);
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
     * Set phone
     *
     * @param string $phone
     *
     * @return TurboSmsSent
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set messageId
     *
     * @param string $messageId
     *
     * @return TurboSmsSent
     */
    public function setMessageId($messageId)
    {
        $this->message_id = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return string
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TurboSmsSent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set statusMessage
     *
     * @param string $statusMessage
     *
     * @return TurboSmsSent
     */
    public function setStatusMessage($statusMessage)
    {
        $this->status_message = $statusMessage;

        return $this;
    }

    /**
     * Get statusMessage
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->status_message;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return TurboSmsSent
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TurboSmsSent
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TurboSmsSent
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function onInsert()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime());
    }

}
