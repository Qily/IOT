<!--<?php
defined('IN_MET') or exit('No permission');

$title = '数据异常';
require_once $this->template('own/header');
echo <<<EOT
-->





<div class="col-md-8">
    <div class="row">
        <table class="table table-overflow">
            <thead>
                <tr>
                    <th></th>
                    <th>名称</th>
                    <th>当前值</th>
                    <th>位置</th>
                    <th>操作</th>
            </thead>

            <tbody>
                <tr>
                    <td>aa</td>
                    <td>bb</td>
                    <td>>0</div></td>
                    <td>cc</td>
                    <td><a class="btn btn-warning">修改</a>
                        <a class="btn btn-danger"  href="javascript:if(confirm('确定删除？'))location='{$urlUserdata}a='">删除</a>
                    </td>
                </tr>
            </tbody>
    </table>
    </div>
</div>

<div class="col-md-1"></div>




<!--
EOT;
require_once $this->template('own/footer');
?>