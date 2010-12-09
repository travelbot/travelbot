<?php

use Doctrine\DBAL\Logging\SQLLogger;
use Nette\Debug;

class NetteLogger extends Nette\Object implements SQLLogger
{
	/**
	 * Logs a SQL statement somewhere.
	 *
	 * @param string $sql The SQL to be executed.
	 * @param array $params The SQL parameters.
	 * @param float $executionMS The microtime difference it took to execute this query.
	 * @return void
	 */
	public function logSQL($sql, array $params = null, $executionMS = null)
	{
		Debug::fireLog(
			array($sql, // table title
			array(
				array_keys($params), // table header
				$params), // 1. row
			), 'TABLE'
		);
	}

    /**
     * Logs a SQL statement somewhere.
     *
     * @param string $sql The SQL to be executed.
     * @param array $params The SQL parameters.
     * @param float $executionMS The microtime difference it took to execute this query.
     * @return void
     */
	public function startQuery($sql, array $params = null, array $types = null)
	{
		$this->logSQL($sql, $params);
	}

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
	public function stopQuery()
	{

	}
}
