<?php

namespace Core\Database;

abstract class Driver
{

    protected $_config;

    protected $_baseConfig = [];

    protected $_autoQuoting = false;

    public function __construct($config = [])
    {
        $config += $this->_baseConfig;
        $this->_config = $config;

        if (!empty($config['autoQuoting'])) {
            $this->enableAutoQuoting();
        }
    }

    public function supportsQuoting()
    {
        return true;
    }

    /*-----------------------------------------*/
    /*  Méthodes à ajouter dans chaque Driver  */
    /*-----------------------------------------*/
    abstract public function connect();

    abstract public function disconnect();

    abstract public function connection($connection = null);

    /**
     * Retourne si le driver peut être utilisé par PHP
     *
     * @return bool Vrai si PHP peut utiliser le driver
     */
    abstract public function enabled();

    abstract public function prepare($query);

}
