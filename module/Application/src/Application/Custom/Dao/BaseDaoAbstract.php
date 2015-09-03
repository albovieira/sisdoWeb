<?php

/**
 * ZucchiDoctrine (http://zucchi.co.uk).
 *
 * @link      http://github.com/zucchi/ZucchiDoctrine for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace Application\Custom\Dao;

use Application\Custom\Entity\BaseEntityAbstract;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Abstract Service.
 *
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @author Rick Nicol <rick@zucchi.co.uk>
 */
abstract class BaseDaoAbstract implements EventManagerAwareInterface
{
    const TABLE_COLUMN_SEPARATOR = '.';
    const CONCAT_SEPARATOR = '||';
    const INDEX_OFFSET = 0;

    /**
     * Default limit for index method.
     * Default limit for index method.
     *
     * @var int
     */
    const INDEX_LIMIT = 25;

    /**
     * Qualified name of entity to work with.
     *
     * @var string
     */
    protected $entityName;
    /**
     * The default alias key for queries, 'e' for entity.
     *
     * @var string
     */
    protected $alias = 'e';
    /**
     * The identifying field for the entity.
     *
     * @var string
     */
    protected $identifier = 'id';

    /**
     * @var
     */
    private $eventManager;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Get the metadata for the defined entity.
     *
     * @return ClassMetadata
     *
     * @throws \RuntimeException
     */
    public function getMetadata()
    {
        if (!$this->entityName) {
            throw new \RuntimeException('No Entity defined for ' . get_called_class() . ' service');
        }

        return $this->getEntityManager()->getClassMetadata($this->entityName);
    }

    /**
     * get the currently set Entity Manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * set the entity manager.
     *
     * @param EntityManager $em
     *
     * @return $this
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    /**
     * Get a new instance of the entity.
     *
     * @param null $id
     *
     * @return BaseEntityAbstract
     */
    public function getEntity($id = null)
    {
        if (is_null($id) || empty($id)) {
            $class = $this->entityName;

            return new $class();
        } else {
            return $this->getRepository()->find($id);
        }
    }

