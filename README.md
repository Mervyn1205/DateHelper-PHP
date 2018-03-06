# DateHelper

## Usage
```php
<?php
require 'vendor/autoload.php';
use DateHelper\DateHelper;

//获取当前年份
echo DateHelper::now()->getYear();

//获取指定日期对应周的周六
echo (new DateHelper('2018-02-11'))->saturday()->format("Y-m-d");

//获取指定日期对应周的周日
echo (new DateHelper('2018-02-11'))->sunday()->format("Y-m-d");

//以周为单位生成一个日期数组
$range = DateHelper::now()->generateDateRange('2018-01-13', '2018-02-10', 'week');

//初始化日期并格式化输出
echo DateHelper::createFromFormat('Y-m-d', '2018-02-10')->format("Y-m-d");

//格式化当前日期
echo DateHelper::now()->format("Y-m-d");

//格式化当前周的周一
echo DateHelper::now()->monday()->format("Y-m-d");

```