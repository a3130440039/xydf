define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop/shop/index',
                    add_url: 'shop/shop/add',
                    del_url: 'shop/shop/del',
                    edit_url: 'shop/shop/edit',
                    clear_url: 'shop/shop/clear',
                    multi_url: 'shop/shop/multi',
                    table: 'shop',
                }
            });

            var table = $("#table");
            table.on('load-success.bs.table', function (e, data) {
                //统计核算显示到模板页面
                $("#day_num").remove();
                $("#deal_num").remove();
                $("#tongji").append('<span class="label label-default num" id="day_num" style="font-size: 100%"> 下单总额:'+data.day_num+'元</span>'
                                   +'           <span class="label label-success num" id="deal_num" style="font-size: 100%"> 支付总额:'+data.deal_num+'元</span>'
                );
            });

            // 初始化表格
           table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'shop.id',
                searchFormVisible: true,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true, operate:false},
                        {field: 'group.nickname', title: __('码商'), operate:false},
                        {field: 'tb_name', title: __('店铺名称'), operate:'LIKE'},
                        {field: 'remark', title: __('备注'), operate:false},
                        {field: 'token', title: __('商家token'), operate:false},
                        {field: 'day_limit', title: __('日限额'), operate:false},
                        {field: 'day_num', title: __('今日成交'), operate:false},
                        {field: 'deal_num', title: __('成功金额'), operate:false},
                        {field: 'rate', title: __('成功率'), operate:false},
                        {field: 'day', title: __('统计日期'), operate:false},
                        {field: 'shop_status', title: __('开启/关闭'), formatter: Table.api.formatter.toggle, searchList: {1: __('开启'), 0: __('关闭')}},
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
        clear: function () {
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
