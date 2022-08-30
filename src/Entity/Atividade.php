<?php

namespace App\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AtividadeRepository;
use Exception;

/**
 * @ORM\Entity(repositoryClass=AtividadeRepository::class)
 */
class Atividade implements JsonSerializable
{

    const SITUACAO_PENDENTE = 0;
    const SITUACAO_CONCLUIDO = 1;
    const SITUACAO_FALHA = 2;

    const DESCRITIVOS_SITUACAO = [
        self::SITUACAO_PENDENTE => 'pendente',
        self::SITUACAO_CONCLUIDO => 'concluida',
        self::SITUACAO_FALHA => 'falhou',
    ];

    public function jsonSerialize()
    {
        $this->fillSituacaoDescritivo();
        $array = [
            'id' => $this->getId(),
            'descricao' => $this->getDescricao(),
            'situacao' => $this->getSituacao(),
            'situacaoDescritivo' => $this->getSituacaoDescritivo(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
        ];

        return $array;
    }

    private function fillSituacaoDescritivo(){
        $this->setSituacaoDescritivo(self::DESCRITIVOS_SITUACAO[$this->getSituacao()]);
    }

    public function falhar(){
        if($this->situacao != Atividade::SITUACAO_PENDENTE) {
            throw new Exception('Não é possível concluir uma tarefa que não está pendente.');
        }
        $this->situacao = Atividade::SITUACAO_FALHA;
        return $this;
    }

    public function concluir(){
        if($this->situacao != Atividade::SITUACAO_PENDENTE) {
            throw new Exception('Não é possível concluir uma tarefa que não está pendente.');
        }
        $this->situacao = Atividade::SITUACAO_CONCLUIDO;
        return $this;
    }

    public function desconcluir(){
        if($this->situacao != Atividade::SITUACAO_CONCLUIDO) {
            throw new Exception('Não é possível concluir uma tarefa que não está pendente.');
        }
        $this->situacao = Atividade::SITUACAO_PENDENTE;
        return $this;
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $descricao;

    /**
     * @ORM\ManyToOne(targetEntity=Hora::class, inversedBy="atividades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hora;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="atividades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="integer")
     */
    private $situacao;

    private $situacaoDescritivo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getHora(): ?Hora
    {
        return $this->hora;
    }

    public function setHora(?Hora $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getSituacao(): ?int
    {
        return $this->situacao;
    }

    public function setSituacao(int $situacao): self
    {
        $this->situacao = $situacao;
        $this->fillSituacaoDescritivo();

        return $this;
    }

    public function getSituacaoDescritivo(): ?string
    {
        return $this->situacaoDescritivo;
    }

    public function setSituacaoDescritivo(string $situacaoDescritivo): self
    {
        $this->situacaoDescritivo = $situacaoDescritivo;
        return $this;
    }
}
