// ========= chart_department_line =======

if (typeof Chart !== "undefined") {
const departmentChartData = {
  labels: ["🏨 Rooms", "🍽 Restaurant", "🎉 Banquets"],
  datasets: [
    {
      label: "Leads by Department",
      data: [65, 45, 80],

      borderColor: "#9F8BE7",
      borderWidth: 5,

      backgroundColor: function (context) {
        const chart = context.chart;
        const { ctx, chartArea } = chart;

        if (!chartArea) {
          return "#9F8BE7";
        }

        const gradient = ctx.createLinearGradient(
          0,
          chartArea.top,
          0,
          chartArea.bottom,
        );

        gradient.addColorStop(0, "#9f8be798");
        gradient.addColorStop(0.5, "#9f8be737");
        gradient.addColorStop(1, "rgba(255,255,255,0)");

        return gradient;
      },

      fill: true,

      tension: 0.55,

      pointRadius: 8,
      pointHoverRadius: 12,

      pointBackgroundColor: "#ffffff",
      pointBorderColor: "#9F8BE7",
      pointBorderWidth: 4,

      cubicInterpolationMode: "monotone",
    },
  ],
};

const config = {
  type: "line",
  data: departmentChartData,
  options: {
    responsive: true,

    maintainAspectRatio: true,

    interaction: {
      mode: "index",
      intersect: false,
    },

    plugins: [
      {
        id: "labelShadow",

        beforeDraw(chart) {
          const ctx = chart.ctx;

          ctx.save();
          ctx.shadowColor = "rgba(0,0,0,.12)";
          ctx.shadowBlur = 4;
          ctx.shadowOffsetY = 2;
        },

        afterDraw(chart) {
          chart.ctx.restore();
        },
      },
    ],

    elements: {
      line: {
        borderJoinStyle: "round",
      },
    },

    scales: {
      x: {
        grid: {
          display: false,
          drawBorder: false,
        },

        border: {
          display: true,
        },

        ticks: {
          color: "#000",

          padding: 15,

          font: {
            size: 15,
            weight: "600",
            // family: "'Poppins', sans-serif"
          },
        },
      },

      y: {
        beginAtZero: true,

        grid: {
          color: "rgba(0,0,0,.06)",
        },

        ticks: {
          color: "#666",
        },
      },
    },
  },
};
new Chart(document.getElementById("chart_department_line"), config);


      // ======= = = chart_status_new = = ======
        const statusData = {

            labels: [
                'Open',
                'In Progress',
                'Closed',
                'Lost'
            ],

            datasets: [{

                data: [55, 32, 21, 12],

                borderWidth: 0,

                spacing: 6,

                hoverOffset: 18,

                cutout: '72%',

                borderRadius: 25,

                backgroundColor: function(context) {

                    const chart = context.chart;

                    const {
                        ctx,
                        chartArea
                    } = chart;

                    if (!chartArea) {

                        return [
                            '#3B82F6',
                            '#8B5CF6',
                            '#22C55E',
                            '#EF4444'
                        ];

                    }

                    function gradient(c1, c2) {

                        const g = ctx.createLinearGradient(0, 0, 0, chartArea.bottom);

                        g.addColorStop(0, c1);

                        g.addColorStop(1, c2);

                        return g;

                    }

                    return [

                        gradient('#60A5FA', '#2563EB'),

                        gradient('#C084FC', '#7C3AED'),

                        gradient('#4ADE80', '#16A34A'),

                        gradient('#FB7185', '#DC2626')

                    ];

                }

            }]

        };

        const centerText = {

            id: 'centerText',

            beforeDraw(chart) {

                const {
                    ctx
                } = chart;

                const meta = chart.getDatasetMeta(0);

                if (!meta.data.length) return;

                const x = meta.data[0].x;

                const y = meta.data[0].y;

                ctx.save();

                ctx.textAlign = 'center';

                ctx.fillStyle = '#111827';

                ctx.font = '700 34px Poppins';

                const total = chart.data.datasets[0].data.reduce(function(sum, value) {
                    return sum + (Number(value) || 0);
                }, 0);
                ctx.fillText(total.toLocaleString('en-IN'), x, y - 6);

                ctx.font = '500 15px Poppins';

                ctx.fillStyle = '#9F8BE7';

                ctx.fillText('Total Leads', x, y + 22);

                ctx.restore();

            }

        };

        const shadowPlugin = {

            id: 'shadow',

            beforeDatasetsDraw(chart) {

                const ctx = chart.ctx;

                ctx.save();

                ctx.shadowColor = 'rgba(0,0,0,.20)';

                ctx.shadowBlur = 30;

                ctx.shadowOffsetY = 12;

            },

            afterDatasetsDraw(chart) {

                chart.ctx.restore();

            }

        };
        new Chart(

            document.getElementById('chart_status_new'),

            {

                type: 'doughnut',

                data: statusData,

                plugins: [centerText, shadowPlugin],

                options: {

                    responsive: true,

                    maintainAspectRatio: true,

                    animation: {

                        animateRotate: true,

                        animateScale: true,

                        duration: 2500,

                        easing: 'easeOutElastic'

                    },

                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                padding: 25,

                                boxWidth: 16,

                                boxHeight: 16,

                                usePointStyle: true,

                                pointStyle: 'circle',

                                font: {

                                    size: 14,

                                    weight: 'bold'

                                }

                            }

                        }

                    }

                }

            }

        );


            //    <!-- ========= = = chart_stage_bar = = ======== -->
        const stageData = {

            labels: [

                "📞 Not Contacted",
                "📄 Quotation",
                "🤝 Negotiation",
                "📃 Contract",
                "💰 Advance",
                "🏆 Won",
                "❌ Lost"

            ],

            datasets: [{

                label: "Lead Stage",

                data: [35, 58, 76, 48, 65, 90, 18],

                borderRadius: 0,

                borderSkipped: false,

                barThickness: 50,

                hoverBorderWidth: 2,

                hoverBorderColor: "#ffffff",

                backgroundColor: function(context) {

                    const chart = context.chart;
                    const {
                        ctx,
                        chartArea
                    } = chart;

                    if (!chartArea) {

                        return "#4F46E5";

                    }

                    function gradient(c1, c2) {

                        const g = ctx.createLinearGradient(
                            chartArea.left,
                            0,
                            chartArea.right,
                            0
                        );

                        g.addColorStop(0, c1);
                        g.addColorStop(1, c2);

                        return g;

                    }

                    return [

                        gradient("#60A5FA", "#2563EB"),
                        gradient("#38BDF8", "#0284C7"),
                        gradient("#A78BFA", "#7C3AED"),
                        gradient("#FB923C", "#EA580C"),
                        gradient("#34D399", "#059669"),
                        gradient("#FACC15", "#EAB308"),
                        gradient("#FB7185", "#DC2626")

                    ];

                }

            }]

        };

        const stageConfig = {

            type: "bar",

            data: stageData,

            options: {

                // indexAxis:"y",   // ❌ Remove this line
                // ya
                indexAxis: "x", // ✅ Vertical Bar Chart

                responsive: true,

                maintainAspectRatio: true,

                animation: {
                    duration: 2200,
                    easing: "easeOutQuart"
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    title: {
                        display: true,
                        text: "Lead Stage Overview",
                        font: {
                            size: 20,
                            weight: "bold"
                        }
                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: "#111",
                            font: {
                                size: 13,
                                weight: "600"
                            }
                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: "rgba(0,0,0,.05)"
                        },

                        ticks: {
                            color: "#555"
                        }

                    }

                }

            }

        };

        new Chart(
            document.getElementById("chart_stage_bar"),
            stageConfig
        );





            // ========= = = chart_guest_type = = =========
        const ctx = document.getElementById("chart_guest_type").getContext("2d");

        // Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, "#9f8be745");
        gradient.addColorStop(1, "#9f8be702");

        new Chart(ctx, {

            type: "line",

            data: {

                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],

                datasets: [{

                    label: "Guest",

                    data: [65, 59, 80, 81, 56, 55, 40],

                    borderColor: "#9F8BE7",

                    backgroundColor: gradient,

                    fill: true,

                    tension: 0,

                    borderWidth: 3,

                    pointRadius: 5,

                    pointHoverRadius: 8,

                    pointBackgroundColor: "#fff",

                    pointBorderColor: "#9F8BE7",

                    pointBorderWidth: 3,

                    hoverBorderWidth: 4
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: "index"
                },

                plugins: {
                    title: {
                        display: true,
                        text: "Guest Type Trend",
                        color: "#000",
                        font: {
                            size: 20,
                            weight: "700"
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    legend: {
                        display: false
                    }
                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        },

                        ticks: {
                            color: "#000"
                        }

                    },

                    y: {

                        beginAtZero: true,

                        grid: {
                            color: "#9f8be741"
                        },

                        ticks: {
                            color: "#000"
                        }

                    }

                },

                animation: {

                    duration: 1800,

                    easing: "easeOutQuart"

                }

            }

        });





             // ========= = = sales_funnel_chart = = =============
        if (false) Highcharts.chart('sales_funnel_chart', {

            chart: {
                type: 'funnel',
                backgroundColor: 'transparent',
                spacingTop: 20,
                spacingBottom: 20,
                spacingLeft: 10,
                spacingRight: 20
            },

            title: {
                text: 'Sales Funnel',
                align: 'left',
                margin: 25,
                style: {
                    fontSize: '22px',
                    fontWeight: '700',
                    color: '#1f2937'
                }
            },
            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false
            },

            plotOptions: {

                series: {

                    width: '68%',
                    neckWidth: '45%',
                    neckHeight: '50%',

                    borderWidth: 3,
                    borderColor: '#ffffff',

                    dataLabels: {
                        enabled: true,
                        distance: 18,
                        softConnector: false,
                        connectorWidth: 2,
                        connectorColor: '#bdbdbd',
                        style: {
                            textOutline: 'none',
                            fontSize: '14px',
                            fontWeight: '600'
                        }
                    },

                    states: {
                        hover: {
                            brightness: 0.15
                        }
                    }

                }

            },

            colors: [

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5EA8FF'],
                        [1, '#2F6FE4']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5ED4FF'],
                        [1, '#2497E3']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#48E58C'],
                        [1, '#22C55E']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#A8F05C'],
                        [1, '#7ED321']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFD65A'],
                        [1, '#F4B400']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFA94D'],
                        [1, '#FF7A00']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FF5FA2'],
                        [1, '#E91E63']
                    ]
                }

            ],

            series: [{

                name: 'Leads',

                data: [

                    ['Enquiry', 4083],

                    ['Qualified', 2891],

                    ['Contacted', 2103],

                    ['Demo', 1203],

                    ['Negotiation', 563],

                    ['Quotation', 183],

                    ['Won', 84]

                ]

            }]

        });





            const ctxRevenue = document.getElementById('chart_revenue_vs_leads');

