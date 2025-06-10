<?php

namespace System\Modules\Feedback;

use Doctrine\ORM\Mapping as ORM;
use System\Modules\Account\Account;
use System\Modules\Article\Article;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'feedbacks')]
#[ORM\HasLifecycleCallbacks]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $content;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private Account $author;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private Article $article;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getAuthor(): ?Account
    {
        return $this->author;
    }

    public function setAuthor(Account $author): void
    {
        $this->author = $author;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
