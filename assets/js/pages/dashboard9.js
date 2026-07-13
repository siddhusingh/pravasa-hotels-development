$(function () {

  'use strict';



    /* Sales Chart Weekly */
    var options = {
      series: [{
        name: 'Sale',
        data: [76, 85, 101, 98, 87, 105, 91]
      }, {
        name: 'Profit',
        data: [44, 55, 57, 56, 61, 58, 63]
      }],
      chart: {
        type: 'bar',
        height: 315,
        toolbar: {
          show: true,
          offsetX: 0,
          offsetY: 0,
        },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      colors: ['#9a8efe', '#60eecb'],
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      legend: {
        show: true,
        labels: {
          colors: 'var(--clr-chart-1)',
          fontSize: '12px',
          fontFamily: 'var(--bd-fs-body)',
        },
      },
      xaxis: {
        categories: ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'],
        labels: {
          style: {
            colors: 'var(--clr-chart-1)',
            fontSize: '12px',
            fontFamily: 'var(--bd-fs-body)',
            fontWeight: 400,
            cssClass: 'apexcharts-xaxis-label',
          },
        },
      },
      yaxis: {
        labels: {
          style: {
            colors: 'var(--clr-chart-1)',
            fontSize: '12px',
            fontFamily: 'var(--bd-fs-body)',
            fontWeight: 400,
            cssClass: 'apexcharts-xaxis-label',
          },
        },
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
    var chart = new ApexCharts(document.querySelector("#salesChartWeek"), options);
    chart.render();
  
     
  var options = {
          series: [50, 20, 30],
          chart: {
          type: 'donut',
       height: 263,
        width: 240,
        },
    legend: {
          show: false,
    },
    dataLabels: {
      enabled: false,
      },
    plotOptions: {
      pie: {
        customScale: 0.90,
        donut: {
        size: '80%',
          labels: {
          show: true,
          total: {
            showAlways: true,
            show: true,
          }
          }
        },
        offsetY: 0,
      },
      stroke: {
        colors: undefined
      }
      },
    colors:['#0d6efd', '#fc696a','#ff9920' ],
        };

        var chart = new ApexCharts(document.querySelector("#chart41"), options);
        chart.render();
  
  
   var analyticsBarChartOptions = {
    chart: {
      height: 265,
      type: 'bar',
      toolbar: {
      show: false
      }
    },
    plotOptions: {
      bar: {
      horizontal: false,
      columnWidth: '40%',
      borderRadius: 3
      },
    },
    dataLabels: {
      enabled: false
    },
    colors: ['#3596f7', '#cce5ff'],
    series: [{
      name: 'Project',
      data: [80, 95, 150, 210, 140, 230, 300]
    }, {
      name: 'Bench',
      data: [50, 70, 130, 180, 90, 180, 270]
    }],
    xaxis: {
      categories: ['Sun', 'Mon', 'Tur', 'Wed', 'Thu', 'Fri', 'Set'],
      axisBorder: {
      show: false
      },
      axisTicks: {
      show: false
      },
      labels: {
      style: {
        colors: '#333333'
      }
      }
    },
    yaxis: {
      min: 0,
      max: 300,
      tickAmount: 3,
      labels: {
      style: {
        color: '#333333'
      }
      }
    },
    legend: {
      show: false, 
    },
    }

    var analyticsBarChart = new ApexCharts(
    document.querySelector("#analytics-bar-chart"),
    analyticsBarChartOptions
    );

    analyticsBarChart.render();
  
  
  
  var analyticsBarChartOptions = {
    chart: {
      height: 228,
      type: 'bar',
      toolbar: {
      show: false
      }
    },
    plotOptions: {
      bar: {
      horizontal: false,
      columnWidth: '45%',
      borderRadius: 3
      },
    },
    dataLabels: {
      enabled: false
    },
    colors: ['#7047ee'],
    series: [{
      name: 'Turnover',
      data: [80, 95, 150, 210, 140, 230, 300, 280, 130]
    }],
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      axisBorder: {
      show: false
      },
      axisTicks: {
      show: false
      },
      labels: {
      style: {
        colors: '#333333'
      }
      }
    },
    yaxis: {
      min: 0,
      max: 300,
      tickAmount: 3,
      labels: {
      style: {
        color: '#333333'
      }
      }
    },
    legend: {
      show: false, 
    },
    tooltip: {
      y: {
      formatter: function (val) {
        return "$ " + val + " thousands"
      }
      }
    }
    }

    var analyticsBarChart = new ApexCharts(
    document.querySelector("#staff_turnover"),
    analyticsBarChartOptions
    );

    analyticsBarChart.render();
  




          var options = {
          series: [{
            name: 'OnTime',
            data: [40, 30, 20, 35, 40, 50, 35, 43, 28,]
          },{
              name: 'Late Arival',
              data: [30, 20, 10, 30, 25, 36, 32, 30, 25,]
            },{
              name: 'Absent',
              data: [20, 15, 5, 25, 30, 30, 25, 23, 14,]
            }],
          chart: {
          height: 283,
          type: 'bar',
          toolbar: {
            show: false
          },
        },
        plotOptions: {
          bar: {
            borderRadius: 5,  
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        colors: ["#0d6efd" , "#FFAA05", "#fc696a"],
        stroke: {
          show: true,
          width: 4,
          colors: ['transparent']
        },
        dataLabels: {
          enabled: false,
          formatter: function (val) {
            return val + "%";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758" , "#FFAA05"]
          }
        },
        legend: {
            show: true,
        },
        grid: {
            show: false,
          },
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
          position: 'bottom',
          labels: {
            show: true,
          },
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: false,
          }
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
          }
        
        },
        title: {
          text: '',
          floating: false,
          offsetY: 330,
          align: 'center',
          style: {
            color: '#444'
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#attendance-chart"), options);
        chart.render();
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
}); // End of use strict