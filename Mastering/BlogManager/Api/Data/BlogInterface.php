<?php
namespace Mastering\BlogManager\Api\Data;

interface BlogInterface
{
    const ENTITY_ID 		= 'entity_id';
    const TITLE 			= 'title';
    const CONTENT		    = 'content';
    const STATUS 			= 'status';
    const CREATED_AT		= 'created_at';
    const UPDATED_AT		= 'updated_at';
    
	public function getId();

	public function getTitle();

    public function getDescription();

    public function getStatus();
	
    public function getCreatedAt();

    public function getUpdatedAt();
	
    public function setId($id);
	
	public function setTitle($title);
	
    public function setDescription($content);
	
    public function setStatus($status);
	
    public function setCreatedAt($created_at);

    public function setUpdatedAt($updated_at);

}
