<?php
namespace Cudatabase\Controller;

use Cudatabase\Controller\CuBaseController;
use Libs\Util\BaiduTJ;
class AnalysController extends CuBaseController {
    /**
     * 百度统计功能
     *
     * @return void
     */
    public function yesterday(){
        $title='昨日统计';
        $baiduTj = new BaiduTJ();
        //获取可用网站列表
        // $siteList = $baiduTj->getSiteList();
        //www.sx-cc.com 的site_id 为11080954        
        //获取当前时间和昨天
        $today = new \Datetime('now');
        $interval = new \DateInterval('P1D');
        $today->sub($interval);
        $yesterday = $today->format('Ymd');
        
        //接口字段说明：https://tongji.baidu.com/dataapi/file/TongjiApiFile.pdf

        //获取趋势报告
        $data = $baiduTj->getReportData(array('site_id' => 11080954,                   //站点ID
            'method' => 'trend/time/a',             //趋势分析报告
            'start_date' => $yesterday,             //所查询数据的起始日期
            'end_date' => $yesterday,               //所查询数据的结束日期
            'metrics' => 'pv_count,visit_count,ip_count,visitor_count,new_visitor_count',  //所查询指标为PV和UV
            'max_results' => 0,                     //返回所有条数
            'gran' => 'hour',                        //按天粒度
        ));
        //获取受访页面
        // $visit = $baiduTj->getReportData(array('site_id' => 11080954,                   
        //     'method' => 'overview/getTimeTrendRpt',         
        //     'start_date' => $yesterday,             
        //     'end_date' => $yesterday,               
        //     'metrics' => 'pv_count,visitor_count,ip_count',  //所查询指标
        //     'max_results' => 0,              
        //     'gran' => 'day',                    
        // ));
        // $area  = $baiduTj->getReportData(['site_id'=>11080954,
        //     'method'=>'visit/province/a',
        //     'start_date' => $yesterday,             
        //     'end_date' => $yesterday,               
        //     'metrics' => 'pv_count,visitor_count,ip_count',  //查询指标
        //     // 'max_results' => 0,              
        //     // 'gran' => 'day'
        // ]);
  
        // $data  = $baiduTj->getReportData(['site_id'=>11080954,
        //     'method'=>'trend/time/a',
        //     'start_date' => $yesterday,             
        //     'end_date' => $yesterday,               
        //     'metrics' => 'pv_count,visitor_count,ip_count',  //查询指标
        //     'max_results' => 0,              
        //     // 'gran' => 'day'
        // ]);
        if(empty($data)){
            var_dump($baiduTj->header);
            var_dump($baiduTj->getError());
            exit;
        }
        // var_dump($data);exit;
        $rquota = $baiduTj->header['rquota'];
        $PV_data = [];
        $UV_data = [];
        $IP_data = [];
        foreach($data['items'][1] as $item){
            array_unshift($PV_data,($item[0]=='--'?'0':$item[0]));
            array_unshift($UV_data,($item[1]=='--'?'0':$item[1]));
            array_unshift($IP_data,($item[2]=='--'?'0':$item[2]));
        }
        $this->assign("yesterday",compact('data','PV_data','UV_data','IP_data','title','rquota'));
        // return $this->display('echart');
    }
    //过去7天
    public function weekago(){
        $title='过去7天';
        $baiduTj = new BaiduTJ();
        //获取当前时间和昨天
        $now = new \Datetime('now');
        $interval = new \DateInterval('P7D');
        $today = $now->format('Ymd');
        $now->sub($interval);
        $weekago = $now->format('Ymd');
        
        //接口字段说明：https://tongji.baidu.com/dataapi/file/TongjiApiFile.pdf

        //获取趋势报告
        $data = $baiduTj->getReportData(array('site_id' => 11080954,                   //站点ID
            'method' => 'trend/time/a',             //趋势分析报告
            'start_date' => $weekago,             //所查询数据的起始日期
            'end_date' => $today,               //所查询数据的结束日期
            'metrics' => 'pv_count,visit_count,ip_count,visitor_count,new_visitor_count',  //所查询指标为PV和UV
            'max_results' => 0,                     //返回所有条数
            'gran' => 'day',                        //按天粒度
        ));
        if(empty($data)){
            var_dump($baiduTj->header);
            var_dump($baiduTj->getError());
            exit;
        }
        $rquota = $baiduTj->header['rquota'];
        $PV_data = [];
        $UV_data = [];
        $IP_data = [];
        foreach($data['items'][1] as $item){
            array_unshift($PV_data,($item[0]=='--'?'0':$item[0]));
            array_unshift($UV_data,($item[1]=='--'?'0':$item[1]));
            array_unshift($IP_data,($item[2]=='--'?'0':$item[2]));
        }
        $this->assign('weekago',compact('data','PV_data','UV_data','IP_data','title','rquota'));
        // return $this->display('echart');
    }
    //过去30天
    public function monthago(){
        $title='过去一个月';
        $baiduTj = new BaiduTJ();
        //获取当前时间和昨天
        $now = new \Datetime('now');
        $interval = new \DateInterval('P30D');
        $today = $now->format('Ymd');
        $now->sub($interval);
        $monthago = $now->format('Ymd');
        
        //接口字段说明：https://tongji.baidu.com/dataapi/file/TongjiApiFile.pdf

        //获取趋势报告
        $data = $baiduTj->getReportData(array('site_id' => 11080954,                   //站点ID
            'method' => 'trend/time/a',             //趋势分析报告
            'start_date' => $monthago,             //所查询数据的起始日期
            'end_date' => $today,               //所查询数据的结束日期
            'metrics' => 'pv_count,visit_count,ip_count,visitor_count,new_visitor_count',  //所查询指标为PV和UV
            'max_results' => 0,                     //返回所有条数
            'gran' => 'day',                        //按天粒度
        ));
       
        if(empty($data)){
            var_dump($baiduTj->header);
            var_dump($baiduTj->getError());
            exit;
        }
        $rquota = $baiduTj->header['rquota'];
        $PV_data = [];
        $UV_data = [];
        $IP_data = [];
        foreach($data['items'][1] as $item){
            array_unshift($PV_data,($item[0]=='--'?'0':$item[0]));
            array_unshift($UV_data,($item[1]=='--'?'0':$item[1]));
            array_unshift($IP_data,($item[2]=='--'?'0':$item[2]));
        }
        $this->assign('monthago',compact('data','PV_data','UV_data','IP_data','title','rquota'));
        // return $this->display('echart');
    }
    //百度统计首页
    public function tongji(){
        $this->yesterday();
        $this->monthago();
        $this->weekago();
        return $this->display('echart');
    }
}