new Chart(ctxRevenue, {
    data: {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul"
        ],

        datasets: [

            {
                type: 'bar',
                label: 'Leads',

                data: [45, 62, 78, 55, 83, 95, 70],

                backgroundColor: [
                    '#8B5CF6',
                    '#60A5FA',
                    '#34D399',
                    '#F59E0B',
                    '#FB7185',
                    '#06B6D4',
                    '#A78BFA'
                ],

                borderRadius: 10,

                barThickness: 26
            },

            {
                type: 'line',

                label: 'Revenue',

                data: [25, 38, 55, 48, 70, 92, 80],

                borderColor: '#23211D',

                backgroundColor: '#23211D',

                borderWidth: 3,

                tension: .45,

                fill: false,

                pointRadius: 5,

                pointHoverRadius: 7,

                pointBackgroundColor: '#23211D'
            }

        ]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                position: 'top',

                labels: {

                    usePointStyle: true,

                    boxWidth: 10
                }

            },

            title: {

                display: true,

                text: 'Leads vs Revenue'

            }

        },

        scales: {

            y: {

                beginAtZero: true,

                grid: {

                    color: '#ECECEC'

                }

            },

            x: {

                grid: {

                    display: false

                }

            }

        }

    }

});
}




            // ========= = = sales_funnel_chart = = =============
        if (false) Highcharts.chart('sales_funnel_chart', {

            chart: {
                type: 'funnel',
                backgroundColor: 'transparent',
                spacingTop: 20,
                spacingBottom: 20,
                spacingLeft: 10,
                spacingRight: 20
            },

            title: {
                text: 'Sales Funnel',
                align: 'left',
                margin: 25,
                style: {
                    fontSize: '22px',
                    fontWeight: '700',
                    color: '#1f2937'
                }
            },
            credits: {
                enabled: false
            },

            exporting: {
                enabled: false
            },

            legend: {
                enabled: false
            },

            plotOptions: {

                series: {

                    width: '68%',
                    neckWidth: '45%',
                    neckHeight: '50%',

                    borderWidth: 3,
                    borderColor: '#ffffff',

                    dataLabels: {
                        enabled: true,
                        distance: 18,
                        softConnector: false,
                        connectorWidth: 2,
                        connectorColor: '#bdbdbd',
                        style: {
                            textOutline: 'none',
                            fontSize: '14px',
                            fontWeight: '600'
                        }
                    },

                    states: {
                        hover: {
                            brightness: 0.15
                        }
                    }

                }

            },

            colors: [

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5EA8FF'],
                        [1, '#2F6FE4']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#5ED4FF'],
                        [1, '#2497E3']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#48E58C'],
                        [1, '#22C55E']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#A8F05C'],
                        [1, '#7ED321']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFD65A'],
                        [1, '#F4B400']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FFA94D'],
                        [1, '#FF7A00']
                    ]
                },

                {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 1,
                        y2: 1
                    },
                    stops: [
                        [0, '#FF5FA2'],
                        [1, '#E91E63']
                    ]
                }

            ],

            series: [{

                name: 'Leads',

                data: [

                    ['Enquiry', 4083],

                    ['Qualified', 2891],

                    ['Contacted', 2103],

                    ['Demo', 1203],

                    ['Negotiation', 563],

                    ['Quotation', 183],

                    ['Won', 84]

                ]

            }]

        });



        (function() {
            const funnelStages = [{
                    name: 'Enquiry',
                    value: 4083,
                    color: '#3b82f6',
                    width: 100
                },
                {
                    name: 'Qualified',
                    value: 2891,
                    color: '#38bdf8',
                    width: 91
                },
                {
                    name: 'Contacted',
                    value: 2103,
                    color: '#34d399',
                    width: 81
                },
                {
                    name: 'Demo',
                    value: 1203,
                    color: '#62c76b',
                    width: 70
                },
                {
                    name: 'Negotiation',
                    value: 563,
                    color: '#f7ad20',
                    width: 58
                },
                {
                    name: 'Quotation',
                    value: 183,
                    color: '#f57c2d',
                    width: 46
                },
                {
                    name: 'Won',
                    value: 84,
                    color: '#e83d87',
                    width: 34
                }
            ];

            const funnelTarget = document.getElementById('sales_funnel_chart');
            const funnelBase = funnelStages[0].value;

            if (!funnelTarget) return;

            const funnelSteps = funnelStages.map(function(stage) {
                return '<div class="sales-funnel-step" style="width:' + stage.width + '%;background:' +
                    stage.color + '"></div>';
            }).join('');

            const funnelLegend = funnelStages.map(function(stage) {
                const percentage = (stage.value / funnelBase * 100).toFixed(1).replace('.0', '');
                return '<div class="sales-funnel-legend__item">' +
                    '<span class="sales-funnel-legend__dot" style="background:' + stage.color +
                    '"></span>' +
                    '<span>' + stage.name + '</span>' +
                    '<strong class="sales-funnel-legend__value">' + stage.value.toLocaleString('en-IN') +
                    ' (' + percentage + '%)</strong>' +
                    '</div>';
            }).join('');

            funnelTarget.innerHTML = '<section class="sales-funnel-card" aria-label="Sales Funnel">' +
                '<h3 class="sales-funnel-card__title">Sales Funnel</h3>' +
                '<div class="sales-funnel-card__content">' +
                '<div class="sales-funnel-visual" aria-hidden="true">' + funnelSteps + '</div>' +
                '<div class="sales-funnel-legend">' + funnelLegend + '</div>' +
                '</div></section>';
        })();


