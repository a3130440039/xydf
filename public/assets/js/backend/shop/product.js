define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop/product/index',
                    add_url: 'shop/product/add',
                    import_url: 'shop/product/import',
                    edit_url: 'shop/product/edit',
                    del_url: 'shop/product/del',
                    multi_url: 'shop/product/multi',
                    table: 'product',
                }
            });

            var table = $("#table");

            // 初始化表格
           table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                searchFormVisible: true,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'tb_name', title: __('闲鱼店铺'), operate:'like'},
                        {field: 'shop.remark', title: __('备注'), operate:false},
                        {field: 'tb_order_sn', title: __('闲鱼单号')},
                        {field: 'tb_qr', title: __('订单二维码'),operate:false},
                        {field: 'tb_qr_url', title: __('二维码链接'),operate:false},
                        {field: 'sum', title: __('商品金额'),operate:false},
                        {field: 'ctime', title: __('创建时间'),formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'expire_time', title: __('过期时间'),formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'is_sale', title: __('上下架'),  formatter: Table.api.formatter.toggle, searchList: {1: __('上架'), 0: __('下架')}, },
                        {field: 'is_expire', title: __('是否过期'),searchList: {1: __('未过期'), 0: __('已过期')},formatter: Table.api.formatter.label},
                        {field: 'is_lock', title: __('是否锁定'), searchList: {'0': __('未锁定'), '1': __('已锁定')}, formatter: Table.api.formatter.label, operate:false},
                        {field: 'is_payed', title: __('支付状态'), searchList: {0: __('未支付'), 1: __('已支付')},formatter: Table.api.formatter.label,operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        del: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});