
$(function () {

  'use strict';




  
  var options = {
        series: [{
            name: "Profit",
            data: [0, 40, 110, 70, 100, 60, 130, 55, 140, 125]
        }],
        chart: {
      foreColor:"#bac0c7",
          height: 238,
          type: 'area',
          zoom: {
            enabled: false
          },
          toolbar:{
            show: false,
          },
        },
    colors:['#8950fc'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
            show: false,
      curve: 'stepline',
      lineCap: 'butt',
      colors: undefined,
      width: 3,
      dashArray: 0, 
        },    
    markers: {
      size: 6,
      colors: '#8950fc',
      strokeColors: '#ffffff',
      strokeWidth: 2,
      strokeOpacity: 0.9,
      strokeDashArray: 0,
      fillOpacity: 1,
      discrete: [],
      shape: "circle",
      radius: 5,
      offsetX: 0,
      offsetY: 0,
      onClick: undefined,
      onDblClick: undefined,
      hover: {
        size: undefined,
        sizeOffset: 3
      }
    },  
        grid: {
      borderColor: '#f7f7f7', 
          row: {
            colors: ['transparent'], // takes an array which will be repeated on columns
            opacity: 0
          },      
      yaxis: {
      lines: {
        show: true,
      },
      },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
      labels: {
      show: true,        
          },
          axisBorder: {
            show: true
          },
          axisTicks: {
            show: true
          },
          tooltip: {
            enabled: true,        
          },
        },
        yaxis: {
          labels: {
            show: true,
            formatter: function (val) {
              return val + "K";
            }
          }
        
        },
      };
      var chart = new ApexCharts(document.querySelector("#charts_widget_2_chart"), options);
      chart.render();
  
  var options = {
          series: [{
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63]
        }, {
          name: 'Revenue',
          data: [76, 85, 101, 98, 87, 105, 91]
        }],
          chart: {
          type: 'bar',
          height: 250,
        toolbar: {
            show: false,
        }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false,
        },
    grid: {
      show: false,
      padding: {
        top: 0,
        bottom: 0,
        right: 30,
        left: 20
      }
    },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
    colors: ['rgba(255, 255, 255, 0.25)', '#f7f7f7'],
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
      labels: {
              show: false,
      },
      axisBorder: {
              show: false,
      },
      axisTicks: {
              show: false,
      },
        },
        yaxis: {
          labels: {
              show: false,
      }
        },
     legend: {
          show: false,
     },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          },
      marker: {
        show: false,
      },
        }
        };

        var chart = new ApexCharts(document.querySelector("#revenue3"), options);
        chart.render();




  


     $("#walet-status").sparkline([80, 85, 76, 67, 78, 81, 54, 70, 51, 74, 79, 64, 68, 69, 72, 54, 75], {
        type: "bar",
        height: "65",
        barWidth: "3",
        resize: !0,
        barSpacing: "17",
        barColor: "#71D875"
    });
  
  
  
  
  $("#barchart3").sparkline([32,24,26,24,32,26,40,34,22,24,22,24,34,32,38,28,36,36,40,38,30,34,38], {
      type: 'bar',
      height: '70',
      width: '100%',
      barWidth: 6,
      barSpacing: 4,
      barColor: '#f64e60',
    });



            var options = {
          series: [{
          name: 'series1',
          data: [58, 25, 54, 71, 89, 95, 48]
          },{
          name: 'series2',
          data: [47, 39, 42, 54, 98, 71, 79]
          },],
          chart: {
          height: 288,
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
          type: 'categories',
          categories: ["01 July", "02 July", "03 July", "04 July", "05 July", "06 July", "07 July"]
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
  
}); // End of use strict

// easypie chart
  $(function() {
    'use strict'
    $('.easypie').easyPieChart({
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
        $(this.el).find('.percent').text(Math.round(percent));
      }
    });
    var chart = window.chart = $('.easypie').data('easyPieChart');
    $('.js_update').on('click', function() {
      chart.update(Math.random()*200-100);
    });
  });// End of use strict

// ------------------------------




    $('#to-groth').circleProgress({
        startAngle: -Math.PI / 4 * 3,
        value: 0.8,
        size: 150,
        lineCap: 'round',
        fill: { color: '#7460ee' },
        reverse: false
    });


    $('#to-income').circleProgress({
        startAngle: -Math.PI / 4 * 3,
        value: 0.5,
        size: 150,
        lineCap: 'round',
        fill: { color: '#fc4b6c' },
        reverse: false
    });
