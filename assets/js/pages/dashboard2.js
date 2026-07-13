$(function () {

  'use strict';
  
      
  var options = {
    chart: {
    height: 150,
    width: 150,
    type: "radialBar"
    },

    series: [60],
    colors: ['#673ab7'],
    plotOptions: {
    radialBar: {
      hollow: {
      margin: 0,
      size: "60%"
      },
      track: {
      background: '#e7daff',
      },

      dataLabels: {
      showOn: "always",
      name: {
        show: false,
      },
      value: {
        offsetY: 5,
        color: "#111",
        fontSize: "20px",
        show: true
      }
      }
    }
    },

    stroke: {
    lineCap: "round",
    },
    labels: ["Progress"]
  };

  var chart = new ApexCharts(document.querySelector("#revenue1"), options);

  chart.render();
  
  var options = {
    chart: {
    height: 150,
    width: 150,
    type: "radialBar"
    },

    series: [50],
    colors: ['#3da643'],
    plotOptions: {
    radialBar: {
      hollow: {
      margin: 0,
      size: "60%"
      },
      track: {
      background: '#e9f5ea',
      },

      dataLabels: {
      showOn: "always",
      name: {
        show: false,
      },
      value: {
        offsetY: 5,
        color: "#111",
        fontSize: "20px",
        show: true
      }
      }
    }
    },

    stroke: {
    lineCap: "round",
    },
    labels: ["Progress"]
  };

  var chart = new ApexCharts(document.querySelector("#revenue2"), options);

  chart.render();
  
  var options = {
    chart: {
    height: 150,
    width: 150,
    type: "radialBar"
    },

    series: [34],
    colors: ['#fdac42'],
    plotOptions: {
    radialBar: {
      hollow: {
      margin: 0,
      size: "60%"
      },
      track: {
      background: '#fde5ba',
      },

      dataLabels: {
      showOn: "always",
      name: {
        show: false,
      },
      value: {
        offsetY: 5,
        color: "#111",
        fontSize: "20px",
        show: true
      }
      }
    }
    },

    stroke: {
    lineCap: "round",
    },
    labels: ["Progress"]
  };

  var chart = new ApexCharts(document.querySelector("#revenue3"), options);

  chart.render();
  
  
  
  var options = {
    series: [
      {
      name: "Applications",
      data: [15, 22, 35, 49, 50, 12, 28, 20, 33, 39, 85, 98]
      },
      {
      name: "Shortlisted",
      data: [5, 15, 25, 30, 25, 8, 18, 21, 32, 39, 62, 72]
      },
    ],
    chart: {
    height: 340,
    type: 'bar',
    zoom: {
    enabled: false
    },        
    toolbar: {
    show: false,
    },
  },
  dataLabels: {
    enabled: false
  },
  colors: ['#673ab7', '#3da643'],
  grid: {     
    show: true,
  },
    
    plotOptions: {
      bar: {
      horizontal: false,
      columnWidth: '40%',
      endingShape: 'rounded'
      },
    },

   legend: {
    show: true,
     position: 'top',
        horizontalAlign: 'left', 
   },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    labels: {
      show: true,
    },
    axisBorder: {
      show: true,
    },
    axisTicks: {
      show: true,
    },
    },

  yaxis: {
    labels: {
      show: true,
    }
  },
  };

  var chart = new ApexCharts(document.querySelector("#active_jobs"), options);
  chart.render();

      var options = {
          series: [{
          data: [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46]
        }],
          chart: {
          type: 'area',
          height: 100,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          curve: 'smooth',
      width: 2,
        },
        fill: {
          opacity: 1,
        },
        yaxis: {
          min: 0
        },
        colors: ['#51ce8a'],
        };

        var chart = new ApexCharts(document.querySelector("#followers-spark"), options);
        chart.render();
  
  
    
  
    var options = {
          series: [{
          data: [51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46, 47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62]
        }],
          chart: {
          type: 'area',
          height: 100,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          curve: 'smooth',
      width: 2,
        },
        fill: {
          opacity: 1,
        },
        yaxis: {
          min: 0
        },
        colors: ['#4d7cff'],
        };

        var chart = new ApexCharts(document.querySelector("#growth-spark"), options);
        chart.render();
  
    
  
    var options = {
          series: [{
          data: [47, 45, 27, 93, 53, 61, 27, 54, 43, 19, 46, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35]
        }],
          chart: {
          type: 'area',
          height: 100,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          curve: 'smooth',
      width: 2,
        },
        fill: {
          opacity: 1,
        },
        yaxis: {
          min: 0
        },
        colors: ['#733aeb'],
        };

        var chart = new ApexCharts(document.querySelector("#post-spark"), options);
        chart.render();
  
  
    
  
    var options = {
          series: [{
          data: [51, 35, 41, 35, 27, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 93, 53, 61, 27, 54, 43, 19, 46, 47]
        }],
          chart: {
          type: 'area',
          height: 100,
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          curve: 'smooth',
      width: 2,
        },
        fill: {
          opacity: 1,
        },
        yaxis: {
          min: 0
        },
        colors: ['#fec801'],
        };

        var chart = new ApexCharts(document.querySelector("#interactions-spark"), options);
        chart.render();
  
  
}); // End of use strict


$(document).ready(function() {
    $("#calendar").fullCalendar();
});