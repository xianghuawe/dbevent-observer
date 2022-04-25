<?php

namespace xianghua_we\dbeventObserver\examples;

use xianghua_we\dbeventObserver\interfaces\SplObserver;
use xianghua_we\dbeventObserver\interfaces\SplSubject;

class ExampleObServer implements SplObserver
{

    public function update(SplSubject $subject): void
    {
        // TODO: Implement update() method.
        // 打印修改的数据
        var_dump($subject->getSplUpdateData());
    }
}
