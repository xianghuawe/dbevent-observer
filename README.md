### 使用

#### 安装

```text
composer require xianghua_we/dbevent-observer
```

#### 被观察者装载接口和观察者实现

```php
<?php

namespace xianghua_we\dbeventObserver\examples;

use xianghua_we\dbeventObserver\interfaces\SplSubject;
use xianghua_we\dbeventObserver\SplSubjectEnforcer;

class ExampleModel implements SplSubject
{
    use SplSubjectEnforcer;
}

```

#### 业务观察者

```php
<?php

namespace xianghua_we\dbeventObserver\examples;

use xianghua_we\dbeventObserver\interfaces\SplObserver;
use xianghua_we\dbeventObserver\interfaces\SplSubject;

class ExampleObServer implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        // TODO: Implement update() method.
        // 打印修改的数据 根据要修改的数据,进行业务处理
        var_dump($subject->getSplUpdateData());
    }
}

```

#### 使用

```php
$eventLogs = [
    [
        'level' => 'info',
        'operate' => 'update',
        'record' =>
            [
                'level' => 'info',
                'operate' => 'update',
                'primary_id' => 7390,
                'primary_field' => 'id',
                'data' =>
                    [
                        'old' =>
                            [
                                'end_time' => 0,
                            ],
                        'new' =>
                            [
                                'end_time' => 1650873046,
                            ],
                    ],
                'param' =>
                    [
                        'table' => 'task',
                        'model' => 'Task',
                        'where' =>
                            [
                                'id' => 7390,
                            ],
                    ],
                'table' => 'task',
                'module_data' =>
                    [
                        'id' => 35,
                        'type' => 'fixed',
                        'name' => 'Task',
                        'code' => 'task',
                        'uuid' => '1aeb40b0-0071-11eb-94f6-119c9617d004',
                    ],
            ],
        'module_code' => 'task',
        'link_id' => 7390,
        'created_by' => '4fce9ce0-225e-11eb-b592-f3e550788657',
        'batch_number' => 'teamones-saas_e00f4d5b-6a7fb730-c46c-11ec-8188-379deb611ae2',
        'belong_system' => 'teamones-saas',
    ],
];
$exampleSubject = new \xianghua_we\dbeventObserver\examples\ExampleModel();

// 状态
$exampleSubject->attach(new \xianghua_we\dbeventObserver\examples\ExampleObServer());
// 框架产生的event update log

foreach ($eventLogs as $event) {
    $id = $event['link_id'];
    $event['record']['data']['old']['json'] = $event['record']['data']['old']['json'] ?? "[]";
    $event['record']['data']['new']['json'] = $event['record']['data']['new']['json'] ?? "[]";
    // 设置主键
    $exampleSubject->setSplPrimaryId($id);
    // 对比出修改的数据
    $exampleSubject->splDataChanged($event['record']['data']['old'], $event['record']['data']['new']);
    // 通知所有的观察者
    $exampleSubject->notify();

}

```
