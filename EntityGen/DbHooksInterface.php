<?php
namespace JPF\EntityGen;

interface DbHooksInterface {
	public function PutHook($tableName, $id);
	public function UpdateHook($tableName, $id);
	public function DeleteHook($tableName, $id);
	public function FindHook($tableName, $id);

 }