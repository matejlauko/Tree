<?php

namespace Tree\Tree;
use Tree\Node\Node;
use Tree\Node\NodeInterface;


/**
 * Tree
 *
 * @package   Tree
 * @author Matej Lauko
 */
class Tree implements TreeInterface
{

	/**
	 * Points to the current node by it's id in $nodes
	 * @var int
	 */
	private $pointer = 0;

	/**
	 * Array of nodes
	 * @var NodeInterface[]
	 */
	private $nodes = array();

	/**
	 * @param NodeInterface $rootNode
	 */
	public function __construct(NodeInterface $rootNode = null)
	{
		if (!$rootNode) {
			$rootNode = new Node('root');
		}
		$rootNode->setId(0);

		$this->nodes[0] = $rootNode;
	}

	/**
	 * {@inheritdoc}
	 */
	public function addNode(NodeInterface $node, NodeInterface $parent = null, $onlyCurrentNode = false)
	{
		$this->_setIdToNode($node);

		$this->nodes[$node->getId()] = $node;

		if (!$onlyCurrentNode) {
			$this->_addAncestors($node);
			$this->_addDescendants($node);
		}

		if ($parent != null) {
			$parent->addChild($node);
		} elseif (!$onlyCurrentNode) {
			$this->getTreeRoot()->addChild($node);
		}


		return $this;
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 */
	private function _addAncestors(NodeInterface $node)
	{
		/** @var NodeInterface $ancestorNode */
		foreach ($node->getAncestors() as $ancestorNode) {
			if (!$ancestorNode->getId() || !$this->_idExists($ancestorNode->getId())) {
				$this->addNode($ancestorNode, null, true);
			}
		}

		return $node;
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 */
	private function _addDescendants(NodeInterface $node)
	{
		/** @var NodeInterface $descendantNode */
		foreach ($node->getDescendants() as $descendantNode) {
			if (!$descendantNode->getId() || !$this->_idExists($descendantNode->getId())) {
				$this->addNode($descendantNode, null, true);
			}
		}

		return $node;
	}

	/**
	 * @param NodeInterface $node
	 * @return NodeInterface
	 */
	private function _setIdToNode(NodeInterface $node)
	{
		if ($node->getId() == null || $this->_idExists($node->getId())) {
			$node->setId(count($this->nodes));
		}
		return $node;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNodes()
	{
		$nodes = $this->nodes;
		unset($nodes[0]);
		return $nodes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNodesSorted()
	{
		$nodes = array();
		foreach ($this->nodes[0]->getDescendants() as $subNode) {
			$nodes[] = $subNode;
		}
		return $nodes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeNode($nodeId)
	{
		if (!is_int($nodeId)) {
			$nodeId = $nodeId->getId();
		}

		if (!$this->_idExists($nodeId)) {
			return false;
		}

		if ($this->nodes[$nodeId]->isChild()) {
			$this->nodes[$nodeId]->getParent()->removeChild($this->nodes[$nodeId]);
		}

		$this->_removeDescendants($this->nodes[$nodeId]);

		unset($this->nodes[$nodeId]);

		return $this;
	}

	private function _removeDescendants(NodeInterface $node)
	{
		/** @var NodeInterface $descendantNode */
		foreach ($node->getDescendants() as $descendantNode) {
			if ($descendantNode->getId() || $this->_idExists($descendantNode->getId())) {
				unset($this->nodes[$descendantNode->getId()]);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTreeRoot()
	{
		return $this->nodes[0];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRootNodes()
	{
		return $this->nodes[0]->getChildren();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNodeById($id)
	{
		return (isset($this->nodes[$id])) ? $this->nodes[$id] : false;
	}

	/**
	 * @return array
	 */
	private function _getNodeIds()
	{
		return array_keys($this->nodes);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	private function _idExists($id)
	{
		return in_array($id, $this->_getNodeIds());
	}

}