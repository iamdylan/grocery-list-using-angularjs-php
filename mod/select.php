<?php
try {
    $host = getenv('HOST');
    $user = getenv('USERNAME');
    $pass = getenv('PASSWORD');
    $dbname = getenv('DB_NAME');

    $dsn = 'mysql:host='. $host .';dbname='. $dbname;

    $objDb = new PDO($dsn, $user, $pass);
    $objDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `i`.*,
            `t`.`name` AS `type_name`
            FROM `items` `i`
            JOIN `types` `t`
              ON `t`.`id` = `i`.`type`
            ORDER BY `i`.`date` ASC";

    $result = $objDb->query($sql);

    if (!$result) {
        throw new PDOException('The result returned no object');
    }

    $result->setFetchMode(PDO::FETCH_ASSOC);

    $items = $result->fetchAll();

    $sql = "SELECT *
            FROM `types`
            ORDER BY `id`";

    $result = $objDb->query($sql);

    if (!$result) {
        throw new PDOException('The result returned no object');
    }

    $result->setFetchMode(PDO::FETCH_ASSOC);

    $types = $result->fetchAll();
    
    asort($types);

    echo json_encode(array(
        'error' => false,
        'items' => $items,
        'types' => $types
    ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

} catch(PDOException $e) {
    echo json_encode(array(
        'error' => true,
        'message' => $e->getMessage()
    ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}
