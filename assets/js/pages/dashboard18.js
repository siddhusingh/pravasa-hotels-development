$(function () {

  'use strict';


          var options = {
          series: [{
          name: 'New Ticket',
          data: [49, 62, 55, 67, 73, 110, 120, 115, 129, 123, 133,105]
        }, {
          name: 'Ticket Solved',
          data: [62, 76, 67, 49, 63, 70, 37, 52, 44, 61, 43,90]
        }],
          chart: {
          height: 318,
          type: 'area',
          toolbar: {
            show: false
          }
        },
        stroke: {
          width: [3, 3],
      curve: 'smooth',
        },
        legend: {
          show: false,
        },
    colors:['#ffa800', '#3762EA'],
        dataLabels: {
          enabled: false,
          enabledOnSeries: [1]
        },
    fill: {
      colors:['#ffa800', '#3762EA'],
      opacity: 0.05,
        type: 'solid',
    },
    markers: {
      size: 7,
      colors: undefined,
      strokeColors: '#fff',
      strokeWidth: 2,
      strokeOpacity: 1,
      strokeDashArray: 0,
      fillOpacity: 1,
      discrete: [],
      shape: "square",
      radius: 0,
      offsetX: 0,
      offsetY: 0,
      onClick: undefined,
      onDblClick: undefined,
      showNullDataPoints: true,
      hover: {
        size: undefined,
        sizeOffset: 3
      }
    },
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        };

        var chart = new ApexCharts(document.querySelector("#balance_overview"), options);
        chart.render();




  var options = {
          series: [{
          name: 'Open Ticket',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 70, 40, 50]
        }, {
          name: 'Close Ticket',
          data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 50, 120, 35]
        }],
          chart: {
          type: 'bar',
          height: 270,
          stacked: false,  
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '35%',
            endingShape: 'rounded'
          },
        },
        legend: {
            show: false,
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
        },
        yaxis: {
          title: {
            text: ''
          }
        },
        colors: ['#0d6efd', '#fc696a'],
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + ""
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-Overall"), options);
        chart.render();


  var options = {
          series: [{
          name: 'series1',
          data: [90, 60, 45, 40, 65, 52, 41]
        }, {
          name: 'series2',
          data: [50, 40, 55, 100, 42, 80, 60]
        }],
          chart: {
          height: 318,
          type: 'area',
          toolbar: {
            show: false,
            },
            offsetY: 30,
        },
        colors: ['#0d6efd', '#ff9920'],
        fill: {
          colors:['#0d6efd', '#ff9920'],
          opacity: 0.05,
            type: 'solid',
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
            right: -60,
          },
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
        responsive: [{
            breakpoint: 1367,
            options: {          
                chart: {
                height: 318,
              },
            },
          }
          ],
        };

        var chart = new ApexCharts(document.querySelector("#numberchart"), options);
        chart.render();



  var options = {
          series: [{
          name: 'Online Ticket',
          data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
        }, {
          name: 'Offline Ticket',
          data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
        }],
          chart: {
          type: 'bar',
          height: 283,
          offsetX: -15,
          offsetY: 8,
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
        colors: ['#3596f7', '#cce5ff'],
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        },
        yaxis: {
          title: {
            text: ''
          }
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

        var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        chart.render();


  
      

  
  
}); // End of use strict
