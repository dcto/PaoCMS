<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class DB
 *
 * @method static DB connection(string $connection = 'default');
 * @method static DB extend(string $name, callable $resolver)
 * @method static Illuminate\Database\Eloquent\Builder table(string $table, string $connection = 'default')
 * @method static \Illuminate\Database\Query\Expression raw(string $table, string $connection = 'default')
 * @method static DB select(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static DB cursor(string $query, array $bindings = [], bool $useReadPdo = true)
 * @method static DB insert(string $query, array $bindings = [])
 * @method static DB update(string $query, array $bindings = [])
 * @method static DB delete(string $query, array $bindings = [])
 * @method static DB selectOne(string $query, array $bindings = [])
 * @method static DB selectFromWriteConnection(string $query, array $bindings = [])
 * @method static DB transaction(Closure $callback)
 * @method static DB beginTransaction()
 * @method static DB rollBack()
 * @method static DB commit()
 * @method static DB transactionLevel()
 * @method static DB affectingStatement(string $query, array $bindings = [])
 * @method static DB useDefaultPostProcessor()
 * @method static DB useDefaultSchemaGrammar()
 * @method static DB useDefaultQueryGrammar()
 * @method static DB unprepared(string $query)
 * @method static DB prepareBindings(array $bindings)
 * @method static DB pretend(Closure $callback)
 * @method static Illuminate\Database\Schema\Builder schema(string $connection = 'default')
 * @method static DB getConnection(string $name = 'default');
 * @method static DB addConnection(array $config, $name = 'default')
 * @method static DB bootEloquent()
 * @method static Illuminate\Database\Capsule\Manager setFetchMode($fetchMode)
 * @method static DB getDatabaseManager()
 * @method static DB getEventDispatcher()
 * @method static DB setEventDispatcher()
 * @method static DB isDoctrineAvailable()
 * @method static DB getDoctrineColumn()
 * @method static DB getDoctrineConnection()
 * @method static DB getDoctrineSchemaManager()
 * @method static DB getPdo()
 * @method static DB getReadPdo()
 * @method static DB setPdo(\PDO $pdo)
 * @method static DB setReadPdo(\PDO $pdo)
 * @method static DB setReconnector(callable $reconnector)
 * @method static DB listen(Closure $callback)
 * @method static DB getName()
 * @method static DB getConfig(string $option)
 * @method static DB getDriverName()
 * @method static DB getQueryGrammar()
 * @method static DB setQueryGrammar(\Illuminate\Database\Query\Grammars\Grammar $grammar)
 * @method static \Illuminate\Database\Schema\Grammars\Grammar getSchemaGrammar()
 * @method static \Illuminate\Database\Schema\Grammars\Grammar setSchemaGrammar(\Illuminate\Database\Schema\Grammars\Grammar $grammar)
 * @method static \Illuminate\Database\Query\Processors\Processor getPostProcessor()
 * @method static \Illuminate\Database\Query\Processors\Processor setPostProcessor(\Illuminate\Database\Query\Processors\Processor  $processor)
 * @method static DB pretending()
 * @method static DB getFetchMode()
 * @method static DB getFetchArgument()
 * @method static DB getFetchConstructorArgument()
 * @method static DB getQueryLog()
 * @method static DB flushQueryLog()
 * @method static DB enableQueryLog()
 * @method static DB disableQueryLog()
 * @method static DB getDatabaseName()
 * @method static DB setDatabaseName(string $database)
 * @method static DB getTablePrefix()
 * @method static DB setTablePrefix(string $prefix)
 * @method static DB withTablePrefix(\Illuminate\Database\Grammar $grammar)
 * @method static DB logging()
 * @method static DB logQuery()
 * @method static DB reconnect()
 * @method static DB disconnect()
 */
class DB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'db';
    }
}
