Redis
=====

Obsługa zapytań do redisa


```
// connect (def: localhost)
$redis = DbRedis::getInstance();

// select db
$redis->select(0);

// query
$redis->incr($key);
```
