<?php

namespace Tree\Tree;
use Tree\Node\NodeInterface;

/**
 * Interface for the Tree
 *
 * @package   	Tree
 * @author 		Matej Lauko
 */
interface TreeInterface
{

	/**
	 * Add and returns a node with the given properties
	 *
	 * @param NodeInterface $node
	 * @param NodeInterface|null $parent
	 * @param bool $onlyCurrentNode
	 * @return Tree
	 */
	public function addNode(NodeInterface $node, NodeInterface $parent = null, $onlyCurrentNode = false);

	/**
	 * Returns a flat array of all node objects in the tree.
	 *
	 * @return NodeInterface[] Nodes in normal order without the root node
	 */
	public function getNodes();

	/**
	 * Returns a flat, sorted array of all node objects in the tree.
	 *
	 * @return NodeInterface[] Nodes, sorted as if the tree was hierarchical,
	 *                i.e.: the first level 1 item, then the children of
	 *                the first level 1 item (and their children), then
	 *                the second level 1 item and so on.
	 */
	public function getNodesSorted();

	/**
	 * Removes node by id or by reference. Also removes all children of node and reference to it from it's parent
	 * @param int|NodeInterface $nodeId
	 * @return Tree
	 */
	public function removeNode($nodeId);

	/**
	 * Returns a single node from the tree, identified by its ID.
	 *
	 * @param int $id Node ID
	 *
	 * @return NodeInterface|null
	 */
	public function getNodeById($id);

	/**
	 * Returns an array of all nodes in the root level
	 *
	 * @return NodeInterface[]
	 */
	public function getRootNodes();

	/**
	 * Returns the root node of the tree
	 *
	 * @return NodeInterface
	 */
	public function getTreeRoot();


}