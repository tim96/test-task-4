<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    const STATUS_NEW = 1;
    const STATUS_PAID = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $status;

    /**
     * @var OrderProduct[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    private $orderProducts;

    /**
     * @ORM\Column(type="money")
     *
     * @var Money
     */
    private $sum;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->status = static::STATUS_NEW;
        $this->sum = new Money(0, new Currency('RUB'));
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): bool
    {
        return $this->orderProducts->removeElement($orderProduct);
    }

    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function getSum(): ?Money
    {
        return $this->sum;
    }

    public function setSum(Money $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function isNew(): bool
    {
        return $this->getStatus() === static::STATUS_NEW;
    }

    public function setPaid(): void
    {
        $this->setStatus(static::STATUS_PAID);
    }
}
