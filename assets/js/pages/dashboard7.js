
$(function () {

  'use strict';



    $.fn.raty.defaults.path = 'https://tresto-admin-template.multipurposethemes.com/bs5/images/rating/';

   // Default Score
    $('#score-rating').raty({
        score: 4
    });

     // Icon Range
    $('#icon-range').raty({
        iconRange: [
            { range: 1, on: 'wi wi-day-rain-wind font-size-18', off: 'wi wi-day-cloudy font-size-18' },
            { range: 2, on: 'wi wi-day-sleet font-size-18', off: 'wi wi-day-sleet-storm font-size-18' },
            { range: 3, on: 'wi wi-day-snow font-size-18', off: 'wi wi-day-storm-showers font-size-18' },
            { range: 4, on: 'wi wi-night-alt-snow font-size-18', off: 'wi wi-night-alt-thunderstorm font-size-18' },
            { range: 5, on: 'wi wi-night-snow-thunderstorm font-size-18', off: 'wi wi-night-snow-wind font-size-18' }
        ],
        starType: 'i'
    });

    


    var optionsProgress1 = {
  chart: {
    height: 70,
    type: 'bar',
    stacked: true,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '20%',
      colors: {
        backgroundBarColors: ['#ffffff']
      }
    },
  },
  colors: ['#ff8f00'],
  stroke: {
    width: 0,
  },
  series: [{
    name: 'Process 1',
    data: [44]
  }],
  subtitle: {
    floating: true,
    align: 'right',
    offsetY: 0,
    text: '44%',
    style: {
      fontSize: '20px',
    color:'#ffffff'
    }
  },
  tooltip: {
    enabled: false
  },
  xaxis: {
    categories: ['Process 1'],
  },
  yaxis: {
    max: 100
  },
  fill: {
    opacity: 1
  }
}

var chartProgress1 = new ApexCharts(document.querySelector('#progress1'), optionsProgress1);
chartProgress1.render();


var optionsProgress2 = {
  chart: {
    height: 70,
    type: 'bar',
    stacked: true,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '20%',
      colors: {
        backgroundBarColors: ['#ffffff']
      }
    },
  },
  colors: ['#689f38'],
  stroke: {
    width: 0,
  },
  series: [{
    name: 'Process 2',
    data: [80]
  }],
  subtitle: {
    floating: true,
    align: 'right',
    offsetY: 0,
    text: '80%',
    style: {
      fontSize: '20px',
    color:'#ffffff'
    }
  },
  tooltip: {
    enabled: false
  },
  xaxis: {
    categories: ['Process 2'],
  },
  yaxis: {
    max: 100
  },
}

var chartProgress2 = new ApexCharts(document.querySelector('#progress2'), optionsProgress2);
chartProgress2.render();


var optionsProgress3 = {
  chart: {
    height: 70,
    type: 'bar',
    stacked: true,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '20%',
      colors: {
        backgroundBarColors: ['#ffffff']
      }
    },
  },
  colors: ['#ee1044'],
  stroke: {
    width: 0,
  },
  series: [{
    name: 'Process 3',
    data: [74]
  }],
  subtitle: {
    floating: true,
    align: 'right',
    offsetY: 0,
    text: '74%',
    style: {
      fontSize: '20px',
    color:'#ffffff'
    }
  },
  tooltip: {
    enabled: false
  },
  xaxis: {
    categories: ['Process 3'],
  },
  yaxis: {
    max: 100
  },
}

var chartProgress3 = new ApexCharts(document.querySelector('#progress3'), optionsProgress3);
chartProgress3.render();
  
  var optionsProgress4 = {
  chart: {
    height: 70,
    type: 'bar',
    stacked: true,
    sparkline: {
      enabled: true
    }
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '20%',
      colors: {
        backgroundBarColors: ['#ffffff']
      }
    },
  },
  colors: ['#38649f'],
  stroke: {
    width: 0,
  },
  series: [{
    name: 'Process 4',
    data: [74]
  }],
  subtitle: {
    floating: true,
    align: 'right',
    offsetY: 0,
    text: '74%',
    style: {
      fontSize: '20px',
    color:'#ffffff'
    }
  },
  tooltip: {
    enabled: false
  },
  xaxis: {
    categories: ['Process 4'],
  },
  yaxis: {
    max: 100
  },
}

