<?php

namespace core\Database\Driver;

use Core\Database\Driver;
use core\Database\Query;
use PDO;

class Mysql extends Driver
{

    use MysqlDialectTrait;
    use PDODriverTrait;

    protected $_baseConfig = [
        'persistent' => true,
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'utaphp',
        'port' => '3306',
        'flags' => [],
        'encoding' => 'utf8',
        'timezone' => null,
        'init' => []
    ];

    protected $_version;

    protected $_supportsNativeJson;

    public function enabled()
    {
        return in_array('mysql', PDO::getAvailableDrivers());
    }

    /**
     * Prepares a sql statement to be executed
     *
     * @param string|Query $query The query to prepare.
     * @return \Cake\Database\StatementInterface
     */
    public function prepare($query)
    {
        $this->connect();
        $isObject = $query instanceof Query;
        $statement = $this->_connection->prepare($isObject ? $query->sql() : $query);
        $result = new MysqlStatement($statement, $this);

        if ($isObject && $query->isBufferedResultsEnabled() === false) {
            $result->bufferResults(false);
        }

        return $result;
    }

    public function connect()
    {
        if ($this->_connection) return true;

        $config = $this->_config;

        if ($config['timezone'] === 'UTC')
            $config['timezone'] = '+0:00';
        if (!empty($config['timezone']))
            $config['init'][] = sprintf("SET time_zone = '%s'", $config['timezone']);
        if (!empty($config['encoding']))
            $config['init'][] = sprintf('SET NAMES %s', $config['encoding']);

        $config['flags'] += [
            PDO::ATTR_PERSISTENT => $config['persistent'],
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        if (!empty($config['ssl_key']) && !empty($config['ssl_cert'])) {
            $config['flags'][PDO::MYSQL_ATTR_SSL_KEY] = $config['ssl_key'];
            $config['flags'][PDO::MYSQL_ATTR_SSL_CERT] = $config['ssl_cert'];
        }

        if (!empty($config['ssl_ca']))
            $config['flags'][PDO::MYSQL_ATTR_SSL_CA] = $config['ssl_ca'];

        if (empty($config['unix_socket']))
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['encoding']}";
        else
            $dsn = "mysql:unix_socket={$config['unix_socket']};dbname={$config['database']}";

        $this->_connect($dsn, $config);

        if (!empty($config['init'])) {
            $connection = $this->connection();

            foreach ((array)$config['init'] as $command)
                $connection->exec($command);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function schema()
    {
        return $this->_config['database'];
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDynamicConstraints()
    {
        return true;
    }

    /**
     * Returns true if the server supports native JSON columns
     *
     * @return bool
     */
    public function supportsNativeJson()
    {
        if ($this->_supportsNativeJson !== null) {
            return $this->_supportsNativeJson;
        }
        if ($this->_version === null) {
            $this->_version = $this->_connection->getAttribute(PDO::ATTR_SERVER_VERSION);
        }
        return $this->_supportsNativeJson = version_compare($this->_version, '5.7.0', '>=');
    }
}
