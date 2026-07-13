$(function () {

  'use strict';


    var options = {
    chart: {
      height: 378,
      type: 'line',
      stacked: false,
      toolbar: {
        show: false
      },
    },
    stroke: {
      width: [0, 2, 5],
      curve: 'smooth'
    },
    plotOptions: {
      bar: {
        columnWidth: '40%',
        endingShape: 'rounded',        
      },
      
    },
    colors: ["#2444e8", "#fbadbf", "#45b6c6"],
    series: [{
      name: 'Project',
      type: 'column',
      data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
    }, {
      name: 'Supplier',
      type: 'area',
      data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
    }, {
      name: 'Invoice',
      type: 'line',
      data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
    }],
    fill: {
        type: 'gradient',
        gradient: {
            inverseColors: true,
            shade: 'light',
            type: "horizontal",
            shadeIntensity: 0.25,
            gradientToColors: undefined,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100, 100, 100]
        }
    },
    labels: ['01/01/2003', '02/01/2003','03/01/2003','04/01/2003','05/01/2003','06/01/2003','07/01/2003','08/01/2003','09/01/2003','10/01/2003','11/01/2003'],
    markers: {
      size: 0
    },
    xaxis: {
      type:'datetime',
      axisBorder: {
        show: true,
        color: '#eff1ff',
      },  
      axisTicks: {
        show: true,
        color: '#eff1ff',
      },    
    },
    yaxis: {
      min: 0
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: function (y) {
          if(typeof y !== "undefined") {
            return  y.toFixed(0) + "%";
          }
          return y;
          
        }
      }
    },
    legend: {
    position: 'top',
           horizontalAlign: 'right',
      labels: {
        useSeriesColors: true
      },
      offsetX: 0,
      offsetY: 0,
      markers: {
        customHTML: [
          function() {
            return ''
          }, function() {
            return ''
          }, function() {
            return ''
          }
        ]
      }
    }
  }

  var chart = new ApexCharts(
    document.querySelector("#crm_dash_2"),
    options
  );

  chart.render();



  var options = {
        series: [{
          name: 'Inflation',
          data: [189, 156, 123, 118]
        }],
        chart: {
          height: 150,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'top', // top, center, bottom
            },
        columnWidth: '15%',
        endingShape: 'rounded',
          }
        },
        dataLabels: {
          enabled: false,
        },
        colors:['#45b6c6'],
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr"],
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
          tooltip: {
            enabled: false,        
          }
        },
    grid: {
      yaxis: {
      lines: {
        show: false
      }
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

      var chart = new ApexCharts(document.querySelector("#meetingschart3"), options);
      chart.render();
  
  
  
  
  var options = {
        series: [{
          name: 'Inflation',
          data: [189, 156, 123, 118]
        }],
        chart: {
          height: 120,
          type: 'bar',
        },
        plotOptions: {
          bar: {
            dataLabels: {
              position: 'top', // top, center, bottom
            },
        columnWidth: '15%',
        endingShape: 'rounded',
          }
        },
        dataLabels: {
          enabled: false,
        },
        colors:['#2444e8'],
        xaxis: {
          categories: ["Jan", "Feb", "Mar", "Apr"],
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
          tooltip: {
            enabled: false,        
          }
        },
    grid: {
      yaxis: {
      lines: {
        show: false
      }
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

      var chart = new ApexCharts(document.querySelector("#meetingschart4"), options);
      chart.render();




    
         
    var options = {
          series: [70, 38],
          chart: {
          type: 'donut',
             height: 140,
              width: 140,
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
        colors:['#7047ee', '#e8e1ff'],
        };

        var chart = new ApexCharts(document.querySelector("#chart41"), options);
        chart.render();
    
        
         
    var options = {
          series: [32, 80],
          chart: {
          type: 'donut',
             height: 140,
              width: 140,
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
        colors:['#fc696a', '#ffe8e9'],
        };

        var chart = new ApexCharts(document.querySelector("#chart42"), options);
        chart.render();
        
         
    var options = {
          series: [102, 12],
          chart: {
          type: 'donut',
             height: 140,
              width: 140,
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
        colors:['#05825f', '#ebf9f5'],
        };

        var chart = new ApexCharts(document.querySelector("#chart43"), options);
        chart.render();
    
        
        var options = {
          series: [{
          name: 'This Week',
          type: 'column',
          data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        }, {
          name: 'Previous Week',
          type: 'line',
          data: [323, 642, 435, 527, 443, 622, 117, 231, 822, 222, 412, 116]
        }],
          chart: {
          height: 236,
          type: 'line',
          toolbar: {
            show: false
          }
        },
        stroke: {
          width: [0, 3],
            curve: 'smooth',
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            borderRadius: 3
          },
        },
        legend: {
          show: false,
        },
        colors:['#fc696a', '#05825f'],
        dataLabels: {
          enabled: false,
          enabledOnSeries: [1]
        },
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        };

        var chart = new ApexCharts(document.querySelector("#chart44"), options);
        chart.render();
    
    
    var options = {
        series: [17, 22, 19],
        chart: {
          type: 'donut',
            width: '100%',
            height: 236,       },
        colors:['#7047ee', '#3596f7', '#ffa800'],
        labels: ["On line", "in Store", "Marketing"],
        legend: {
          show: true,
          position: 'bottom',
          horizontalAlign: 'center', 
        },
        dataLabels: {
            enabled: false,
          },
        responsive: [{
          breakpoint: 1367,
          options: {
            chart: {
              height: 283
            },
          },
        },
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
          },
        }]
      };

      var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
      chart.render();
    
    $('.activity-div').slimScroll({
        height: '250px'
    });



        Apex.grid = {
      padding: {
        right: 0,
        left: 0
      }
    }

    Apex.dataLabels = {
      enabled: false
    }

    var randomizeArray = function (arg) {
      var array = arg.slice();
      var currentIndex = array.length, temporaryValue, randomIndex;

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
        id: 'sparkline1',
        group: 'sparklines',
        type: 'area',
        height: 233,
        sparkline: {
          enabled: true
        },
      },
      stroke: {
        curve: 'straight'
      },
      fill: {
        opacity: 1,
        colors: ['#689f38']
      },
      series: [{
        name: 'Sales',
        data: randomizeArray(sparklineData)
      }],
      labels: [...Array(24).keys()].map(n => `2018-09-0${n+1}`),
      yaxis: {
        min: 0
      },
      xaxis: {
        type: 'datetime',
      },
      colors: ['#689f38'],
      
    }
    
    new ApexCharts(document.querySelector("#spark1"), spark1).render();



    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        autoplay: true,
        dots: false,
        responsive: {
          0: {
            items: 1,
            nav: false
          },
          600: {
            items: 1,
            nav: false
          },
          1000: {
            items: 1,
            nav: false,
            margin: 20
          }
        }
      });

    
    
    
        var options = {
          series: [{
              name: 'Earning',
              data: [44, 55, 41, 67, 22, 43, 21, 33, 54]
            }],
          chart: {
          foreColor:"#bac0c7",
          type: 'bar',
          height: 310,
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
        colors:['#7047ee'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            borderRadius: 3
          },
        },
        dataLabels: {
          enabled: false
        },
 
        xaxis: {
          type: 'datetime',
          categories: ['08/01/2021 GMT', '08/02/2021 GMT', '08/03/2021 GMT', '08/04/2021 GMT','08/05/2021 GMT', '08/06/2021 GMT', '08/07/2021 GMT', '08/08/2021 GMT', '08/09/2021 GMT'
          ],
        },
        legend: {
          show: false,
        },
        fill: {
          opacity: 1
        }
        };

        var chart = new ApexCharts(document.querySelector("#charts_widget_1_chart"), options);
        chart.render();
    
    
    
}); // End of use strict