<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$sth = $db->query('SELECT id, name, sku, image_url FROM products LIMIT 20');
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
	echo "$r[id] | $r[name] | $r[sku] | $r[image_url]" . PHP_EOL;
}
