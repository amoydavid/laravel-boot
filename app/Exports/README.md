# 导出数据类

## 文档

[文档原文](https://docs.laravel-excel.com/3.1/exports/)

## 命令行

```shell
php artisan make:export UsersExport --model=User
```

## 正确使用

一般通过 query 来生成导出类，不然数据量比较大会挂

## 例子

```php
<?php

namespace App\Exports;

use App\Models\DictValue;
use App\Models\WxUser as Customer;
use App\Util\Helper;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CustomerExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings
{
    use Exportable;

    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            '#',
            '姓名',
            '手机号',
        ];
    }

    /**
     * @var Customer $customer
     */
    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->tel,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, //指定为text模式
        ];
    }

    public function query()
    {
        // 生成 query
        return Customer::filter($this->request);
    }
}

```

