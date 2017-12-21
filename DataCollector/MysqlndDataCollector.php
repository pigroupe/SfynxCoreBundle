<?php
namespace Sfynx\CoreBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Sfynx\CoreBundle\Layers\Infrastructure\Mysqlnd\Engine;
use Sfynx\CoreBundle\Layers\Infrastructure\Mysqlnd\DefaultRuleProvider;
use Sfynx\CoreBundle\Layers\Infrastructure\Mysqlnd\Calculator;
use Sfynx\CoreBundle\Layers\Infrastructure\Mysqlnd\Collector;

/**
 * Data collector collecting mysqlnd statistics.
 */
class MysqlndDataCollector extends DataCollector
{
    private $analyticsCollector = false;

    /**
     * MysqlndDataCollector constructor.
     */
    public function __construct()
    {
        $this->startCollector();
    }

    public function onEarlyKernelRequest(GetResponseEvent $event)
    {
        if (!$this->analyticsCollector) {
            $this->startCollector();
        }
    }
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['stats'] = false;
        $this->data['mysqlnd_qc_trace'] = false;

        if ($this->analyticsCollector && Collector::canCollect()) {
            $this->data['stats'] = $this->analyticsCollector->collect();
        }
        if (function_exists('mysqlnd_qc_get_query_trace_log') && ini_get('mysqlnd_qc.collect_query_trace')) {
            $this->data['mysqlnd_qc_trace'] = mysqlnd_qc_get_query_trace_log();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mysqlnd';
    }

    /**
     * Returns the collected data to be used by the view.
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->data['stats'];
    }

    /**
     * @return mixed
     */
    public function getMysqlndQCTrace()
    {
        return $this->data['mysqlnd_qc_trace'];
    }

    /**
     * Dump information about mysqli.
     *
     * This is used by the view in case no statistics were collected to
     * ease the debugging
     *
     * @return string
     */
    public function getMysqlInfo()
    {
        if (!extension_loaded("mysqli")) {
            return "The mysqli extension is not available at all.";
        }

        ob_start();
        $re = new \ReflectionExtension("mysqli");
        $re->info();
        $info = ob_get_contents();
        ob_end_clean();

        return str_replace('<h2><a name="module_mysqli">mysqli</a></h2>', '', $info);
    }

    /**
     * @return Engine
     */
    public function getAnalytics()
    {
        return new Engine(
            new DefaultRuleProvider(),
            new Calculator($this->getStatistics())
        );
    }

    /**
     *
     */
    private function startCollector()
    {
        $this->analyticsCollector = new Collector();
        if (Collector::canCollect()) {
            $this->analyticsCollector->start();
        }
    }
}