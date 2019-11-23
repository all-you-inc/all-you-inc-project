<?php



namespace common\modules\queue\interfaces;

/**
 * ExclusiveJobInterface can be added to an ActiveJob to ensure this task is only
 * queued once. As example this is useful for asynchronous jobs like search index rebuild.
 *
 * @see \common\modules\queue\ActiveJob
 * @author Luke
 */
interface ExclusiveJobInterface
{

    public function getExclusiveJobId();
}
