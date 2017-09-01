<!doctype html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <title>流量统计</title></title>

    <template file="Cudatabase/Common/_cssjs"/>
    <style>
        body{color: #fff;}
        .wraprow{height: 100%;}
        .wrapcol{height: 100%;}
        .cwrapcol{border-left: 1px solid rgba(255,255,255,0.2);border-right: 1px solid rgba(255,255,255,0.2);}
        .row-md-4{ height: 33.333%;}
        .crow{border-top: 1px solid rgba(255,255,255,0.2);border-bottom: 1px solid rgba(255,255,255,0.2);}
        .row-md-3{height: 25%;}
        .row-md-8{ height: 66.666%;}
        .row-md-6{ height: 49%;}
        .row-md-9{ height: 75%;}
		.row-md-12{ height: 100%;}
        
    </style>
    <script src="//cdn.bootcss.com/echarts/3.5.3/echarts.min.js"></script>
</head>
<body class="bdanaindex">

<div class="anaindexwrap">
   <div class="row wraprow">
       <div class="col-md-6 wrapcol">
           <div id='statistic1' class="row-md-6">
               1
           </div>
           <div id='statistic2' class="row-md-6">
               2
           </div>
       </div>
       <div id='statistic3' class="col-md-6 wrapcol cwrapcol">
           3
       </div>
   </div>
</div>

<script type="text/javascript">
    var chart_change = function() {
		//过去一个月
		var chart1 = echarts.init(document.getElementById('statistic1'));
		option1 = {
				title:{
					text:'{$monthago.title}',
					textStyle:{
						color:'#fff',
						fontSize:'20'
					},
					// subtext:'本周配额:{$monthago.rquota}',
					// subtextStyle:{
					// 	fontSize:'10'
					// },
				},
				color: ['#ff3d3d', '#00a0e9'],
				tooltip: {
					trigger: 'item',
					position:["50%","50%"],
					axisPointer: {
						type: 'cross'
					}
				},
				legend: {
					textStyle: {color: '#eee'},
					x: 'left',
					padding: [10, 20, 0, 120],
					data: [{name: 'PV({$monthago["data"]["sum"][0][0]})',textStyle: {color: 'red'}},
						   {name: 'UV({$monthago["data"]["sum"][0][1]})',textStyle: {color: '#1AA1E4'}},
						   {name: 'IP({$monthago["data"]["sum"][0][2]})',textStyle: {color: 'green'}}
						   ],
					selected: {
						'PV({$monthago["data"]["sum"][0][0]})': true,
						'UV({$monthago["data"]["sum"][0][1]})': true,
						'IP({$monthago["data"]["sum"][0][2]})': true,
					}
				},
				grid: {
					left: '8%',
					right: '3%',
					bottom: '8%',
					top: '15%',
					containLabel: false
				},
				xAxis: {
					type: 'category',
					boundaryGap: false,
					splitLine: { //网格线
						show: false,
						lineStyle: {
							color: ['#fff'],
							type: 'dashed'
						}
					},
					name:'时间',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'30px'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},
					data:  <?=json_encode(array_reverse($monthago['data']['items'][0]));?>
				},
				yAxis: {
					splitLine: { //网格线
						show: true,
						lineStyle: {
							color: ['#b1b1b1'],
							type: 'dashed'
						}
					},
					name:'访问量',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'20'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},	
				},
				series: [{
					name: 'PV({$monthago["data"]["sum"][0][0]})',
					type: 'line',
					data: <?=json_encode($monthago['PV_data']);?>,
					// xAxisIndex: 1,
            		// smooth: true,
					label: {
						normal: {
							show: true,
							position: 'top' //值显示
						}
					}
					}, {
					name: 'UV({$monthago["data"]["sum"][0][1]})',
					type: 'line',
					data: <?=json_encode($monthago['UV_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					}
				}, 
				{
					name: 'IP({$monthago["data"]["sum"][0][2]})',
					type: 'line',
					data:<?=json_encode($monthago['IP_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top',
							color:'green'
						}
					},
					lineStyle:{
						normal:{
							color:'green'
						}
					}
				}
			]
		};
		chart1.setOption(option1);
		//过期7天统计数据
		var chart2 = echarts.init(document.getElementById('statistic2'));
		option2 = {
				title:{
					text:'{$weekago.title}',
					textStyle:{
						color:'#fff',
						fontSize:'20'
					},
					// subtext:'本周配额:{$weekago.rquota}',
					// subtextStyle:{
					// 	fontSize:'10'
					// },
				},
				color: ['#ff3d3d', '#00a0e9'],
				tooltip: {
					trigger: 'item',
					position:["50%","50%"],
					axisPointer: {
						type: 'cross'
					}
				},
				legend: {
					textStyle: {color: '#eee'},
					x: 'left',
					padding: [10, 20, 0, 120],
					data: [{name: 'PV({$weekago["data"]["sum"][0][0]})',textStyle: {color: 'red'}},
						   {name: 'UV({$weekago["data"]["sum"][0][1]})',textStyle: {color: '#1AA1E4'}},
						   {name: 'IP({$weekago["data"]["sum"][0][2]})',textStyle: {color: 'green'}}
						   ],
					selected: {
						'PV({$weekago["data"]["sum"][0][0]})': true,
						'UV({$weekago["data"]["sum"][0][1]})': true,
						'IP({$weekago["data"]["sum"][0][2]})': true,
					}
				},
				grid: {
					left: '8%',
					right: '3%',
					bottom: '8%',
					top: '15%',
					containLabel: false
				},
				xAxis: {
					type: 'category',
					boundaryGap: false,
					splitLine: { //网格线
						show: false,
						lineStyle: {
							color: ['#fff'],
							type: 'dashed'
						}
					},
					name:'时间',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'30px'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},
					data:  <?=json_encode(array_reverse($weekago['data']['items'][0]));?>
				},
				yAxis: {
					splitLine: { //网格线
						show: true,
						lineStyle: {
							color: ['#b1b1b1'],
							type: 'dashed'
						}
					},
					name:'访问量',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'20'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},	
				},
				series: [{
					name: 'PV({$weekago["data"]["sum"][0][0]})',
					type: 'line',
					data: <?=json_encode($weekago['PV_data']);?>,
					// xAxisIndex: 1,
            		// smooth: true,
					label: {
						normal: {
							show: true,
							position: 'top' //值显示
						}
					}
					}, {
					name: 'UV({$weekago["data"]["sum"][0][1]})',
					type: 'line',
					data: <?=json_encode($weekago['UV_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					}
				}, 
				{
					name: 'IP({$weekago["data"]["sum"][0][2]})',
					type: 'line',
					data:<?=json_encode($weekago['IP_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top',
							color:'green'
						}
					},
					lineStyle:{
						normal:{
							color:'green'
						}
					}
				}
			]
		};
		chart2.setOption(option2);
		//昨天统计数据
		var chart3 = echarts.init(document.getElementById('statistic3'));
		option3 = {
				title:{
					text:'{$yesterday.title}',
					textStyle:{
						color:'#fff',
						fontSize:'20'
					},
					subtext:'本周配额:{$yesterday.rquota}',
					subtextStyle:{
						fontSize:'10'
					},
				},
				color: ['#ff3d3d', '#00a0e9'],
				tooltip: {
					trigger: 'item',
					position:["50%","50%"],
					axisPointer: {
						type: 'cross'
					}
				},
				legend: {
					textStyle: {color: '#eee'},
					x: 'left',
					padding: [10, 20, 0, 120],
					data: [{name: 'PV({$yesterday["data"]["sum"][0][0]})',textStyle: {color: 'red'}},
						   {name: 'UV({$yesterday["data"]["sum"][0][1]})',textStyle: {color: '#1AA1E4'}},
						   {name: 'IP({$yesterday["data"]["sum"][0][2]})',textStyle: {color: 'green'}}
						   ],
					selected: {
						'PV({$yesterday["data"]["sum"][0][0]})': true,
						'UV({$yesterday["data"]["sum"][0][1]})': true,
						'IP({$yesterday["data"]["sum"][0][2]})': true,
					}
				},
				grid: {
					left: '5%',
					right: '3%',
					bottom: '3%',
					top: '10%',
					containLabel: false
				},
				xAxis: {
					type: 'category',
					boundaryGap: false,
					splitLine: { //网格线
						show: false,
						lineStyle: {
							color: ['#fff'],
							type: 'dashed'
						}
					},
					axisLine:{
						lineStyle:{
							width:2
						}
					},
					name:'时间',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'30px'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},
					data:  <?=json_encode(array_reverse($yesterday['data']['items'][0]));?>
				},
				yAxis: {
					splitLine: { //网格线
						show: true,
						lineStyle: {
							color: ['#b1b1b1'],
							type: 'dashed'
						}
					},
					name:'访问量',
					nameTextStyle:{
							color:['#fff'],
							fontsize:'20'
					},
					axisLabel: {
						textStyle:{
							color: ['#fff']
						}
					},	
				},
				series: [{
					name: 'PV({$yesterday["data"]["sum"][0][0]})',
					smooth:true,
					type: 'line',
					data: <?=json_encode($yesterday['PV_data']);?>,
					// xAxisIndex: 1,
            		// smooth: true,
					label: {
						normal: {
							show: true,
							position: 'top' //值显示
						}
					}
					}, {
					name: 'UV({$yesterday["data"]["sum"][0][1]})',
					smooth:true,
					type: 'line',
					data: <?=json_encode($yesterday['UV_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top'
						}
					}
				}, 
				{
					name: 'IP({$yesterday["data"]["sum"][0][2]})',
					smooth:true,
					type: 'line',
					data:<?=json_encode($yesterday['IP_data']);?>,
					label: {
						normal: {
							show: true,
							position: 'top',
							color:'green'
						}
					},
					lineStyle:{
						normal:{
							color:'green'
						}
					}
				}
			]
		};
		chart3.setOption(option3);
    };
    chart_change();
</script>
</body>
</html>
