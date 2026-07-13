
$(function () {

  


    window.Apex = {
      stroke: {
        width: 3
      },
      markers: {
        size: 0
      },
      tooltip: {
        fixed: {
          enabled: true,
        }
      }
    };
    
    var randomizeArray = function (arg) {
      var array = arg.slice();
      var currentIndex = array.length,
        temporaryValue, randomIndex;

      while (0 !== currentIndex) {

        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }

      return array;
    }

    // data for the sparklines that appear below header area
    var sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46];

    var spark1 = {
      chart: {
        type: 'area',
        height: 120,
        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'smooth'
      },
      fill: {
        opacity: 0,
        type: 'solid',
    gradient: {
      gradientToColors: ['#689f38']
    },
      },
      series: [{
        data: randomizeArray(sparklineData)
      }],
    labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
      yaxis: {
        min: 0
      },
    xaxis: {
    type: 'datetime',
    },
      colors: ['#e2bb33'],
    tooltip: {
      theme: 'dark'
      },
    }
  
  var spark2 = {
      chart: {
        type: 'area',
        height: 120,
        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'smooth'
      },
      fill: {
        opacity: 0,
        type: 'solid',
    gradient: {
      gradientToColors: ['#ff8f00', '#ff8f00']
    },
      },
      series: [{
        data: randomizeArray(sparklineData)
      }],
    labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
      yaxis: {
        min: 0
      },
    xaxis: {
    type: 'datetime',
    },
      colors: ['#ff8f00'],
    tooltip: {
      theme: 'dark'
      },
    };
  
   var spark3 = {
      chart: {
        type: 'area',
        height: 120,
        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'smooth'
      },
      fill: {
        opacity: 0,
        type: 'solid',
    gradient: {
      gradientToColors: ['#ee1044', '#ee1044']
    },
      },
      series: [{
        data: randomizeArray(sparklineData)
      }],
    labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
    xaxis: {
    type: 'datetime',
    },
      yaxis: {
        min: 0
      },
      colors: ['#ee1044'],
    tooltip: {
      theme: 'dark'
      },
    };
  
  
  
  var spark1 = new ApexCharts(document.querySelector("#spark1"), spark1);
    spark1.render();
  var spark2 = new ApexCharts(document.querySelector("#spark2"), spark2);
    spark2.render();
    var spark3 = new ApexCharts(document.querySelector("#spark3"), spark3);
    spark3.render();

var options = {
          series: [{
          name: 'series1',
          data: [ 20,15,25,25,30,27,33,30,35,32,25,31,20,25,30,27,33,30,20]
          }],
          chart: {
          height: 114,
          type: 'area',
          toolbar: {
            show: false,
            },
            offsetY: 0,
        },
        colors: ["#0d6efd"],
        fill: {
          colors: ["#0d6efd" ],
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.4,
            inverseColors: false,
            opacityFrom: 0.7,
            opacityTo: 0.1,
            stops: [0,85,90],
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
        width: [2],
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
            size: 0,
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











 // ----------Shifts Overview-----//
    var option = {
        labels: ["Payments", "Withdrawal", "Deposit", "Profits", "Transfers"],
        series: [45, 20, 30, 50, 30],
        chart: {
            type: "donut",
            height: 380,
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        stroke: {
            width: 5,
        },
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: "80%",
                    labels: {
                        show: true,
                        name: {
                            offsetY: -10,
                            color: '#00000',
                        },
                        total: {
                            show: true,
                            fontSize: "20px",
                            fontWeight: 600,
                            color:"#000000",
                            formatter: () => "My Balance",
                            label: "$ 1,250",
                        },
                    },
                },
            },
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                },
            },
            hover: {
                filter: {
                    type: "none",
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                },
            },
        },
        colors: ["#ff9920", "#ff562f", "#0052cc", "#00baff", "#04a08b"],
    };

    var chart = new ApexCharts(
        document.querySelector("#balance-overview"),
        option
    );
    chart.render();













   var options = {
          series: [44, 55, 25, 30, 25],
          chart: {
          type: 'donut',
          width: 400,
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#balance_chart"), options);
        chart.render();


  
 

        var options = {
          series: [44, 55, 25, 30, 25],
          chart: {
          width: 400,
          type: 'donut',
        },
        dataLabels: {
          enabled: false
        },
         labels: ["Payments", "Withdrawal", "Deposit", "Profits", "Transters"],
         colors: ["#de8647", "#00baff", "#ff562f", "#0052cc", "#543cde"],
        responsive: [{
          breakpoint: 280,
          options: {
            
            legend: {
              show: false
            }
          }
        }],
        legend: {
          show: false,
          position: 'right',
          offsetY: 0,
          height: true,
          fontSize: '14px',
          fontWeight: 600,
          markers: {
            size: 7,
          },
          itemMargin: {
              horizontal: 0,
              vertical: 10
          },
        },
        responsive: [
            {
              breakpoint: 1440,
              options: {
                legend: {
                  position: 'bottom',
                  offsetX: -20,
                }
              }
            }
          ]
        };
        

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();






var chartData = generateChartData();
  function generateChartData() {
    var chartData = [];
    var firstDate = new Date( 2024, 3, 12 );
    firstDate.setDate( firstDate.getDate() - 1000 );
    firstDate.setHours( 0, 0, 0, 0 );

    var a = 2000;
   
    for ( var i = 0; i < 1000; i++ ) {
      var newDate = new Date( firstDate );
      newDate.setHours( 0, i, 0, 0 );

      a += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
      var b = Math.round( Math.random() * 100000000 );

      chartData.push( {
        "date": newDate,
        "value": a,
        "volume": b
      } );
    }
    return chartData;
  }

var chart = AmCharts.makeChart( "chartdiv21", {
  "type": "stock",
  "theme": "light",
  "categoryAxesSettings": {
    "minPeriod": "mm"
  },

  "dataSets": [ {
    "color": "#fbae1c",
    "fieldMappings": [ {
      "fromField": "value",
      "toField": "value"
    }, {
      "fromField": "volume",
      "toField": "volume"
    } ],

    "dataProvider": chartData,
    "categoryField": "date"
  } ],

  "panels": [ {
    "showCategoryAxis": false,
    "title": "Value",
    "percentHeight": 70,

    "stockGraphs": [ {
      "id": "g1",
      "valueField": "value",
      "type": "smoothedLine",
      "lineThickness": 2,
      "bullet": "round"
    } ],


    "stockLegend": {
      "valueTextRegular": " ",
      "markerType": "none"
    }
  }, {
    "title": "Volume",
    "percentHeight": 30,
    "stockGraphs": [ {
      "valueField": "volume",
      "type": "column",
      "cornerRadiusTop": 2,
      "fillAlphas": 1
    } ],

    "stockLegend": {
      "valueTextRegular": " ",
      "markerType": "none"
    }
  } ],

  "chartScrollbarSettings": {
    "graph": "g1",
    "usePeriod": "10mm",
    "position": "top"
  },

  "chartCursorSettings": {
    "valueBalloonsEnabled": true
  },

  "periodSelector": {
    "position": "top",
    "dateFormat": "YYYY-MM-DD JJ:NN",
    "inputFieldWidth": 150,
    "periods": [ {
      "period": "hh",
      "count": 1,
      "label": "1 hour"
    }, {
      "period": "hh",
      "count": 2,
      "label": "2 hours"
    }, {
      "period": "hh",
      "count": 5,
      "selected": true,
      "label": "5 hour"
    }, {
      "period": "hh",
      "count": 12,
      "label": "12 hours"
    }, {
      "period": "MAX",
      "label": "MAX"
    } ]
  },

  "panelsSettings": {
    "usePrefixes": true
  },

  "export": {
    "enabled": true,
    "position": "bottom-right"
  }
} );


  
}); // End of use strict




 
zingchart.render({
  id: 'myChart',
  data: chartConfig,
  height: 400,
  width: '100%'
});