    /**
     * get count of entities.
     *
     * @param array $where
     * @param array $order
     *
     * @return int
     */
    public function getCount($where = array())
    {
        if (!$this->entityName) {
            throw new \RuntimeException('No Entity defined for ' . get_called_class() . ' service');
        }

        $em = $this->entityManager;
        $qb = $em->createQueryBuilder();
        $qb->select('count(' . $this->alias . ') as total')
            ->from($this->entityName, $this->alias);

        $this->addWhere($qb, $where);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Build where statement and add to the query builder.
     *
     * @param \Doctrine\Orm\QueryBuilder $qb
     * @param mixed $where
     *
     * @return $this
     */
    protected function addWhere($qb, $where)
    {
        // process the $where
        if (is_string($where)) {
            // straight DQL string
            $qb->andWhere($where);
        } elseif (is_array($where) && count($where)) {
            // create where expression
            $whereExp = $qb->expr()->andx();
            $params = array();

            // index for the parameters
            $i = 0;

            // loop through all the clauses supplied
            foreach ($where as $col => $val) {
                if ((is_array($val) && (!isset($val['value']) || (is_string($val['value']) && strlen($val['value']) == 0))) ||
                    (is_string($val) && (!$val || strlen($val) == 0))
                ) {
                    // skip if invalid value
                    continue;
                }

                // check if we've been provided with an operator as well as a value
                if (!is_array($val)) {
                    $operator = Expr\Comparison::EQ;
                    $val = $val;
                } elseif (count($val) == 1) {
                    $operator = Expr\Comparison::EQ;
                    $val = end($val);
                } else {
                    $operator = isset($val['operator']) ? $val['operator'] : Expr\Comparison::EQ;
                    $val = array_key_exists('value', $val) ? $val['value'] : array();
                }

                // set the alias to the default
                $entityAlias = $this->alias;

                // if col relates to a relation i.e. Role.id
                // then perform a join and set up the alias and column names
                if (strpos($col, self::TABLE_COLUMN_SEPARATOR) !== false) {
                    $parts = explode(self::TABLE_COLUMN_SEPARATOR, $col);
                    $col = array_pop($parts);
                    $par = $this->alias;

                    foreach ($parts as $rel) {
                        $entityAlias = strtolower($rel);
                        $jt = new Expr\Join(Expr\Join::LEFT_JOIN, $par . self::TABLE_COLUMN_SEPARATOR . $rel, $entityAlias);
                        if (!strpos($qb->getDql(), $jt->__toString()) !== false) {
                            $qb->leftJoin($par . self::TABLE_COLUMN_SEPARATOR . $rel, $entityAlias);
                        }
                        $par = $entityAlias;
                    }
                }

                // process sets a little differently
                if (!is_array($val)) {
                    $val = array($val);
                }

                if ($operator == 'regexp') {
                    $whereExp->add('REGEXP(' . $entityAlias . self::TABLE_COLUMN_SEPARATOR . $col . ",'" . $val[0] . "') = 1");
                } elseif ($operator == 'between') {
                    if (count($val) == 2) {
                        // $value should now be an array with 2 values
                        $expr = new Expr();
                        $from = (is_int($val[0])) ? $val[0] : "'" . $val[0] . "'";
                        $to = (is_int($val[1])) ? $val[1] : "'" . $val[1] . "'";

                        $stmt = $expr->between($entityAlias . self::TABLE_COLUMN_SEPARATOR . $col, $from, $to);
                        $whereExp->add($stmt);
                    }
                } elseif ($operator == 'is') {
                    $expr = new Expr();
                    $method = 'is' . ucfirst($val[0]);
                    if (method_exists($expr, $method)) {
                        $stmt = $expr->{$method}($entityAlias . self::TABLE_COLUMN_SEPARATOR . $col);
                        $whereExp->add($stmt);
                    }
                } else {
                    // this holds the subquery for this field, each component being an OR
                    $subWhereExp = $qb->expr()->orX();

                    foreach ($val as $value) {
                        if ($value == null) {
                            $cmpValue = 'NULL';
                        } else {
                            $cmpValue = '?' . $i;

                            // wrap LIKE values
                            if ($operator == 'like') {
                                $value = '%' . trim($value, '%') . '%';
                            }

                            // add the parameter value into the parameters stack
                            $params[$i] = $value;
                            ++$i;
                        }

                        $comparison = new Expr\Comparison($entityAlias . self::TABLE_COLUMN_SEPARATOR . $col, $operator, $cmpValue);
                        $subWhereExp->add($comparison);
                    }

                    // add in the subquery as an AND
                    $whereExp->add($subWhereExp);
                }
            }

            // only add where expression if actually has parts
            if (count($whereExp->getParts())) {
                $qb->where($whereExp);
            }

            // set the params from the where clause above
            $qb->setParameters($params);
        }

        return $this;
    }

    /**
     * Get a list of entities.
     *
     * @param array $where
     * @param array $order
     * @param int $limit
     * @param int $offset
     * @param int $hydrate
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function getList(
        $where = array(),
        $order = array(),
        $offset = self::INDEX_OFFSET,
        $limit = self::INDEX_LIMIT,
        $hydrate = \Doctrine\ORM\Query::HYDRATE_OBJECT
    )
    {
        if (!$this->entityName) {
            throw new \RuntimeException('No Entity defined for ' . get_called_class() . ' service');
        }

        // allow for hydration to be set to null
        if ($hydrate == null) {
            $hydrate = \Doctrine\ORM\Query::HYDRATE_OBJECT;
        }

        $em = $this->entityManager;
        $qb = $em->createQueryBuilder();
        $qb->select($this->alias)
            ->from($this->entityName, $this->alias);

        $this->addWhere($qb, $where)
            ->addOrder($qb, $order)
            ->addLimit($qb, $limit, $offset);

        return $qb->getQuery()->getResult($hydrate);
    }

    /**
     * Build and add an oder field to the query builder.
     *
     * @param \Doctrine\Orm\QueryBuilder $qb
     * @param mixed $order
     *
     * @return Gmg_Service_Abstract
     */
    protected function addOrder($qb, $order)
    {
        // add the where expression to the query
        // process the $order
        if (is_string($order)) {
            // straight DQL string
            $qb->orderBy($order);
        } elseif (is_array($order) && count($order)) {
            // loop through each order clause supplied
            foreach ($order as $col => $dir) {
                // set the alias to the default
                $tableAlias = $this->alias;

                // if col relates to Relation i.e. Role.id
                // then set up the alias and column names (the join should have
                // already been performed in the $where)
                // Caution: this will cause an error if the column wasn't specified in the $where
                if (strpos($col, self::TABLE_COLUMN_SEPARATOR) !== false) {
                    $parts = explode(self::TABLE_COLUMN_SEPARATOR, $col);
                    $col = array_pop($parts);
                    $par = $this->alias;

                    // test for existing joins
                    $as = array();
                    foreach ($qb->getDQLPart('join') as $j) {
                        $as[] = $j;
                    }

                    foreach ($parts as $rel) {
                        $tableAlias = strtolower($rel);
                        $jt = new Expr\Join(Expr\Join::LEFT_JOIN, $par . self::TABLE_COLUMN_SEPARATOR . $rel, $tableAlias);
                        if (!strpos($qb->getDql(), $jt->__toString()) !== false) {
                            $qb->leftJoin($par . self::TABLE_COLUMN_SEPARATOR . $rel, $tableAlias);
                        }
                        $par = $tableAlias;
                    }
                }
                $qb->addOrderBy($tableAlias . self::TABLE_COLUMN_SEPARATOR . $col, $dir);
            }
        }

        return $this;
    }

    /**
     * Get a a specific entity.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function get($id)
    {
        if (!$this->entityName) {
            throw new \RuntimeException('No Entity defined for ' . get_called_class() . ' service');
        }

        // Alterei o modo LockMode::OPTIMISTIC para LockMode::NONE
        return $this->entityManager->find($this->entityName, $id, LockMode::NONE);
    }

    /**
     * Refresh entity.
     *
     * @param BaseEntityAbstract $entity
     *
     * @return $this
     */
    public function refresh(BaseEntityAbstract $entity)
    {
        $this->entityManager->refresh($entity);
        $metaData = $this->entityManager->getClassMetaData($this->entityName);
        $mappings = $metaData->getAssociationMappings();

        foreach ($mappings as $mapping) {
            $fieldName = $mapping['fieldName'];
            $assoc = $entity->{$fieldName};

            if ($assoc instanceof \Traversable) {
                foreach ($assoc as $ent) {
                    if ($ent instanceof BaseEntityAbstract) {
                        $this->entityManager->refresh($ent);
                    }
                }
            } elseif ($assoc instanceof BaseEntityAbstract) {
                $this->entityManager->refresh($assoc);
            }
        }

        return $this;
    }

    /**
     * Save the supplied entity.
     *
     * @param BaseEntityAbstract $entity
     *
     * @return BaseEntityAbstract
     */
    public function save(BaseEntityAbstract $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * Delete the specified entities by id.
     *
     * @param int|string|array $id
     *
     * @return int the number of rows affected
     */
    public function remove($id)
    {
        if (!$id) {
            return 0;
        }

        if ($id instanceof BaseEntityAbstract) {
            $this->entityManager->remove($id);

            $this->entityManager->flush();

            return 1;
        } else {
            if (!is_array($id)) {
                $id = array($id);
            }

            $qb = $this->entityManager->createQueryBuilder();
            $qb->delete($this->entityName, $this->alias)
                ->where($this->alias . self::TABLE_COLUMN_SEPARATOR . $this->identifier . ' IN (:ids)')
                ->setParameter('ids', $id);

            $result = $qb->getQuery()->execute();
        }

        return $result;
    }

    /**
     * Retrieve the event manager.
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Inject an EventManager instance.
     *
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * get the currently set Entity Manager.
     *
     * @return \Doctrine\ORM\EntityRepository The repository class.
     */
    public function getRepository($entityName = null)
    {
        return $this->getEntityManager()->getRepository($entityName == null ? $this->getEntityName() : $entityName);
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Return the alias used in the completeQueryBuilder.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Return a complete query builder without the defaults joins.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder()
            ->select($this->alias)
            ->from($this->entityName, $this->alias);
    }

    /**
     * Return a complete query builder with the defaults joins.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCompleteQueryBuilder()
    {
        return $this->getQueryBuilder();
    }

    /**
     * Simplify the createQueryBuilder proccess.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->entityManager
            ->createQueryBuilder();
    }

    /**
     * Build and add a limit and offset for the query builder.
     *
     * @param \Doctrine\Orm\QueryBuilder $qb
     * @param int $limit
     * @param int $offset
     *
     * @return $this
     */
    protected function addLimit($qb, $limit, $offset)
    {
        // add the limit and offset
        if ($limit) {
            $qb->setMaxResults($limit);

            if ($offset) {
                $qb->setFirstResult($offset);
            }
        }

        return $this;
    }

    /**
     * @param $columnName
     * @param bool $escapeColumnSeparator
     *
     * @return mixed|string
     */
    public function createColumnName($columnName, $escapeColumnSeparator = false)
    {

        // remove acessos encadeados de objeto, exemplo: alvara.custodiado.nome => custodiado.nome
        if (strpos($columnName, self::TABLE_COLUMN_SEPARATOR) !== false) {
            $coluna = $columnName;
        } else {
            $coluna = $this->getAlias() . self::TABLE_COLUMN_SEPARATOR . $columnName;
        }

        if ($escapeColumnSeparator) {
            $coluna = str_replace(self::TABLE_COLUMN_SEPARATOR, '\\.', $coluna);
        }

        return $coluna;
    }
}
