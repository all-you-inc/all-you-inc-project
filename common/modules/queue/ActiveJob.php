<?php

namespace common\modules\queue;

use yii\base\Object;
use common\modules\queue\interfaces\JobInterface;

/**
 * ActiveJob
 * 
 * @since 1.3
 * @author Luke
 */
abstract class ActiveJob extends Object implements JobInterface
{

    /**
     * Runs this job
     */
    abstract public function run();

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        
        return $this->run();
    }

}
