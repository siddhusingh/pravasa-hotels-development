$(function () {

  'use strict';
  
    var options = {
          series: [{
            name: "Expenses",
            data: [30, 41, 20, 51, 80, 60]
        }],
          chart: {
          height: 271,
          type: 'area',
        foreColor:"#bac0c7",
          zoom: {
            enabled: false
          }
        },
    colors:['#EA5455'],
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-main"), options);
        chart.render();
  
  
  
  
  var options = {
        series: [
      {
            name: "Current year",
            data: [0, 40, 110, 70, 100, 130,]
          },
      {
            name: "Last year",
            data: [0, 30, 150, 40, 90, 70,]
          },
        ],
        chart: {
      foreColor:"#bac0c7",
          height: 322,
          type: 'line',
          zoom: {
            enabled: false
          },
          toolbar:{
            show:false
          },
        },
    colors:['#7367F0', '#EA5455'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
            show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 4,
      dashArray: 0, 
        },
     legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
     },
    markers: {
      size: 6,
      colors: ['#7367F0', '#EA5455'],
      strokeColors: '#ffffff',
      strokeWidth: 2,
      strokeOpacity: 1,
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
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',],
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
          name: 'Earning',
          data: [76, 85, 101, 98, 87, 105, 91]
        }],
          chart: {
          type: 'bar',
      foreColor:"#bac0c7",
          height: 165,
        toolbar: {
            show: false,
        }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '20%',
          },
        },
        dataLabels: {
          enabled: false,
        },
    grid: {
      show: false,      
    },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
    colors: ['#7367F0'],
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
      
        },
        yaxis: {
          
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

        var chart = new ApexCharts(document.querySelector("#recent_trend"), options);
    chart.render();
  
      
    var options = {
          series: [60, 30],
      labels: ['Booked', 'Cancelled'],
          chart: {
          width: 215,
          type: 'donut',
        },
    colors: ['#7367F0', '#EA5455'],
        dataLabels: {
          enabled: false
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              show: false
            }
          }
        }],
        legend: {
          show: false
        }
        };

        var chart = new ApexCharts(document.querySelector("#analytics_chart"), options);
        chart.render();
  
  
  
  
  jQuery('#world-map-markers').vectorMap(
    {
      map: 'world_mill_en',
      backgroundColor: '#ffffff00',
      borderColor: '#818181',
      borderOpacity: 0.25,
      borderWidth: 1,
      color: '#f4f3f0',
      regionStyle : {
        initial : {
          fill : '#eef0fe'
        }
        },
      markerStyle: {
        initial: {
      r: 5,
      'fill': '#EA5455',
      'fill-opacity':1,
      'stroke': '#000',
      'stroke-width' : 1,
      'stroke-opacity': 0.0
            },
            },
      enableZoom: false,
      hoverColor: '#bcc3fb',
      markers : [
        {
        latLng : [43.73, 7.41],
        name : 'Monaco',
        style: {fill: '#7367F0', r:5}
          },
        {
        latLng : [3.2, 73.22],
        name : 'Maldives',
        style: {fill: '#28C76F', r:5}
          },
        {
        latLng : [7.35, 134.46],
        name : 'Palau',
        style: {fill: '#3699ff', r:5}
          },
        {
        latLng : [1.3, 103.8],
        name : 'Singapore',
        style: {fill: '#FF9F43', r:5}
          },
        {
        latLng : [13.16, -59.55],
        name : 'Barbados',
        style: {fill: '#EA5455', r:5}
          },
        {
        latLng : [35.88, 14.5],
        name : 'Malta',
        style: {fill: '#172b4c', r:5}
          },
      ],
      hoverOpacity: null,
      normalizeFunction: 'linear',
      scaleColors: ['#b6d6ff', '#005ace'],
      selectedColor: '#c9dfaf',
      selectedRegions: [],
      showTooltip: true,
      onRegionClick: function(element, code, region)
      {
        var message = 'You clicked "'
          + region
          + '" which has the code: '
          + code.toUpperCase();

        alert(message);
      }
    });


