<?php
namespace Model;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Exception;
use Everyman\Neo4j\Index\NodeIndex;
use Everyman\Neo4j\Cypher;
/**
 * Class Base
 * @package Model
 */
abstract class Base
{
    const NAME = 'name';
    const DATE = 'date';
    const TYPE = 'type';

    /**
     * @var Client|null
     */
    protected $client = null;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * construct the Base
     */
    public function __construct($data = array())
    {
        $this->client = new Client();
        $this->actors = new NodeIndex($this->client, 'actors');
        $this->data = $data;
    }

    /**
     * make a new node
     * @param array $input
     * @param null $type
     * @return \Everyman\Neo4j\PropertyContainer
     * @throws Exception
     */
    public function makeNode($input = array(), $type = null)
    {
        $node = $this->client
                    ->makeNode()
                    ->setProperties(
                        array(
                            static::NAME => $input[static::NAME],
                            static::TYPE => $type
                        )
                    )
                    ->save();
        $this->actors->add($node, static::NAME, $node->getProperty(static::NAME));
        return $node;
    }

    /**
     * set relationship of two nodes
     * @param $nodeA
     * @param $nodeB
     * @param $name
     * @return mixed
     */
    public function setRelationship($nodeA, $nodeB, $name)
    {
        $date = date('G:i:s d-m-Y');
        return $nodeA->relateTo($nodeB, $name)
            ->setProperty(static::DATE, $date)
            ->save();
    }

    /**
     * get output, should be json
     * @return mixed
     */
    abstract function getOutput();

    /**
     * use Cypher to query
     * @param $queryTemplate
     * @param array $data
     * @return \Everyman\Neo4j\Query\ResultSet
     */
    public function query($queryTemplate)
    {
        $query = new Cypher\Query($this->client, $queryTemplate);
        return $query->getResultSet();
    }

    /**
     * get nodes by property
     * @param $name
     * @param $value
     * @return \Everyman\Neo4j\Query\ResultSet
     */
    public function getByProperty($name, $value)
    {
        $queryTemplate = "MATCH (n) WHERE n.`$name` = '$value' RETURN n";
        return $this->query($queryTemplate);
    }

    /**
     * get or create new node
     * @param $value
     * @param $type
     * @return \Everyman\Neo4j\PropertyContainer|int
     */
    public function getOrCreateNode($value, $type)
    {
        try {
            $node = $this->actors->findOne(static::NAME, $value);
            if ($node) {
                return $node;
            } else {
                return $this->makeNode(array(static::NAME => $value), $type);
            }
        } catch (Exception $e) {
            return $this->makeNode(array(static::NAME => $value), $type);
        }
    }

    /**
     * @return Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    public function test1($var)
    {
        return ++$var;
    }
}
