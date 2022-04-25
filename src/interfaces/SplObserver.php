<?php

namespace xianghua_we\dbeventObserver\interfaces;

/**
 * 观察者模型 观察者接口
 */
interface SplObserver
{
    /**
     * 接收通知
     * @param SplSubject $subject
     * @return void
     */
    public function update(SplSubject $subject): void;
}
