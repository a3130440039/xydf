define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'data/shopdata/index',
                    table: 'shop',
                }
            });

            var table = $("#table");

            // 初始化表格
           table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                searchFormVisible: true,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true, operate:false},
                        {field: 'username', title: __('用户名'), operate:false},
                        {field: 'nickname', title: __('用户昵称'), operate:false},
                        {field: 'dur', title: __('时间段'),  operate:'BETWEEN', addclass:'datetimepicker'},
                        {field: 'all', title: __('下单金额'), operate:false},
                        {field: 'deal', title: __('成功金额'), operate:false},
                        {field: 'rate', title: __('成功率'), operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            $('.datetimepicker').attr('data-date-format',"YYYY-MM-DD");
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
