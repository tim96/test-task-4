<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="money")
     *
     * @var Money
     */
    private $price;

    /**
     * @var OrderProduct[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="product", cascade={"persist"})
     */
    protected $productOrders;

    public function __construct()
    {
        $this->productOrders = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getProductOrders(): Collection
    {
        return $this->productOrders;
    }

    public function addProductOrder(OrderProduct $orderProduct): self
    {
        if (!$this->productOrders->contains($orderProduct)) {
            $this->productOrders[] = $orderProduct;
        }

        return $this;
    }

    public function removeProductOrder(OrderProduct $orderProduct): bool
    {
        return $this->productOrders->removeElement($orderProduct);
    }
}
