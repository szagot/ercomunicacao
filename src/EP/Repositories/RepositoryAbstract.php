<?php
/**
 * Repositório de Base
 *
 * @author    Daniel Bispo <daniel@tmw.com.br>
 * @copyright Copyright (c) 2017, TMW E-commerce Solutions
 */

namespace EP\Repositories;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;

abstract class RepositoryAbstract extends EntityRepository
{
    /**
     * Dá entrada da entidade no BD
     *
     * @param $entity
     *
     * @return mixed
     */
    public function create($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * Atualiza a entidade no BD
     *
     * @param $entity
     *
     * @return mixed
     */
    public function update($entity)
    {
        // Verifica se a unidade de trabalho está gerenciada
        if ($this->getEntityManager()->getUnitOfWork()->getEntityState($entity) != UnitOfWork::STATE_MANAGED) {
            // Se não estiver gerenciável, torna gerenciavel
            // Semelhante ao persist, mas é mais apropriado para atualização.
            // Dessa forma ele pega apenas o que mudou na persistencia já feita.
            $this->getEntityManager()->merge($entity);
        }

        // Aplica as alterações feitas
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * Remove a entidade do BD
     *
     * @param $entity
     *
     * @return bool
     */
    public function delete($entity): bool
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

        return true;
    }

    /**
     * Pega todos os registros ordenados pelo campo informado
     *
     * @param string|null $orderField
     * @param string|null $orderType
     *
     * @return array
     */
    public function findAll($orderField = null, $orderType = null): array
    {
        // Garante que o tipo da ordenação esteja tudo em maiúsculo
        if (! empty($orderType)) {
            $orderType = strtoupper($orderType);
        }

        // Retorna ordenado pelo campo escolhido
        if (! empty($orderField)) {
            return parent::findBy([], [$orderField => ($orderType == 'DESC') ? 'DESC' : 'ASC']);
        }

        // Ordem de cadastro (id em ordem normal)
        return parent::findAll();
    }

}