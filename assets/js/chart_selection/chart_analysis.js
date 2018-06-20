jQuery(document).ready(function(){
	//setTimeout(function(){
		get_chart_for_analysis();
	//},1000)
});

function get_chart_for_analysis(){
	user_id = jQuery("#user_id").val();
	var min_reading = [];max_reading =[]; reading_date = [];
	 var url = BASE_URL + "Admin_chart_selection/get_chart_for_analysis"
           $.ajax({
            type: "POST",
            url: url,
            dataType:'json',
            data: {'user_id':user_id}, // serializes the form's elements.
            success: function (data)
            {
            	console.log(data);
            	var cnt = 1;
            	jQuery.each(data.response,function(i,v){
            		jQuery.each(v['chart_vals'],function(i,v){
            			min_reading.push(v['min_reading']);
            			max_reading.push(v['max_reading']);
            			reading_date.push(v['created']);
            		});
            		$("#chart_div").append('<div id="chart_div_'+cnt+'" style="height: 370px"></div>');
            		//alert(min_reading.replace(/,\s*$/, ""));

            		draw_chart(v['chart_name'],min_reading,max_reading,reading_date,"chart_div_"+cnt+"");
            		
            	cnt++;
            	});
            	
            },
            error: function (data) {
                fancyAlert('Something unexpected happened please try after sometime.','error');
                console.log("Error in chart draw:"+data);
            }
        });
}

function draw_chart(chart_name,min_reading,max_reading,date_of_reading,div_id){
	if(chart_name == 'LineChart'){
		echart_draw_line_chart(min_reading,max_reading,date_of_reading,div_id);
	}
	if(chart_name == "BarChart"){
		echart_draw_bar_chart(min_reading,max_reading,date_of_reading,div_id);	
	}
	if(chart_name == "ColumnChart"){
		echart_draw_column_chart(min_reading,max_reading,date_of_reading,div_id);	
	}

}

// jQuery(document).ready(function() {

    <!--basic line echarts init-->
function echart_draw_line_chart(min_reading,max_reading,date_of_reading,div_id){
	
    var chartOneDom = document.getElementById(div_id);
    var chartOne = echarts.init(chartOneDom);
console.log(chartOne);
    var chartOneOption = {
        color: ['#4aa9e9','#eac459'],

        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['Max','Min']
        },

        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data: date_of_reading
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'//'{value} °C'
                }
            }
        ],
        series : [
            {
                name:'Max',
                type:'line',
                // data:[11, 11, 15, 13, 12, 13, 10],
                data:max_reading,
                markPoint : {
                    data : [
                        {type : 'max', name: 'Max'},
                        {type : 'min', name: 'Min'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: 'Average'}
                    ]
                }
            },
            {
                name:'Min',
                type:'line',
                // data:[1, -2, 2, 5, 3, 2, 0],
                data:min_reading,
                markPoint : {
                    data : [
                        {name : 'Min of Week', value : -2, xAxis: 1, yAxis: -1.5}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name : 'Average'}
                    ]
                }
            }
        ]
    };
    //alert(typeof chartOneOption);
    if (chartOneOption && typeof chartOneOption === "object") {
        chartOne.setOption(chartOneOption, true);
    }
}
 
 function echart_draw_column_chart(min_reading,max_reading,date_of_reading,div_id){
 	<!--Rainfall and Evaporation echarts init-->

    var dom = document.getElementById(div_id);
    var rainChart = echarts.init(dom);

    var app = {};
    option = null;
    option = {
        color: ['#4aa9e9','#67f3e4'],
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['Max','Min']
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                // data : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                data : date_of_reading
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'Sale',
                type:'bar',
                // data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
                data:max_reading,
                markPoint : {
                    data : [
                        {type : 'max', name: 'Max'},
                        {type : 'min', name: 'Min'}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name: 'Average'}
                    ]
                }
            },
            {
                name:'Market',
                type:'bar',
                // data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],
                data:min_reading,
                markPoint : {
                    data : [
                        {name : 'Max', value : 182.2, xAxis: 7, yAxis: 183, symbolSize:18},
                        {name : 'Min', value : 2.3, xAxis: 11, yAxis: 3}
                    ]
                },
                markLine : {
                    data : [
                        {type : 'average', name : 'Average'}
                    ]
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        rainChart.setOption(option, false);
    }
 }


    <!--basic area echarts init-->
function echart_draw_bar_chart(min_reading,max_reading,date_of_reading,div_id){
    var dom = document.getElementById(div_id);
    var myChart = echarts.init(dom);

    var app = {};
    option = null;
    option = {
        color: ['#8dcaf3','#67f3e4', '#4aa9e9' ],

        tooltip : {
            trigger: 'axis'
        },
        legend: {
            // data:['Preorder','Sale','Deal']
            data:['Min','Max']
        },

        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                // data : ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']
                data:date_of_reading
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'Max',
                type:'line',
                smooth:true,
                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                // data:[10, 12, 21, 54, 260, 830, 710]
                data:max_reading
            },
            {
                name:'Min',
                type:'line',
                smooth:true,
                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                // data:[30, 182, 434, 791, 390, 30, 10]
                data:min_reading
            },
            // {
            //     name:'Preorder',
            //     type:'line',
            //     smooth:true,
            //     itemStyle: {normal: {areaStyle: {type: 'default'}}},
            //     data:[1320, 1132, 601, 234, 120, 90, 20]
            // }
        ]
    };


    if (option && typeof option === "object") {
        myChart.setOption(option, false);
    }
}