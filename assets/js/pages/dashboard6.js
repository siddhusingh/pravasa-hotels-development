$(function () {
    "use strict";


      var options = {
          series: [{
          name: 'CS',
          data: [44, 55, 41, 67, 22, 43, 44]
        }, {
          name: 'Mathematics',
          data: [13, 23, 20, 8, 13, 27, 13]
        }, {
          name: 'IT',
          data: [23, 17, 12, 9, 15, 24, 18]
        }, {
          name: 'DS',
          data: [23, 12, 12, 15, 12, 5, 11]
        }, {
          name: 'Business',
          data: [14, 25, 18, 8, 5, 11, 14]
        }],
          chart: {
      foreColor:"#bac0c7",
          type: 'bar',
          height: 304.8,
          stacked: true,
          toolbar: {
            show: false
          },
          zoom: {
            enabled: true
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],   
    grid: {
      show: true,
      borderColor: '#f7f7f7',      
    },
    colors:['#0052cc', '#04a08b', '#00baff', '#ff9920', '#ff562f'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '15%',
        colors: {
        backgroundBarColors: ['#f0f0f0'],
        backgroundBarOpacity: 1,
      },
          },
        },
        dataLabels: {
          enabled: false
        },
 
        xaxis: {
          categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Set', 'Sun'],
        },
        legend: {
          show: true,
      position: 'top',
          horizontalAlign: 'center',
        },
        fill: {
          opacity: 1
        }
        };

        var chart = new ApexCharts(document.querySelector("#charts_widget_1_chart"), options);
        chart.render();



     var options = {
          series: [{
          name: 'Study',
          data: [44, 55, 57, 56, 61]
        }, {
          name: 'Exams',
          data: [76, 85, 80, 98, 87]
        }],
          chart: {
          type: 'bar',
          stacked: true,
          height: 256,
          toolbar:{
            show: false,
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '50%',
            isDumbbell: true,
            borderRadius: 8,
          },
        },
        colors:["#0052cc","#d1d3e0"],
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 5,
          colors: ['transparent']
        },
        grid: {
          strokeDashArray: 10,
        },
        xaxis: {
          categories: ['Jan' ,'Feb', 'Mar', 'Apr', 'May'],
        },
        yaxis: {
          title: {
            text: ''
          }
        },
        fill: {
          opacity: 1
        },
        legend: {
            show: false,
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "" + val + " Hr"
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#reality-chart"), options);
        chart.render();




var dom = document.getElementById('chart-container');
var myChart = echarts.init(dom, null, {
  renderer: 'canvas',
  useDirtyRect: false
});
var app = {};

var option;

option = {
  series: [
    {
      type: 'gauge',
      progress: {
        show: true,
        width: 18
      },
      axisLine: {
        lineStyle: {
          width: 18
        }
      },
      axisTick: {
        show: false
      },
      splitLine: {
        length: 15,
        lineStyle: {
          width: 2,
          color: '#999'
        }
      },
      axisLabel: {
        distance: 25,
        color: '#999',
        fontSize: 10
      },
      anchor: {
        show: true,
        showAbove: true,
        size: 25,
        itemStyle: {
          borderWidth: 10
        }
      },
      title: {
        show: true,
        offsetCenter: [0, '95%'],
      },
      detail: {
        valueAnimation: true,
        fontSize: 40,
        offsetCenter: [0, '70%'],
      },
      data: [
        {
          value: 70,
          name: 'Your Progress'
        }
      ]
    }
  ]
};

if (option && typeof option === 'object') {
  myChart.setOption(option);
}

window.addEventListener('resize', myChart.resize);


  });


// slimScroll-------------------------------------------------
window.onload = function() {
  // Cache DOM Element
  var scrollable = $('.scrollable');
  
  // Keeping the Scrollable state separate
  var state = {
    pos: {
      lowest: 0,
      current: 0
    },
    offset: {
      top: [0, 0], //Old Offset, New Offset
    }
  }
  //
  scrollable.slimScroll({
    height: '284px',
    width: '',
    start: 'top'
  });
  //
  scrollable.slimScroll().bind('slimscrolling', function (e, pos) {
    // Update the Scroll Position and Offset
    
    // Highest Position
    state.pos.highest = pos !== state.pos.highest ?
      pos > state.pos.highest ? pos : state.pos.highest
    : state.pos.highest;
    
    // Update Offset State
    state.offset.top.push(pos - state.pos.lowest);
    state.offset.top.shift();
    
    if (state.offset.top[0] < state.offset.top[1]) {
      console.log('...Scrolling Down')
      // ... YOUR CODE ...
    } else if (state.offset.top[1] < state.offset.top[0]) {
      console.log('Scrolling Up...')
      // ... YOUR CODE ...
    } else {
      console.log('Not Scrolling')
      // ... YOUR CODE ...
    }
  });
};
// slimScroll------------------------------------------------- End
