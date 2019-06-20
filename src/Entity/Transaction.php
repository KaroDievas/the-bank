<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $details;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $receiver_account;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $receiver_name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $status;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    protected $fee_amount;

    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $currency;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getReceiverAccount(): ?string
    {
        return $this->receiver_account;
    }

    public function setReceiverAccount(string $receiver_account): self
    {
        $this->receiver_account = $receiver_account;

        return $this;
    }

    public function getReceiverName(): ?string
    {
        return $this->receiver_name;
    }

    public function setReceiverName(string $receiver_name): self
    {
        $this->receiver_name = $receiver_name;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFeeAmount()
    {
        return $this->fee_amount;
    }

    public function setFeeAmount($fee_amount): self
    {
        $this->fee_amount = $fee_amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
