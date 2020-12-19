define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'shop/order/index',
                    add_url: 'shop/order/add',
                    //edit_url: 'shop/order/edit',
                    multi_url: 'shop/order/multi',
                    table: 'product',
                }
            });

            var table = $("#table");

            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                $("#all").text(data.tj.all);
                $("#deal").text(data.tj.deal);
                $("#rate").text(data.tj.rate);
            });

            // 初始化表格
           table.bootstrapTable({
               url: $.fn.bootstrapTable.defaults.extend.index_url,
               pk: 'id',
               sortName: 'order.id',
               searchFormVisible: true,
               search:false,
               columns: [
                   [
                       {checkbox: true},
                       {field: 'id', title: __('Id'), operate:false},
                       {field: 'order_sn', title: __('订单号')},
                       {field: 'part_sn', title: __('四方订单号')},
                       {field: 'tb_order_sn', title: __('闲鱼单号')},
                       {field: 'groups.tb_name', title: __('店铺名称'),operate:'like'},
                       {field: 'groups.remark', title: __('备注'),operate:'like'},
                       {field: 'order_sum', title: __('订单金额'), operate:false},
                       //{field: 'order_total', title: __('订单总金额')},
                       {field: 'group.username', title: __('用户'),operate:'like'},
                       {field: 'pay_date', title: __('支付时间'), formatter: Table.api.formatter.datetime,type: 'datetime',operate:false},
                       {field: 'pay_time', title: __('支付时间'), visible:false,formatter: Table.api.formatter.datetime,type: 'datetime',addclass:'datetimepicker',operate:'BETWEEN'},
                       {field: 'expire_time', title: __('过期时间'),formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD HH:mm:ss",type: 'datetime',operate:false},
                       {field: 'order.ctime', title: __('创建时间'),visible:false,formatter: Table.api.formatter.datetime, type: 'datetime',addclass:'datetimepicker',operate:'BETWEEN'},
                       {field: 'ctime', title: __('创建时间'),formatter: Table.api.formatter.datetime, type: 'datetime',operate:false},
                       {field: 'order_status', title: __('支付状态'), searchList: {'0': __('未支付'), '1': __('已支付'), '2':__('已过期')}, formatter: Table.api.formatter.label},
                       {field: 'notify_time', title: __('回调时间'),formatter: Table.api.formatter.datetime, datetimeFormat:"YYYY-MM-DD HH:mm:ss",type: 'datetime',operate:false},
                       {field: 'is_success', title: __('回调状态'), searchList: {'0': __('未回调'), '1': __('已回调'), '2':__('回调失败')}, formatter: Table.api.formatter.label},
                       {field: 'hand_notify', title: __('回调方式'), searchList: {'0': __(''), '1': __('手动')}, formatter: Table.api.formatter.label},
                       {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                               buttons: [
                                   {
                                       name: 'ajax',
                                       text: __('回调'),
                                       title: __('回调'),
                                       classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                       url: 'shop/order/callback',
                                       confirm: '确认回调',
                                       hidden:function(row){
                                           if(row.is_success == 1){
                                               return true;
                                           }
                                       },
                                       success: function (data, ret) {
                                           $(".btn-refresh").trigger("click");
                                       },
                                       error: function (data, ret) {
                                           Layer.alert(ret.msg);
                                           return false;
                                       }
                                   },
                               ]
                           }
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
        callback: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        },
    };

    return Controller;
});