<?php

$orm = getRabbit();

// write
$data = new XData();
$orm($data)();

// read
$obj = $orm(XData::class)(); // blocks
// obj == data