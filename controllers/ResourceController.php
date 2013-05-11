<?php

Abstract class ResourceController extends AppController {

  abstract public function index($params);
  abstract public function show($params);
  abstract public function add($params);
  abstract public function create($params);
  abstract public function edit($params);
  abstract public function update($params);
  abstract public function delete($params);
  abstract public function setUpVars();

}