am4core.ready(function() {

  // Themes begin
  am4core.useTheme(am4themes_animated);
  // Themes end

  // create chart
  var chart = am4core.create("userflow", am4charts.GaugeChart);
  chart.innerRadius = am4core.percent(82);

  /**
   * Normal axis
   */

  var axis = chart.xAxes.push(new am4charts.ValueAxis());
  axis.min = 0;
  axis.max = 100;
  axis.strictMinMax = true;
  axis.renderer.radius = am4core.percent(80);
  axis.renderer.inside = false;
  axis.renderer.line.strokeOpacity = 1;
  axis.renderer.ticks.template.strokeOpacity = 1;
  axis.renderer.ticks.template.length = 10;
  axis.renderer.grid.template.disabled = true;
  axis.renderer.labels.template.radius = 50;
  axis.renderer.labels.template.adapter.add("text", function(text) {
    return text + "%";
  })

  /**
   * Axis for ranges
   */

  var colorSet = new am4core.ColorSet();

  var axis2 = chart.xAxes.push(new am4charts.ValueAxis());
  axis2.min = 0;
  axis2.max = 100;
  axis2.renderer.innerRadius = 100
  axis2.strictMinMax = true;
  axis2.renderer.labels.template.disabled = true;
  axis2.renderer.ticks.template.disabled = true;
  axis2.renderer.grid.template.disabled = true;

  var range0 = axis2.axisRanges.create();
  range0.value = 0;
  range0.endValue = 50;
  range0.axisFill.fillOpacity = 1;
  range0.axisFill.fill = colorSet.getIndex(0);

  var range1 = axis2.axisRanges.create();
  range1.value = 50;
  range1.endValue = 100;
  range1.axisFill.fillOpacity = 1;
  range1.axisFill.fill = colorSet.getIndex(2);

  /**
   * Label
   */

  var label = chart.radarContainer.createChild(am4core.Label);
  label.isMeasured = false;
  label.fontSize = 18;
  label.x = am4core.percent(50);
  label.y = am4core.percent(100);
  label.horizontalCenter = "middle";
  label.verticalCenter = "bottom";
  label.text = "10%";


  /**
   * Hand
   */

  var hand = chart.hands.push(new am4charts.ClockHand());
  hand.axis = axis2;
  hand.innerRadius = am4core.percent(20);
  hand.startWidth = 10;
  hand.pin.disabled = true;
  hand.value = 50;

  hand.events.on("propertychanged", function(ev) {
    range0.endValue = ev.target.value;
    range1.value = ev.target.value;
    axis2.invalidate();
  });

  setInterval(() => {
    var value = Math.round(Math.random() * 100);
    label.text = value + "%";
    var animation = new am4core.Animation(hand, {
    property: "value",
    to: value
    }, 1000, am4core.ease.cubicOut).start();
  }, 2000);

  }); // end am4core.ready()