var options = {
          series: [{
          name: 'Total Spend',
          data: [23, 31, 40, 101, 40, 36, 32, 23, 14, 18, 15, 12, 13, 11, 40, 10, 40, 26, 12, 23, 16, 8]
        }],
          chart: {
          height: 300,
          type: 'bar',
      zoom: {
            enabled: false
          }
        },
        plotOptions: {
          bar: {
            borderRadius: 100,
        columnWidth: '30%',
        endingShape: 'rounded',
          }
        },
        dataLabels: {
          enabled: false,
          formatter: function (val) {
            return  "$" + val + "k";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#3699ff"]
          }
        },
        
        xaxis: {
      type: 'datetime',
          categories: ['01/01/2021 GMT', '01/02/2021 GMT', '01/03/2021 GMT', '01/04/2021 GMT', '01/05/2021 GMT', '01/06/2021 GMT', '01/07/2021 GMT', '01/08/2021 GMT', '01/09/2021 GMT', '01/10/2021 GMT', '01/11/2021 GMT', '01/12/2021 GMT', '01/13/2021 GMT', '01/14/2021 GMT', '01/15/2021 GMT', '01/16/2021 GMT', '01/17/2021 GMT', '01/18/2021 GMT', '01/19/2021 GMT', '01/20/2021 GMT', '01/21/2021 GMT', '01/22/2021 GMT'],          
          position: 'top',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          tooltip: {
            enabled: true,
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
        };

        var chart = new ApexCharts(document.querySelector("#spend_trend"), options);
        chart.render();



                var options = {
          series: [{
            name: 'Reality Sales',
            data: [23, 31, 40, 101, 40, 36, 32, 23, 14, 18, 15, 12, 13, 11, 40, 10, 40, 26, 12, 23, 16, 8]
          }],
          chart: {
          height: 300,
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
        colors: ["#0d6efd" , "#FFAA05"],
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
            show: false,
        },
        grid: {
            show: false,
          },
        xaxis: {
          type: 'datetime',
          categories: ['01/01/2021 GMT', '01/02/2021 GMT', '01/03/2021 GMT', '01/04/2021 GMT', '01/05/2021 GMT', '01/06/2021 GMT', '01/07/2021 GMT', '01/08/2021 GMT', '01/09/2021 GMT', '01/10/2021 GMT', '01/11/2021 GMT', '01/12/2021 GMT', '01/13/2021 GMT', '01/14/2021 GMT', '01/15/2021 GMT', '01/16/2021 GMT', '01/17/2021 GMT', '01/18/2021 GMT', '01/19/2021 GMT', '01/20/2021 GMT', '01/21/2021 GMT', '01/22/2021 GMT'],
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

        var chart = new ApexCharts(document.querySelector("#Reality-chart"), options);
        chart.render();

  
  
    var bar = new ProgressBar.Circle(progressbar1, {
      color: '#3699ff',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#3699ff', width: 4 },
      to: { color: '#3699ff', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-plane'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.78);
  
  
    var bar = new ProgressBar.Circle(progressbar2, {
      color: '#EA5455',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#EA5455', width: 4 },
      to: { color: '#EA5455', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-hotel'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.5);
  
  
    var bar = new ProgressBar.Circle(progressbar3, {
      color: '#FF9F43',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#FF9F43', width: 4 },
      to: { color: '#FF9F43', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-train'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.4);
  
  
  
    var bar = new ProgressBar.Circle(progressbar4, {
      color: '#28C76F',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#28C76F', width: 4 },
      to: { color: '#28C76F', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-car'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.3);
  
  
  
    var bar = new ProgressBar.Circle(progressbar5, {
      color: '#3699ff',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#3699ff', width: 4 },
      to: { color: '#3699ff', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-subway'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.25);
  
  
  
    var bar = new ProgressBar.Circle(progressbar6, {
      color: '#7367F0',
      // This has to be the same size as the maximum width to
      // prevent clipping
      strokeWidth: 30,
      trailWidth: 5,
      easing: 'easeInOut',
      duration: 1400,
      text: {
      autoStyleContainer: false
      },
      from: { color: '#7367F0', width: 4 },
      to: { color: '#7367F0', width: 4 },
      // Set default step function for all animate calls
      step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);

      var value = Math.round(circle.value() * 150);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText("<i class='fa fa-spoon'></i>");
      }

      }
    });
    bar.text.style.fontSize = '1.5rem';

    bar.animate(0.15);
  
}); // End of use strict