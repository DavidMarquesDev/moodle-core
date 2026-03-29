define(['core/ajax', 'core/notification', 'core/str'], function(Ajax, Notification, Str) {
    var renderMetrics = function(container, metrics) {
        var lines = [
            '• ' + 'Cursos: ' + String(metrics.totalcourses || 0),
            '• ' + 'Concluídos: ' + String(metrics.completedcourses || 0),
            '• ' + 'Progresso: ' + String(metrics.completionpercent || 0) + '%',
            '• ' + 'Mensagens: ' + String(metrics.messagescount || 0)
        ];
        container.innerHTML = '<div>' + lines.join('</div><div>') + '</div>';
    };

    var renderMessages = function(container, messages) {
        if (!Array.isArray(messages) || messages.length === 0) {
            container.textContent = 'Sem mensagens recentes';
            return;
        }

        var rows = messages.map(function(item) {
            return '<li>' + String(item.message || '') + '</li>';
        });
        container.innerHTML = '<ul class="mb-0">' + rows.join('') + '</ul>';
    };

    var renderChart = function(container, points) {
        if (!Array.isArray(points) || points.length === 0) {
            container.textContent = container.dataset.emptyText || '';
            return;
        }

        var maxvalue = 1;
        points.forEach(function(item) {
            maxvalue = Math.max(maxvalue, Number(item.count || 0));
        });

        Str.get_strings([
            {key: 'chartdaylabel', component: 'block_meu_dashboard'},
            {key: 'chartcountlabel', component: 'block_meu_dashboard'}
        ]).then(function(strings) {
            var html = points.map(function(item) {
                var count = Number(item.count || 0);
                var width = Math.max(5, Math.round((count / maxvalue) * 100));
                return '<div class="mb-2"><div class="small">' +
                    strings[0] + ': ' + String(item.day) + ' | ' + strings[1] + ': ' + String(count) +
                    '</div><div class="border rounded" style="height:10px;"><div style="height:100%;background:#2563eb;width:' +
                    String(width) + '%;"></div></div></div>';
            }).join('');
            container.innerHTML = html;
        }).catch(function(error) {
            Notification.exception(error);
        });
    };

    var load = function(userid) {
        var metricscontainer = document.getElementById('block-meu-dashboard-metrics');
        var messagescontainer = document.getElementById('block-meu-dashboard-messages');
        var chartcontainer = document.getElementById('block-meu-dashboard-chart');

        if (!metricscontainer || !messagescontainer || !chartcontainer) {
            return;
        }

        var requests = Ajax.call([
            {
                methodname: 'block_meu_dashboard_get_dashboard_data',
                args: {userid: userid}
            },
            {
                methodname: 'block_meu_dashboard_get_recent_messages',
                args: {userid: userid, limit: 10}
            },
            {
                methodname: 'block_meu_dashboard_get_messages_series',
                args: {userid: userid, perioddays: 30}
            }
        ])[0].then(function(metrics) {
        ]);

        requests[0].then(function(metrics) {
            return Ajax.call([
        }).catch(function(error) {
            Notification.exception(error);
        });
    };

        requests[1].then(function(messages) {
            renderMessages(messagescontainer, messages);
            return null;
        }).catch(function(error) {
            Notification.exception(error);
        });

        requests[2].then(function(points) {
            renderChart(chartcontainer, points);
            return null;
        }).catch(function(error) {
            Notification.exception(error);
        });


    return {
        init: function(config) {
            load(Number((config && config.userid) || 0));
        }
    };
});
