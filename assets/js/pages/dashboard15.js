$(function () {

  'use strict';

  
    var customerData = {
    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov" ],
    datasets: [{
      label: 'New Tickets',
      data: [21, 34, 44, 34, 26, 22, 19, 15],
      backgroundColor: [
        '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#198754', '#e4e4e4', '#e4e4e4', '#e4e4e4',
      ],
      borderColor: [
        '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#198754', '#e4e4e4', '#e4e4e4', '#e4e4e4',
      ],
      borderWidth: 1,
      fill: false
    }
    ]
  };
  var customerOptions = {
    scales: {
      xAxes: [{
      barPercentage: 1,
      position: 'bottom',
      display: true,
      gridLines: {
        display: false,
        drawBorder: false,
      },
      ticks: {
        display: false, //this will remove only the label
        stepSize: 300,
      }
      }],
      yAxes: [{
        display: false,
        gridLines: {
          drawBorder: false,
          display: true,
          color: "#f0f3f6",
          borderDash: [8, 4],
        },
        ticks: {
          display: false,
          beginAtZero: true,
        },
      }]
    },
    legend: {
      display: false
    },
    tooltips: {
      enabled: false,
      backgroundColor: 'rgba(0, 0, 0, 1)',
    },
    plugins: {
      datalabels: {
        display: false,
        align: 'center',
        anchor: 'center'
      }
    }       
  };
  if ($("#customer").length) {
    var barChartCanvas = $("#customer").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    if(screen.width>767) {
      var chartHeight = document.getElementById("customer");
      chartHeight.height = 60;
    }
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: customerData,
      options: customerOptions
    });
  }
  
  
  
  
  
  var ordersData = {
      labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov" ],
      datasets: [{
        label: 'New Tickets',
        data: [19, 18, 17, 14, 43, 24, 18, 17],
        backgroundColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#fc696a', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#fc696a', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderWidth: 1,
        fill: false
      }
      ]
    };
    var ordersOptions = {
      scales: {
        xAxes: [{
        barPercentage: 1,
        position: 'bottom',
        display: true,
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          display: false, //this will remove only the label
          stepSize: 300,
        }
        }],
        yAxes: [{
          display: false,
          gridLines: {
            drawBorder: false,
            display: true,
            color: "#f0f3f6",
            borderDash: [8, 4],
          },
          ticks: {
            display: false,
            beginAtZero: true,
          },
        }]
      },
      legend: {
        display: false
      },
      tooltips: {
        enabled: false,
        backgroundColor: 'rgba(0, 0, 0, 1)',
      },
      plugins: {
        datalabels: {
          display: false,
          align: 'center',
          anchor: 'center'
        }
      }       
    };
    if ($("#orders").length) {
      var barChartCanvas = $("#orders").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      if(screen.width>767) {
        var chartHeight = document.getElementById("orders");
        chartHeight.height = 60;
      }
      var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: ordersData,
        options: ordersOptions
      });
    }
    var growthData = {
      labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov" ],
      datasets: [{
        label: 'New Tickets',
        data: [13, 18, 31, 38, 48, 34, 25, 20],
        backgroundColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#0d6efd', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#0d6efd', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderWidth: 1,
        fill: false
      }
      ]
    };
    var growthOptions = {
      scales: {
        xAxes: [{
        barPercentage: 1,
        position: 'bottom',
        display: true,
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          display: false, //this will remove only the label
          stepSize: 300,
        }
        }],
        yAxes: [{
          display: false,
          gridLines: {
            drawBorder: false,
            display: true,
            color: "#f0f3f6",
            borderDash: [8, 4],
          },
          ticks: {
            display: false,
            beginAtZero: true,
          },
        }]
      },
      legend: {
        display: false
      },
      tooltips: {
        enabled: false,
        backgroundColor: 'rgba(0, 0, 0, 1)',
      },
      plugins: {
        datalabels: {
          display: false,
          align: 'center',
          anchor: 'center'
        }
      }       
    };
    if ($("#growth").length) {
      var barChartCanvas = $("#growth").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      if(screen.width>767) {
        var chartHeight = document.getElementById("growth");
        chartHeight.height = 60;
      }
      var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: growthData,
        options: growthOptions
      });
    }
    var revenueData = {
      labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov" ],
      datasets: [{
        label: 'New Tickets',
        data: [13, 18, 31, 38, 33, 24, 19, 13],
        backgroundColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#ff8f00', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderColor: [
          '#e4e4e4', '#e4e4e4', '#e4e4e4', '#e4e4e4', '#ff8f00', '#e4e4e4', '#e4e4e4', '#e4e4e4',
        ],
        borderWidth: 1,
        fill: false
      }
      ]
    };
    var revenueOptions = {
      scales: {
        xAxes: [{
        barPercentage: 1,
        position: 'bottom',
        display: true,
        gridLines: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          display: false, //this will remove only the label
          stepSize: 300,
        }
        }],
        yAxes: [{
          display: false,
          gridLines: {
            drawBorder: false,
            display: true,
            color: "#f0f3f6",
            borderDash: [8, 4],
          },
          ticks: {
            display: false,
            beginAtZero: true,
          },
        }]
      },
      legend: {
        display: false
      },
      tooltips: {
        enabled: false,
        backgroundColor: 'rgba(0, 0, 0, 1)',
      },
      plugins: {
        datalabels: {
          display: false,
          align: 'center',
          anchor: 'center'
        }
      }       
    };
    if ($("#revenue").length) {
      var barChartCanvas = $("#revenue").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      if(screen.width>767) {
        var chartHeight = document.getElementById("revenue");
        chartHeight.height = 60;
      }
      var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: revenueData,
        options: revenueOptions
      });
    }
  
  
  
        var options = {
            chart: {
                height: 328,
                type: 'bar',
                toolbar:{
                  show:false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%'  
                },
            },
            dataLabels: {
                enabled: false
            },
      colors: ["#0d6efd", '#fc696a'],
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: 'Inquery',
                data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            }, {
                name: 'Conform',
                data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            }],
            xaxis: {
                categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            fill: {
                opacity: 1

            },
        legend: {
        position: 'top',
        horizontalAlign: 'left'
        },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        }

        var chart = new ApexCharts(
            document.querySelector("#bookingstatus"),
            options
        );

        chart.render();
  
  
  
  
      var options = {
      chart: {
      height: 328,
      type: 'line',
      toolbar:{
        show:false,
      },
      zoom: {
        enabled: false
      }
      },
      dataLabels: {
      enabled: false
      },
        colors: ["#40a2ed"],
      stroke: {
      curve: 'straight'
      },
      series: [{
      name: "Revenue",
      data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
      }],
      grid: {
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
      },
      xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      }
    }

    var chart = new ApexCharts(
      document.querySelector("#revenue-real"),
      options
    );

    chart.render();



        var area = new Morris.Area({
      element: 'revenue-chart',
      resize: true,
      data: [
        { y: '2017-01', a: 6,  b: 5 },
    { y: '2017-02', a: 4,  b: 3 },
    { y: '2017-03', a: 7,  b: 4 },
    { y: '2017-04', a: 5,  b: 9 },
    { y: '2017-05', a: 3,  b: 7 },
    { y: '2017-06', a: 1,  b: 3 },
    { y: '2017-07', a: 5,  b: 4 }
      ],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['Commercial Projects', 'Residential Projects'],
    fillOpacity: 0.2,
    lineWidth:2,
    lineColors: ['#1e88e5', '#fc4b6c'],
    hideHover: 'auto',
    color: '#666666'
    });
      
  
}); // End of use strict.



var dom = document.getElementById('chart-container');
var myChart = echarts.init(dom, null, {
  renderer: 'canvas',
  useDirtyRect: false
});
var app = {};

var option;

option = {
  tooltip: {
    trigger: 'item'
  },
  legend: {
    bottom: '0%',
    left: 'center'
  },
  series: [
    {
      name: 'Access From',
      type: 'pie',
      radius: ['50%', '70%'],
      avoidLabelOverlap: false,
      itemStyle: {
        borderRadius: 10,
        borderColor: '#fff',
        borderWidth: 2
      },
      label: {
        show: false,
        position: 'center'
      },
      emphasis: {
        label: {
          show: true,
          fontSize: 20,
          fontWeight: 'bold'
        }
      },
      labelLine: {
        show: false
      },
      data: [
        { value: 5, name: 'By Email' },
        { value: 15, name: 'By Phone' },
        { value: 45, name: 'On Site' },
        { value: 35, name: 'By Agent' },
      ]
    }
  ]
};

if (option && typeof option === 'object') {
  myChart.setOption(option);
}

window.addEventListener('resize', myChart.resize);