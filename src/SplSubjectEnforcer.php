<?php

namespace xianghua_we\dbeventObserver;

use xianghua_we\dbeventObserver\interfaces\SplObserver;

/**
 * 观察者模型 被观察者的接口实现
 */
trait SplSubjectEnforcer
{
    /** @var SplObserver[] */
    protected $observers = [];
    protected $splUpdateData = [];
    protected $splPrimaryId = null;
    protected $splOldData = [];
    protected $splNewData = [];
    protected $subjectData = [];
    protected static $splJsonFields = [
        'json'
    ];


    /**
     * @inheritDoc
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[md5(serialize($observer))] = $observer;
    }

    /**
     * 对比出已经修改的属性
     * @param $old
     * @param $new
     * @return array
     */
    public function splDataChanged($old, $new): array
    {
        $this->setSplNewData($new);
        $this->setSplOldData($old);
        $changed = [];
        // 对比json数据字段
        foreach ($new as $newKey => $newValue) {
            if (in_array($newKey, self::$splJsonFields)) {
                $newJsonFields = json_decode($newValue, true);
                $oldJsonFields = json_decode($old[$newKey] ?? '{}', true);
                foreach ($newJsonFields as $newJsonKey => $newJsonValue) {
                    if (
                        !isset($oldJsonFields[$newJsonKey])
                        || $oldJsonFields[$newJsonKey] != $newJsonValue
                    ) {
                        $changed[$newJsonKey] = $newJsonValue;
                    }
                }
                continue;
            }
            if (
                !isset($old[$newKey])
                || $old[$newKey] != $newValue
            ) {
                $changed[$newKey] = $newValue;
            }
        }
        $this->setSplUpdateData($changed);
        return $changed;
    }

    /**
     * @inheritDoc
     */
    public function detach(SplObserver $observer)
    {
        unset($this->observers[md5(serialize($observer))]);
    }

    /**
     * @inheritDoc
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @inheritDoc
     */
    public function getSplUpdateData(): array
    {
        return $this->splUpdateData;
    }

    /**
     * 获得数据主键
     * @inheritDoc
     */
    public function getSplPrimaryId()
    {
        return $this->splPrimaryId;
    }

    /**
     * @inheritDoc
     */
    public function setSplPrimaryId($splPrimaryId): void
    {
        $this->splPrimaryId = $splPrimaryId;
    }

    /**
     * 获得老数据
     * @inheritDoc
     */
    public function getSplOldData(): array
    {
        return $this->splOldData;
    }


    /**
     * 获得新数据
     * @inheritDoc
     */
    public function getSplNewData(): array
    {
        return $this->splNewData;
    }

    /**
     * 设置老数据
     * @param array $splOldData
     * @return void
     */
    private function setSplOldData(array $splOldData): void
    {
        $this->splOldData = $splOldData;
    }

    /**
     * 设置新数据
     * @param array $splNewData
     * @return void
     */
    private function setSplNewData(array $splNewData): void
    {
        $this->splNewData = $splNewData;
    }

    /**
     * 设置更新的数据
     * @param array $splUpdateData
     * @return void
     */
    private function setSplUpdateData(array $splUpdateData): void
    {
        $this->splUpdateData = $splUpdateData;
    }

    /**
     * 设置当前对象的完整data
     */
    public function setSubjectData($subjectData): void
    {
        $this->subjectData = $subjectData;
    }

    /**
     * 获得当前对象的完整data
     * @return array
     */
    public function getSubjectData(): array
    {
        return $this->subjectData;
    }

}