var chartProgress4 = new ApexCharts(document.querySelector('#progress4'), optionsProgress4);
chartProgress4.render();



  var orderSummaryChartOptions = {
        chart: {
          height: 300,
          type: 'line',
          stacked: false,
          toolbar: {
          show: false,
          },
          sparkline: {
          enabled: true
          },
        },
        colors: ['#2e62b9', '#FF2829'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 2.5,
          dashArray: [0, 8]
        },
        fill: {
          type: 'gradient',
          gradient: {
          inverseColors: false,
          shade: 'light',
          type: "vertical",
          gradientToColors: ['#a1bce8', '#FF2829'],
          opacityFrom: 0.7,
          opacityTo: 0.55,
          stops: [0, 80, 100]
          }
        },
        series: [{
          name: 'Weeks',
          data: [165, 175, 162, 173, 160, 195, 160, 170, 160, 190, 180, 165, 175, 162, 173],
          type: 'area',
        }, {
          name: 'Months',
          data: [168, 168, 155, 178, 155, 170, 190, 160, 150, 170, 140, 168, 168, 155, 178],
          type: 'line',
        }],

        xaxis: {
          offsetY: -50,
          categories: ['', 1, 2, 3, 4, 5, 6, 7, 8, 9, ''],
          axisBorder: {
          show: false,
          },
          axisTicks: {
          show: false,
          },
          labels: {
          show: true,
          style: {
            colors: '#8097b1'
          }
          }
        },
        tooltip: {
          x: { show: false }
        },
        }

        var orderSummaryChart = new ApexCharts(
        document.querySelector("#order-summary-chart"),
        orderSummaryChartOptions
        );

        orderSummaryChart.render();



  
    var options = {
          series: [{
          name: 'Total Income',
          data: [44, 85, 60, 21, 71, 58, 45, 60, 38, 65, 40, 75 ]
        }],
          chart: {
          type: 'bar',
          height: 300,
      toolbar: {
          show: false,
      }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            borderRadius: 3,
          },
        },
    colors:['#3596f7'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#income"), options);
        chart.render();
  
  
    
  
    var options = {
          series: [{
          name: 'Total Expense',
          data: [14, 45, 30, 1, 31, 28, 15, 30, 18, 25, 10, 25 ]
        }],
          chart: {
          type: 'bar',
          height: 300,
      toolbar: {
          show: false,
      }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            borderRadius: 3,
          },
        },
    colors:['#ee3158'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#expense"), options);
        chart.render();
  
    
    var options = {
          series: [
          {
            name: "This Week",
            data: [435, 299, 339, 469, 392, 529, 339]
          },
          {
            name: "Last - Week",
            data: [312, 461, 314, 418, 317, 413, 513]
          }
        ],
          chart: {
          height: 268,
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#3596f7', '#ee3158'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Set', 'Sun'],
        },
        legend: {
          position: 'top',
          horizontalAlign: 'center',
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " thousands"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#visitors"), options);
        chart.render();
  
    $('.product-div').slimScroll({
      height: '296px'
      });
  
     
    
  
  
    var options = {
          series: [{
            name: "Profit",
            data: [35, 41, 62, 42, 13, 18, 29]
          },
          {
            name: 'Sales',
            data: [87, 57, 74, 99, 75, 38, 62]
          }
        ],
          chart: {
          height: 238,
          type: 'line',
          zoom: {
            enabled: false
          },
          toolbar: {
            show: false
          },
        },
        dataLabels: {
          enabled: false
        },
    colors:['#7047ee', '#ee3158' ],
        stroke: {
          width: [5, 2],
          curve: 'smooth',
          dashArray: [0, 5]
        },
        legend: {
      position: 'top',
      horizontalAlign: 'center',
    },
        xaxis: {
          categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan'],
        },
        tooltip: {
          y: [
            {
              title: {
                formatter: function (val) {
                  return val + "%"
                }
              }
            },
            {
              title: {
                formatter: function (val) {
                  return val + "%"
                }
              }
            }
          ]
        },

        responsive: [{
        breakpoint: 1367,
        options: {          
            chart: {
            height: 284,
          },
        },
      },
      ],

        };

        var chart = new ApexCharts(document.querySelector("#monthly-sales"), options);
        chart.render();




                var options = {
          series: [{
          name: 'series1',
          data: [100, 80, 90, 70, 80, 60, 110]
          },{
          name: 'series2',
          data: [50, 60, 40, 40, 45, 48, 70]
          },],
          chart: {
          height: 286,
          type: 'area',
          toolbar: {
            show: false,
            },
            offsetY: 0,
        },
        colors: ["#0d6efd", "#198754"],
        fill: {
          colors: ["#0d6efd","#198754" ],
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.4,
            inverseColors: false,
            opacityFrom: 0.8,
            opacityTo: 0.1,
            stops: [0, 100],
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
        width: [2, 2],
          curve: 'smooth'
        },
        grid: {
          show: false,
          padding: {
            left: -10,
            top: -25,
            right: -0,
          },
        },
        markers: {
            size: 5,
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        legend: {
            show: false,
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
            formatter: function (val) {
              return val + "%";
            }
          },
        },
        xaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: false,
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#chart-widget1"), options);
        chart.render();
  
  
  
  
     
    var optionDonut = {
      chart: {
        type: 'donut',
        width: '100%',
        height: 300
      },
      dataLabels: {
      enabled: false,
      },
      plotOptions: {
      pie: {
        customScale: 0.8,
        donut: {
        size: '65%',
        },
      },
      },
      colors:['#6f57ea', '#ff7e6d', '#2ed4c7' ],
      
      series: [40, 24, 36],
      labels: ['Direct', 'Sponsored', 'Affiliate'],
            
      legend: {
        show: false
      },
    }

    var donut = new ApexCharts(
      document.querySelector("#profit-value"),
      optionDonut
    )
    donut.render();
    
  
}); // End of use strict