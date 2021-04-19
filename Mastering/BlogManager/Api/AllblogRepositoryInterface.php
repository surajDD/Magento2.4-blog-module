<?php
namespace Mastering\BlogManager\Api;

interface AllblogRepositoryInterface
{
	public function save(\Mastering\BlogManager\Api\Data\BlogInterface $blog);

    public function getById($entityId);

    public function delete(\Mastering\BlogManager\Api\Data\BlogInterface $blog);

    public function deleteById($entityId);
}
